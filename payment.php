<?php session_start(); ob_start(); //Declarations ?>
<!DOCTYPE html>

<html lang="en">
    <head>
        <title>Payment - JCS</title>
        <link rel="icon" href="Pictures/icon.png">
        <link rel="shortcut icon" href="Pictures/icon.png">
        <link rel="stylesheet" href="CSS/payment.css">
        <link rel="stylesheet" href="CSS/footer.css">
    </head>

    <body>
    <?php
        // Create database & connection
        include 'connection.php';

        //Declarations
        $logout = $myprofile = $error = "";

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

        if(isset($_GET['paymentMethod']))
            $paymentMethod = $_GET['paymentMethod'];

        if(isset($_GET['receiptID']))
            $order_id = $_GET['receiptID'];

        if($paymentMethod == "EWallet")
        {
            $pageTitle = "Select E-Wallet";
            $subTitle = "Choose Your E-Wallet";
            $selectionImg = array("Pictures/SPayGlobal.png","Pictures/TnG.jpg","Pictures/boost.png","Pictures/Quinpay.png","Pictures/shopeePay.png","Pictures/GrabPay.png");
            $selectionValue = array("SPay&nbspGlobal","TnG","Boost","Quinpay","ShopeePay","GrabPay");
        }
        else if($paymentMethod == "BankTranfer")
        {
            $pageTitle = "Select Bank";
            $subTitle = "Choose Your Bank";
            $selectionImg = array("Pictures/publicBank.png","Pictures/bankIslam.png","Pictures/hongLeong.jpg","Pictures/mayBank.png","Pictures/Ambank.png","Pictures/BSN.png");
            $selectionValue = array("Public&nbspBank","Bank&nbspIslam","Hong&nbspLeong&nbspBank","Maybank","Ambank","BSN");
        }

        //END SESSION DECLARATION 
        ?>
        <header class="HeaderHeader">
            <a href="index.php"> <img class="logo" src="Pictures/icon.png" alt="Jacqueline Cheers System"> </a>
            <span class = "menu">
                <h3><?php echo $pageTitle ?></h3>
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
             if(isset($_GET['check']) && $_GET['check'] == "true")
             {
                date_default_timezone_set("Asia/Kuching");
                $date = date("Y-m-d");
                $time = date("H:i:s");
                $sql ="UPDATE saleorder SET _date = '$date' , _time = '$time', _status = 1 WHERE id = $order_id ";
                $result = mysqli_query($conn,$sql);

                echo '<p class = "success">Payment Successful!</p>';
                            
                echo '<p class = "normal">
                        Date & Time:&nbsp'.$date.'&nbsp'.$time.
                        '<br> Generating Receipt, Please allow
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
                                window.open("receipt_invoice.php?receipt='.$order_id.'&check=true&type=Receipt")
                            }, 3000
                        );
                      </script>';
             }

             if ($_SERVER["REQUEST_METHOD"] === "POST")
             {
                 $selection = $_POST['selection'];
     
                 if(empty( $selection))
                 {
                     $error = "Please ".$subTitle;
                 }
     
                 if($error == "")
                 {
                    echo '<p class = "success">Please wait, we are redirecting to '.$selection.'!</p>';
                        
                    echo '<span class = "normal" style=" display: block;">
                        Redirecting...
                        </span></p><br><br>';

                    //Redirect to related bank or ewallet page
                    header( "refresh:6 ; url=payment.php?paymentMethod=$paymentMethod&receiptID=$order_id&check=true" );
                 }else
                     echo '<p class="error">'.$error.'</p>';
                    ob_end_flush();
             }
            ?>
            <div id="content">
                <h2><?php echo $subTitle?> </h2>;
                <form name="payment" method="post" action="payment.php?paymentMethod=<?php echo $paymentMethod?>&receiptID=<?php echo $order_id?>">
                    <?php 
                    for ($x = 0; $x < count($selectionImg); $x++) {
                        echo '
                        <label>
                            <input type="radio" name="selection" value='."$selectionValue[$x]".' >
                            <img class="ImgPic" src='."$selectionImg[$x]".' alt="Option "'."$x".'>
                        </label>';
                    }?>
                    <button class="b2" type="submit" name = "submit" value="Submit">Confirm</button>
                </form>
            </div>
        </main>

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