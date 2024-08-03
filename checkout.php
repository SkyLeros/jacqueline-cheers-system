<?php session_start(); ob_start(); //Declarations ?>
<!DOCTYPE html>

<html lang="en">
    <head>
        <title>Check Out - JCS</title>
        <link rel="icon" href="Pictures/icon.png">
        <link rel="shortcut icon" href="Pictures/icon.png">
        <link rel="stylesheet" href="CSS/checkout.css">
        <link rel="stylesheet" href="CSS/footer.css">

        <script type="text/javascript">

                function showPaymentMethod(answer)
                {
                    document.getElementById("temptotal").style.display = "none";
                    document.getElementById("temptotal1").style.display = "none";

                    if(answer.value == "StandaryDelivery")
                    {
                        document.getElementById("validation").classList.remove("PaymentMethod");
                        document.getElementById("labelPaymentMethod").classList.remove("PaymentMethodlabel");
                        document.getElementById("shippingSubtotal").classList.remove("shippingSubtotal");
                        document.getElementById("total1").classList.remove("total1");
                        document.getElementById("total2").classList.add("total2");
                        document.getElementById("total3").classList.remove("total1");
                        document.getElementById("total4").classList.add("total2");
                    }
                    else 
                    {
                        document.getElementById("validation").classList.add("PaymentMethod");  
                        document.getElementById("labelPaymentMethod").classList.add("PaymentMethodlabel");  
                        document.getElementById("shippingSubtotal").classList.add("shippingSubtotal");  
                        document.getElementById("total1").classList.add("total1");
                        document.getElementById("total2").classList.remove("total2");
                        document.getElementById("total3").classList.add("total1");
                        document.getElementById("total4").classList.remove("total2");
                    }
                }
        </script>
    </head>

    <body>
        <?php
        // Create database & connection
        include 'connection.php';

        //Declarations
        $signup = $login = $logout = $myprofile = $ShippingOptions = "";

        //Check if session login is defined
        if (isset($_SESSION['login'])) 
        {
            //If it is defined, then we check is it Logged in 
            if($_SESSION['login'] === "Logged In")
            {
                //Show logout and profile button  
                $logout = "Logout"; 
                $myprofile = "My Profile";
            }
        }
        else //session login not set yet
        {
            //Redirect to the home page
            header('Location: index.php');
        } 
        //END SESSION DECLARATION 
        ?>
    
        <header class="HeaderHeader">
            <a href="index.php"> <img class="logo" src="Pictures/icon.png" alt="Jacqueline Cheers System"> </a>
            <span class = "menu">
                <h3>Checking Out - Payment</h3>
                <a class = "cart_position" href="cart.php"> <img id="cart" src="Pictures/cart.png" alt="Cart"> </a>
            </span>
            <div class="login_register">
                <ul>
                  <li><a href="logout.php"  ><?php echo $logout?></a></li>
                  <li>| </li>
                  <li><a href="myProfile.php" ><?php echo $myprofile?></a></li>
                  <li><img class="image_login_register" src="Pictures/login_register_icon.png" alt="Login and register icon"></a>
                </ul>
            </div>
        </header>

        <main>
            <?php
                $userid = $_SESSION['user_id'];
                $cartid = $_SESSION['cartid'];

                //Select user info from the table
                $sql = "SELECT firstname, lastname, region, phone, _state, postcode, _address, city 
                FROM user WHERE id='$userid'";
                $result = mysqli_query($conn,$sql); 
                $user_making_payment = mysqli_fetch_assoc($result); //Fetch the selected info


            //Validate dummy PaymentMethod
                $sql = "SELECT grand_total FROM cart WHERE userid='$userid'";
                $isFound = mysqli_query($conn,$sql);

                //Fetch the grantotal
                $result = mysqli_fetch_assoc($isFound); 

                $validate_error = "";
                $validate_error2 = "";
                $order_id = "";

                if ($_SERVER["REQUEST_METHOD"] === "POST")
                {
                   $ShippingOptions = $_POST["shippingOptions"];
                   $merchandise = $_SESSION['merchandise'];
                   $status = 0;

                    if(  $ShippingOptions == "SelfCollection")
                    {
                        $shippingfee = 0;
                        $paymentotal = $merchandise;
                    }  
                    else
                    {
                        $shippingfee = $_SESSION['shipping'];
                        $paymentotal = $_SESSION['PaymentTotal'];
                        $PaymentMethod = $_POST["validation"];

                        if(empty($PaymentMethod))
                        {
                            $validate_error = "Payment method is required!";
                            $validate_error2 = " Please try again!";
                            $ShippingOptions = "";
                        }
                       
                    }

                    
                    if( $validate_error === "" && $validate_error2 === "")
                    {
                        
                        date_default_timezone_set("Asia/Kuching");
                        $date = date("Y-m-d");
                        $time = date("H:i:s");

                        $sql = "INSERT INTO saleorder (userid, _date, _time, shipping_fee, merchandise_total, grand_total,ShippingOption, _status) 
                        VALUES ('$userid', '$date', '$time', '$shippingfee', '$merchandise', '$paymentotal','$ShippingOptions','$status')";
                        $isFound = mysqli_query($conn,$sql);

                        if ($isFound === TRUE) {
                            //echo "New record created successfully";
                        } else {
                            //echo "Error: " .  mysqli_error($conn);
                        }

                        //Fetch the order id
                        $sql = "SELECT id FROM saleorder WHERE userid = '$userid' AND _date = '$date' AND _time = '$time'"; 
                        $isFound = mysqli_query($conn,$sql); 
                        $result = mysqli_fetch_assoc($isFound);
                        //Store the order id into the variable
                        $order_id = $result['id'];

                        //Select every product in CART ITEM 
                        $sql = "SELECT * FROM cart_item WHERE cart_id = '$cartid'"; 
                        $isFound = mysqli_query($conn,$sql); 
    
                        if(mysqli_num_rows($isFound) > 0)
                        {
                            while($row = mysqli_fetch_assoc($isFound)) //Insert every product into the transactions details
                            {
                                $proid = $row['product_id'];
                                $quan = $row['quantity'];
                                $subtotal = $row['subtotal'];

                                $sql = "SELECT productname FROM product WHERE id = '$proid'";
                                $isFound_2 = mysqli_query($conn,$sql); 
                                $product = mysqli_fetch_assoc($isFound_2);
                                $product_name = $product['productname'];

                                $sql = "INSERT INTO order_details (order_id, quantity, total_price, product_name) VALUES
                                ('$order_id', '$quan', '$subtotal', '$product_name')";

                                $isInsert = mysqli_query($conn,$sql); 
                                if ($isInsert === TRUE) {
                                    //echo "New record FOR TRANSACTIONS DETAILS created successfully";
                                } else {
                                    //echo "Error record FOR TRANSACTIONS DETAILS : " .  mysqli_error($conn);
                                }
                            }
                        }
                        if($ShippingOptions == "SelfCollection")
                        {
                            echo '<p class = "success">Your Order Has Been Placed!</p>';
                            
                            echo '<p class = "normal">
                                Date & Time:&nbsp'.$date.'&nbsp'.$time.
                                '<br> Generating Invoice, Please allow
                                ';
                            echo '<span class = "popup">
                                POP UP 
                                </span>
                                ';
                                
                            echo '<span class = "normal">
                                !<br>
                                Redirecting...
                                </span></p><br><br>';


                            //Redirect back to orderhistory.php after successful payment
                            header( "refresh:6 ; url=orderhistory.php" );

                            //Open a new tab for receipt
                            echo '<script type="text/javascript">
                                setTimeout
                                (
                                    function() 
                                    {
                                        window.open("receipt_invoice.php?receipt='.$order_id.'&check=true&type=Invoice")
                                    }, 3000
                                );
                                </script>';
                        }
                        else
                        {
                            echo '<p class = "success">Validation Successful!</p>';
                        
                            echo '<span class = "normal" style=" display: block;">
                                Redirecting...
                                </span></p><br><br>';

                            //Redirect back to index.php after successful payment
                            header( "refresh:6 ; url=payment.php?paymentMethod=$PaymentMethod&receiptID=$order_id" );
                        }
                        ob_end_flush();
                    }
                    else
                    {
                        echo '<p class="error">'.$validate_error." ".$validate_error2.'</p>';
                    }

                }

                function test($data)
                {
                    $data = trim($data);
                    $data = stripslashes($data);
                    $data = htmlspecialchars($data);
                    return $data;
                }
            ?>

                <table style="width:100%; font-size: 20px;">
                <tr>
                <td><strong>Delivery Address </strong></td>
                <td width="80%"><img id="delivery" src="Pictures/delivery.png"></td>
                </tr>
                </table>

            <?php
                echo '<table style="width:20%; font-size: 18px;">';
                echo '<tr><td>'.$user_making_payment['_address'].",".'</td></tr>'.
                '<tr><td>'.$user_making_payment['city'].",".$user_making_payment['postcode'].",".'</td></tr>'.
                '<tr><td>'.$user_making_payment['_state'].", Malaysia.".'</tr>';
                echo '<tr><td><br></td></tr>';
                echo '<tr><td style="font-weight:bold;">'.$user_making_payment['lastname']." ".$user_making_payment['firstname'].'</td></tr>';
                echo '<tr><td style="font-weight:bold;">'."+".$user_making_payment['region']." ".$user_making_payment['phone'].'</td></tr>';
                echo '</table>';
            ?>
            <br>
            <table style="width:100%;  font-size: 18px;">
            <tr style="background-color: #fc9e6c" align= center height="80px;">
                <td style= "width:55%">Product(s)</td>
                <td style= "width:15%">Quantity</td>
                <td style= "width:15%">Unit Price</td>
                <td style= "width:15%">Subtotal</td>
            </tr>

            <?php
                $total_quantity = 0;

                $sql = "SELECT cart_item.cart_id, product.id, product.productname, cart_item.quantity, product.price, cart_item.subtotal 
                FROM cart_item
                INNER JOIN product
                ON cart_item.product_id = product.id /*Find the same PRODUCT ID*/
                WHERE cart_item.cart_id = '$cartid'"; /*Locate that person's CART ID*/

                $result = mysqli_query ($conn,$sql);

                if(mysqli_num_rows($result) > 0)
                {
                    $total_quantity = mysqli_num_rows($result); //Fetch the total number of items
                    while($row = mysqli_fetch_assoc($result))
                    {
                        echo'<tr align = "center" height="80px;">';
                        echo '<td>'.$row['productname']. '</td>';
                        echo '<td>'.$row['quantity'].'</td>';
                        echo '<td>' .$row['price']. '</td>';
                        echo '<td>' .$row['subtotal']. '</td>';
                        echo'</tr>';
                    }
                }


                if($total_quantity <= 5 )
                {
                    $shippingfee = 15.50;
                }
                else if($total_quantity > 5 && $total_quantity <= 10)
                {
                    $shippingfee = 28.50;
                }
                else if($total_quantity > 10 && $total_quantity <= 15)
                {
                    $shippingfee = 40.50;
                }
                else if($total_quantity > 15 && $total_quantity <= 20)
                {
                    $shippingfee = 63.50;
                }
                else if($total_quantity > 15 && $total_quantity <= 20)
                {
                    $shippingfee = 84.50;
                }
                else
                {
                    $shippingfee = 100.50;
                }

                $_SESSION['shipping'] = $shippingfee;
                
                $sql = "SELECT grand_total FROM cart WHERE id = '$cartid'"; 
                $result = mysqli_query ($conn,$sql);
                $user_making_payment = mysqli_fetch_assoc($result);

                $_SESSION['merchandise'] = $user_making_payment['grand_total'];

                $paymentotal = $user_making_payment['grand_total'] + $shippingfee;
                
                echo '</table><hr style="border: 1px solid #C0C0C0;">';
                echo '<br><br><br>';
                echo '<table style="font-size:20px; width:100%; border-top: 1px solid gray;">';
                echo '<tr><td align=left width="88.8%">'."Merchandise Subtotal: ".
                '</td><td align=center>'.$user_making_payment['grand_total'].'</td></tr>';

                if (!empty($ShippingOptions) && $ShippingOptions=="StandaryDelivery")
                    echo '<tr ><td align=left width="88.8%">'."Shipping Subtotal: ".'</td><td align=center>'.$shippingfee.'</td></tr>';
                echo '<tr id="shippingSubtotal" class="shippingSubtotal"><td align=left width="88.8%">'."Shipping Subtotal: ".'</td><td align=center>'.$shippingfee.'</td></tr>';
                
                echo '<tr style="font-weight: bold;"><td align=left width="88.8%">'."Payment Total (".$total_quantity." Item): ".
                '</td><td align=center id="total1" class="total1">'.$paymentotal.'</td>
                <td align=center id="total2" class="total2">'.$user_making_payment['grand_total'].'</td>';

                if (empty($ShippingOptions) || $ShippingOptions=="SelfCollection")
                    echo '<td align=center id="temptotal">'.$user_making_payment['grand_total'].'</td></tr>';
                else
                    echo '<td align=center id="temptotal">'.$paymentotal.'</td></tr>';

                $_SESSION['PaymentTotal'] = $paymentotal;
            ?>

            </table><br>

            <!--Payment-->
            <table style="font-size:20px; width:100%; box-shadow: 0px 2px 5px 2px #888888; font-weight: bold;margin-bottom:30px;" >
            <tr >

            <?php
                echo '<td align=left id="total3" class="total1">Total: '."$".$paymentotal.'</td>';
                echo '<td align=left id="total4" class="total2">Total: '."$".$user_making_payment['grand_total'].'</td>';
                if (empty($ShippingOptions) || $ShippingOptions=="SelfCollection")
                    echo '<td align=left id="temptotal1" >Total: '."$".$user_making_payment['grand_total'].'</td>';
                else
                    echo '<td align=left " >Total: '."$".$paymentotal.'</td>';
            ?>

            <form name="payment" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
            <td>&nbsp&nbspShipping Options : <select name="shippingOptions" id="shippingOptions"  onchange="showPaymentMethod(this)">
                <option value="SelfCollection" <?php if (isset($ShippingOptions) && $ShippingOptions=="SelfCollection") echo 'selected="selected"';?> >Self-Collection</option>
                <option value="StandaryDelivery" <?php if (isset($ShippingOptions) && $ShippingOptions=="StandaryDelivery") echo 'selected="selected"';?>>Standary Delivery</option>
            </select></td>

            
           
            <td align=right width="20%">
                
                <label for="PaymentMethod" id="labelPaymentMethod" class="PaymentMethodlabel">Payment Method :&nbsp</label> </td></div> 
                <td align=center width="10%">
                    <select name="validation" id="validation" class="PaymentMethod">
                        <option value="" >-- Your Method --</option>
                        <option value="EWallet">E-Wallet</option>
                        <option value="BankTranfer">Bank Tranfer</option>
                    </select>
                    
                </td>
                <td>
                    <input class="b2" type="submit" name = "submit" value="Submit">
                </td>
            </form>
            </tr>
            </table>

            
        </main>

        <?php
            if ($_SERVER["REQUEST_METHOD"] === "POST")
            {
                if($validate_error === "" && $validate_error2 === "")
                {
                    //Remove the paid items in cart
                    $sql = "DELETE FROM cart_item WHERE cart_id = $cartid";
                    $isFound = mysqli_query($conn,$sql); 
                    if ($isFound === TRUE) {
                        //echo "Remove the paid items in cart successfully";
                    } else {
                        //echo "Error Remove the paid items: " .  mysqli_error($conn);
                    }
                }
                
            }
        ?>

        <footer class="FooterFooter" id="Push_footer">
            <div class="FFooterUpperPortion">
                <div class="FFooterIcon">
                    <img  src="Pictures/icon.png" alt="Jacqueline Cheers System">
                </div>
            </div>

            <br>
            <br>
            <hr id="FooterLine"/>

            <div class="FFooterBlocks">
                    <ul>
                      <li><img src="Pictures/wechat.png" alt="wechat">siew2249</li>
                      <li><a href="https://www.facebook.com/jacquelinengosaloon?mibextid=ZbWKwL" ><img src="Pictures/facebook.png" alt="facebook">Jacqueline Ngo</a></li>
                    </ul>
            </div>

            <div class="FFooterLowerPortion" >
              <sub class="Disclaimer">©2022 Jacqueline Cheers System - All Rights Reserved</sub>
            </div>
        </footer>  

    </body>
</html>