<?php session_start(); ?>
<!DOCTYPE html>

<html lang="en">
    <head>
        <title>Cart - JCS</title>
        <link rel="icon" href="Pictures/smallicon.png">
        <link rel="shortcut icon" href="Pictures/smallicon.png">
        <link rel="stylesheet" href="CSS/cart.css">
        <link rel="stylesheet" href="CSS/footer.css">
    </head>

    <body>
    <section class = "spacing">
        <?php
            //For buttons in HEADER
            $signup = $login = $logout = $myprofile = "";
            //Check if session login is defined
            if (isset($_SESSION['login'])) 
            {
                //If it is defined, then we check is it Logged in 
                if($_SESSION['login'] === "Logged In")
                {
                    $logout = "Logout";
                    $myprofile = "My Profile";
                }
            }
            else //session login not set yet
            {
                $signup = "Sign Up";
                $login = "Login";
            }

            // Create onnection
            include 'connection.php';
                
            //Get the product id & quantity thru the Add Cart InputField in Index.php
            if ($_SERVER["REQUEST_METHOD"] == "POST")
            {
                //Get the product id and its quantity
                $pro_id = $_POST["product_id"];
                $pro_quantity = $_POST["quantity"];
            
                //Someone has logged in & NOT public user
                if (isset($_SESSION['login'])) 
                {
                    $isFound_Update = false;
                    $userid = $_SESSION['user_id']; //Retrieve userid for that person
                    $sql = "SELECT id FROM cart WHERE userid='$userid'"; //Select the cart id From SHOPPING CART
                    $isFound = mysqli_query($conn,$sql); 

                    //Fetch the cart id
                    $result = mysqli_fetch_assoc($isFound); 

                    //Store the cart id
                    $cartid = $result["id"]; 
                    $_SESSION['cartid'] = $cartid;

                    $sql = "SELECT price FROM product WHERE id='$pro_id'"; //Select the product price
                    $isFound = mysqli_query($conn,$sql); 

                    //Fetch the price
                    $result = mysqli_fetch_assoc($isFound); 
                    $pro_price = $result["price"]; //Store the price
                    $subtotal = $pro_price * $pro_quantity; //Calculate subtotal
                    
                    //Select all productID for that PERSON in Cart_Item TABLE (use cartID to identify that PERSON)
                    $sql = "SELECT product_id FROM cart_item WHERE cart_id = $cartid"; 
                    $isFound = mysqli_query($conn,$sql); 

                    //That PERSON already added some products into cart
                    if(mysqli_num_rows($isFound) > 0)
                    {
                        
                        while($row = mysqli_fetch_assoc($isFound))
                        {
                            
                            if($row['product_id'] === $pro_id ) //Found the same product id in CART
                            {
                                //Update the quantity
                                $sql = "SELECT quantity FROM cart_item WHERE cart_id= '$cartid' && product_id = '$pro_id'"; //Select the product price
                                $isFound = mysqli_query($conn,$sql); 
                                $result = mysqli_fetch_assoc($isFound); //Fetch the Quantity
                                $Ori_quantity =  $result['quantity'];
                                $Ori_quantity = $Ori_quantity + $pro_quantity ; //Add the quantity

                                $sql = "SELECT price FROM product WHERE id='$pro_id'"; //Select the product price
                                $isFound = mysqli_query($conn,$sql); 
                                $result = mysqli_fetch_assoc($isFound); //Fetch the price
                                $pro_price = $result["price"]; //Store the price

                                //Calculate new subtotal with new quantity
                                $subtotal = $Ori_quantity * $pro_price;

                                //Update the cart_Item with new quantity
                                $sql = "UPDATE cart_item 
                                SET quantity='$Ori_quantity',
                                subtotal = '$subtotal'
                                WHERE product_id='$pro_id'";
                                $result = mysqli_query ($conn,$sql);

                                //Once found, break the loop
                                $isFound_Update = true; 
                                break;
                            }
                            
                        }

                        //The ITEM HAS NOT BEEN FOUND after the LOOP is completed
                        if($isFound_Update !== true) 
                        {
                            //Insert the NEW ITEM into TABLE
                            $sql = "INSERT INTO cart_item (cart_id, product_id, quantity, subtotal)
                            VALUES ('$cartid', '$pro_id', '$pro_quantity', '$subtotal')";
        
                            $result = mysqli_query($conn, $sql);                    
                            if ($result === TRUE)
                            {
                                //echo "cart item added successfully".'<br>';
                            }
                            else
                            {
                                echo "Error adding cart item: " . mysqli_error($conn);
                            }
                        }
                    }
                    else //Never added any item yet
                    {
                        //Insert the ITEM
                        $sql = "INSERT INTO cart_item (cart_id, product_id, quantity, subtotal)
                        VALUES ('$cartid', '$pro_id', '$pro_quantity', '$subtotal')";
    
                        $result = mysqli_query($conn, $sql);                   
                        if ($result === TRUE)
                        {
                            //echo "cart item added successfully".'<br>';
                        }
                        else
                        {
                            echo "Error adding cart item: " . mysqli_error($conn);
                        }
                    }
                    
                }
            }
            
        ?>

        <header class="HeaderHeader">
            <a href="index.php"> <img class="logo" src="Pictures/icon.png" alt="Jacqueline Cheers System"> </a>
            <span class = "menu">
                <a class = "cart_position" href="cart.php"> <img id="cart" src="Pictures/cart.png" alt="Cart"> </a>
            </span>
            <div class="login_register">
                <ul>
                  <li><a href="logout.php"  ><?php echo $logout?></a></li>
                  <li><a href="register.php"><?php echo $signup?></a></li>
                  <li>&nbsp| </li>
                  <li><a href="login.php" ><?php echo $login?></a></li>
                  <li><a href="myProfile.php"  ><?php echo $myprofile?></a></li>
                  <li><img class="image_login_register" src="Pictures/login_register_icon.png" alt="Login and register icon"></a>
                </ul>
            </div>
        </header>
        
         
        <table class= "table">
        <tr style="background-color: #ffc94b" align= center>
            <td style= "width:50%">Product(s)</td>
            <td></td>
            <td>Quantity</td>
            <td></td>
            <td>Unit Price</td>
            <td>Subtotal</td>
            <td></td>
        </tr>
        

            
        <?php 
            //To calculate GRAND TOTAL
            $temp_total = $temp_subtotal = 0.00;
            
            //The CARTID session variable is only SET when USERs did log in
            if(isset($_SESSION['login']))
            {
                $cartid = $_SESSION['cartid'];
                
                //Join the product and cart_item tables to DISPLAY CART ITEM
                $sql = "SELECT cart_item.cart_id, product.id, product.productname, cart_item.quantity, product.price, cart_item.subtotal 
                FROM cart_item
                INNER JOIN product
                ON cart_item.product_id = product.id /*Find the same PRODUCT ID*/
                WHERE cart_item.cart_id = '$cartid'"; /*Locate that person's CART ID*/

                $result = mysqli_query($conn, $sql);                
                if ($result == true)
                {
                    //echo "Join cart and product SUCCESS!";
                }
                else
                {
                    echo "Error joining cart: " . mysqli_error($conn);
                }

                $result = mysqli_query ($conn,$sql);
                
                //Registered user
                //If there is more than 0 rows
                if(mysqli_num_rows($result) > 0)
                {
                    while($row = mysqli_fetch_assoc($result))
                    {
                        echo'<tr align = "center">';
                        echo '<td >'.$row['productname']. '</td>';
                        echo '<td> <a href="minus_quantity.php?update_pro='.$row['id'].'&update_pro_quantity='.$row['quantity'].'&update_cart_id='.$row['cart_id'].'"><img id="buttons" src = "Pictures/minus.png"></a></td>';
                        echo '<td>'.$row['quantity'].'</td>';
                        echo '<td> <a href="plus_quantity.php?update_pro='.$row['id'].'&update_pro_quantity='.$row['quantity'].'&update_cart_id='.$row['cart_id'].'"><img id="buttons" src = "Pictures/plus.png"></a></td>';
                        echo '<td>' .$row['price']. '</td>';
                        echo '<td>' .$row['subtotal']. '</td>';?>
                        <td>
                            <form action="remove_item.php" method="post">
                                <input type="hidden" name="update_pro" value="<?php echo $row['id'] ?>">
                                <input type="hidden" name="update_cart_id" value="<?php echo $row['cart_id'] ?>">
                                <button type="submit" onclick="return confirm('Are You Sure You Want To Remove <?php echo $row['productname'] ?> From Cart?')">
                                    <img id="buttons" src = "Pictures/remove_item.png">
                                </button>
                            </form>
                        </td></tr>

                        <?php        
                        $temp_subtotal = $row['subtotal'];
                        $temp_total = $temp_total + $temp_subtotal;
                    }
                    
                    //Insert the grand total into SHOPPINGCART table
                    $sql = "UPDATE cart SET grand_total = '$temp_total' WHERE id = '$cartid'";
                    $result = mysqli_query($conn, $sql);  
                    if ($result === TRUE)
                    {
                        //echo "Grand total added successfully".'<br>';
                    }
                    else
                    {
                        echo "Error adding grand total: " . mysqli_error($conn);
                    }
                    
                    echo'<tr>';
                    echo '<td colspan="6" align="right">'."<strong> Total: ". '</strong> </td>';
                    echo '<td colspan="7" align="left">'."<strong> $" .$temp_total. '</strong> </td>';
                    echo '</table>';
                    echo '</section>';

                    //Check Out Button
                    echo '<p style="text-align:right;">';
                    //Proceed to payment
                    //Registered users
                    echo '<a href = "checkout.php"><button class="b2" type="checkout" value="checkout"> Check Out </button></a>';
                    echo'</p>';
                }
                else
                {
                    echo'<tr>';
                    echo'<td  colspan="8" align="center"> Empty Cart! </td>';
                    echo'</tr>';
                    echo '</table>';
                    echo '</section>';
                }
            }
            else //Public User!
            {
                //END TABLE FOR THE CART
                echo '</table>';
                echo '</section>';

                //DISPLAY THE NOTIFICATION
                echo '<div class="col">'; 
                echo    '<div class="col1"></div>'; 
                echo    '<div class="col2">'; 
                echo        '<p class ="notify">Please <span id ="bold">Log In</span> Before You Can Start Adding Item(s) To Your Shopping Cart!</p><br><br>';
                echo        '<div class="placement"><a href="login.php" class="buttons">Login</a></div>';
                echo    '</div>';
                echo    '<div class="col3"></div>';
                echo '</div>';
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