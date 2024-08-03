<?php session_start(); ?> 

<!DOCTYPE html>

<html lang="en">

    <head>
        <title>About Us - JCS</title>
        <link rel="icon" href="Pictures/smallicon.png">
        <link rel="shortcut icon" href="Pictures/smallicon.png">
        <link rel="stylesheet" href="CSS/aboutus.css">
        <link rel="stylesheet" href="CSS/footer.css">
        <link rel="stylesheet" href="CSS/slider.css">
        <script src="slides.js"></script>
    </head>
    <body>

        <?php
            // Create database & connection
            include 'connection.php';

            //Declarations
            $signup = $login = $logout = $myprofile = "";

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
                //Show sign up and login button  
                $signup = "Sign Up"; 
                $login = "Login";
            }
        ?>
  
        <header class="HeaderHeader">

            <div class="login_register">
                <ul id = abc>
                  <li id= "active"><a href="aboutus.php">About Us</a></li>
                  <li id= "topnav"><a href="orderhistory.php">Order History</a></li>
                  <li id= "topnav"><a href="appointment.php">Appointment</a></li>  
                  <li id= "topnav"><a href="index.php">Home</a></li>
                  <li><a href="index.php"><img class="logo" src="Pictures/icon.png" alt="Jacqueline Cheers System"></a></li>
                </ul>

                <ul>
                  <li><a href="logout.php"  ><?php echo $logout?></a></li>
                  <li><a href="register.php"><?php echo $signup?></a></li>
                  <li>&nbsp&nbsp|&nbsp</li>
                  <li><a href="login.php" ><?php echo $login?></a></li>
                  <li><a href="myProfile.php"  ><?php echo $myprofile?></a></li>
                  <li><img class="image_login_register" src="Pictures/login_register_icon.png" alt="Login and register icon"></a>
                </ul>
                
            </div>

            <a class = "cart_position" href="cart.php"> <img id="cart" src="Pictures/cart.png" alt="Cart"> </a>
              
        </header><br>

        <!--Slider HERE!!-->
        <div class="slidebanner-container">
            <div class="mySlides">
              <img src="Banner/Banner1.png" style="width:100%">
            </div>
            
            <div class="mySlides">
            <img src="Banner/Banner2.png" style="width:100%">
            </div>

            <div class="mySlides">
            <img src="Banner/Banner3.png" style="width:100%">
            </div>
            
            <a class="prev" onclick="nextslide(-1)">&#10094;</a>
            <a class="next" onclick="nextslide(1)">&#10095;</a>
            
            <div class = "dotbullet">
                <span class="dot" onclick="currentSlide(1)"></span> 
                <span class="dot" onclick="currentSlide(2)"></span> 
                <span class="dot" onclick="currentSlide(3)"></span> 
            </div>
            <script src="slides.js"></script>
            </div>
            <br>
            <br><br>
        <!--END Slider HERE!!-->
        
        <hr id="designline"/><br><br><br>
        <p class="intro">Jacqueline Hair Salon is founded in the year of 
            <span id="bold">2008</span>. With <span id="bold">14</span> years of experience, 
            Madam <span id="bold">Jacqueline Siew Seoul Ngo</span> has been striving to provide the best hair services in Sibu, Sarawak.
            The hair salon focuses to provide services such as hair cutting for both women and men, regular and formal styling, hair colour services, hair treatments and so on.
        </p><br><br>

        <div class="col">
            <div class="col1">
                <ul>
                    <li id="title">Contact Us</li>
                    <br><hr id="designline2"/>
                    <li class="list">Wechat ID: siew2249</li>
                    <li class="list"><a class="list" href="https://www.facebook.com/jacquelinengosaloon?mibextid=ZbWKwL">Facebook : Jacqueline Ngo</a></li>
                    <li class="list">Tel: 014-6802249</li>
                </ul>
            </div>
            <div class="col2">
                <ul>
                    <li id="title">Reach Us</li>
                    <br><hr id="designline2"/>
                    <li class="list">Location: LOT 2376, TKT 1,</li>
                    <li class="list">JALAN BUKIT LIMA TIMUR,</li>
                    <li class="list">96000 SIBU, SARAWAK.</li>
                </ul>
            </div>
            <div class="col3">
                <ul>
                    <li id="title">Operating Hours</li>
                    <br><hr id="designline2"/>
                    <li class="list">9:00 a.m. ~ 6:30 p.m.</li>
                    <li class="list">Regular Day Off: Thursday</li>
                </ul>
            </div>
        </div>

        <br><br><br><br><br><br>
        <footer class="FooterFooter" id="Push_footer">
            <div class="FFooterUpperPortion">
                <div class="FFooterIcon">
                    <img src="Pictures/icon.png" alt="Jacqueline Cheers System">
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