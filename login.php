<?php session_start(); ob_start();?>
<!DOCTYPE html>
<html>
    <head>
        <title>Login - JCS</title>
        <meta charset="UTF-8">
        <link rel="icon" href="Pictures/smallicon.png">
        <link rel="shortcut icon" href="Pictures/smallicon.png">
        <link rel="stylesheet" href="CSS/login.css">
        <link rel="stylesheet" href="CSS/footer.css">
    </head>

    <body>
        <?php 
            //Declarations
            $email = $pw = "";
            $emailE = $pwE ="";
            $error= "";


            if ($_SERVER["REQUEST_METHOD"] == "POST")
            {
                //Validate Email
                if(empty($_POST["email"]))
                {
                    $emailE = "*Email is required!";
                }
                else 
                {
                    $email = test($_POST["email"]);
                    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) 
                    {
                        $emailE = "*Invalid email format!";
                    }
                    
                }

                //Validate Password
                if(empty($_POST["pw"]))
                {
                    $pwE = "*Password is required!";
                }
                else
                {
                    $pw = test($_POST["pw"]);
                }

            }

            function test($data)
            {
                $data = trim($data);
                $data = stripslashes($data);
                $data = htmlspecialchars($data);
                return $data;
            }

            //Connection
            include 'connection.php';
            //No error in input
            if (!empty($_POST["email"])&& !empty($_POST["pw"])) //Check whether the user exists 
            {
                $sql = "SELECT id FROM user WHERE email='$email' AND pwd='$pw'"; //Select the user id
                $isFound = mysqli_query($conn,$sql); //Check is it exists
                
                //Found the user
                if(mysqli_num_rows($isFound) == 1) 
                {
                    //fetch the id
                    $result = mysqli_fetch_assoc($isFound);
                    $id = $result["id"];
                    //Update the login status in table
                    $sql = "UPDATE user SET _login=1 WHERE id='$id'";
                    $result = mysqli_query ($conn,$sql);
                    //See if updated
                    if($result == true)
                    {
                        echo "UPDATED LOGIN";
                    } 
                    else
                    {
                        echo "Failed to update". $conn->error;
                    }
                    
                    //Set session variables
                    $_SESSION['email'] = $email;
                    $_SESSION['login'] = "Logged In";
                    $_SESSION['user_id'] = $id;
                    $sql = "SELECT id FROM cart WHERE userid='$id'"; //Select the cart id From SHOPPING CART
                    $isFound = mysqli_query($conn,$sql); 
                    
                    //Fetch the cart id
                    $result = mysqli_fetch_assoc($isFound); 
                    
                    //Store the cart id
                    $cartid = $result["id"]; 
                    $_SESSION['cartid'] = $cartid;
                    
                    //Close Connection
                    mysqli_close($conn);
                    
                    //Redirecting user
                    header("Location: index.php");
                    ob_end_flush();
                }   
                else
                {
                    $error = "Login Failed! Wrong Password or Email, Please try again!";
                }
            }
        ?>
        <header>
            <a href="index.php"> <img class="logo" src="Pictures/icon.png" alt="Jacqueline Cheers System"> </a>
        </header><br><br>
        
        <div class="column">

                <h3 class="login">Log In your JCS Account</h3>
                <hr id="line"/><br><br><br><br>
                <form name="login" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                    <div class="row">
                        <div class="col1">
                            <img id= "icon" src="Pictures/email.png">
                            <label for="Email">Email</label>
                        </div>
                        <div class="col2">
                            <input type="email" id="email" name="email" placeholder="Email.." value="<?php echo $email;?>" ><br>
                            <span class="error"> <?php echo $emailE; ?> </span>
                        </div>
                    </div>
                    <br>

                    <div class="row">
                        <div class="col1">
                            <img id= "icon" src="Pictures/pw.png">
                            <label for="pw">Password</label>
                        </div>
                        <div class="col2">
                            <input type="password" id="pw" name="pw" placeholder="Password.." value="<?php echo $pw;?>" ><br>
                            <span class="error"> <?php echo $pwE; ?> </span>
                        </div>
                    </div><br><br><br>

                    <div class="button">
                        <input type="submit" name = "submit" value="Login" > 
                    </div>

                    <div class ="forgotpw">
                        <a href ="getemail.php">Forgotten password?</a>
                    </div>

                    <div class = "login_error">
                    <span class = "login_fail"><?php echo $error; ?></span>
                    </div>

                    <div class="header_login">
                        <a href="register.php" >Not Registered Yet? Click here to Register!</a>
                    </div>

                </form>
        </div>
        <br><br><br><br><br><br><br><br><br>

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