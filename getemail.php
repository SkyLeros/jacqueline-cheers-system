<?php session_start(); ob_start(); ?>
<!DOCTYPE html>
<html>
    <head>
        <title>Forgot Password - JCS</title>
        <meta charset="UTF-8">
        <link rel="icon" href="Pictures/smallicon.png">
        <link rel="shortcut icon" href="Pictures/smallicon.png">
        <link rel="stylesheet" href="CSS/forgotpw.css">
        <link rel="stylesheet" href="CSS/footer.css">
    </head>

    <body>
        <?php 
            //Connection
            include 'connection.php';
            $emailE = "";

            if ($_SERVER["REQUEST_METHOD"] == "POST") //Submitted email
            {
                if(empty($_POST["email"]))
                {
                    $emailE = "Email is required!";
                }
                else
                {
                    $email = $_POST["email"];
                    $sql = "SELECT id, email FROM user WHERE email='$email'"; //Select the user id
                    $isFound = mysqli_query($conn,$sql); //Check is it exists
                
                    //Found the user
                    if(mysqli_num_rows($isFound) == 1) 
                    {
                        //fetch the id
                        $result = mysqli_fetch_assoc($isFound);
                        $id = $result["id"];
                        $_SESSION['user_id'] = $id; //Set session variable
                        $_SESSION['email'] = $email;

                        $email = $_SESSION['email'];//Get email
                        $code = rand(100000,999999); //Get randomized code
                        $_SESSION['code'] =$code;

                        //Content for email
                        $message = '
                        <html lang="en">
                            <head>
                                <title>[JCS] Please reset your password</title>
                            </head>
                            <body style="font-family: Times New Roman;">
                                <img src = "https://i.imgur.com/qS64mJ9.png" alt="Jacqueline Cheers System">
                                <p style="font-size:28px; font-family: Times New Roman; font-weight:bold;">Reset your password</p><br>
                                <hr style="width:75%; border-top: 1px solid gray;">
                                <p style="font-size:18px; font-family: Times New Roman;">We heard that you lose your password. Sorry about that!</p>
                                <p style="font-size:18px; font-family: Times New Roman;">Below shows your 6-digits code, enter the code to reset your password!</p><br>
                                <p style="font-size:28px; font-family: Times New Roman; font-weight:bold;">'.$code.'</p><br><br><br><br>
                                <p style="font-size:18px; font-family: Times New Roman; ">Thanks, <br>The JCS Team</p><br><br>
                                <p style="font-size:12px; font-family: Times New Roman; ">This is an auto generated message, please do not reply.</p>
                            </body>
                        </html>';

                        //Get user email
                        $to = $email;
                        //Subject
                        $subject = "[JCS] Please reset your password";
                        // Always set content-type when sending HTML email
                        $headers = "MIME-Version: 1.0" . "\r\n";
                        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                        // More headers
                        $headers .= 'From: <noreply@JCS.com>' . "\r\n";
                        $MAIL = mail($to,$subject,$message,$headers); //Send email
                        if($MAIL == true)
                        {
                            //echo "SUCCESS MAIL!";
                        }
                        else if($MAIL == false)
                        {
                            //echo "FAILED MAIL!";
                        }  
                        //End email

                        header("Refresh:2; forgotpw.php");
                        ob_end_flush();
                    }
                    else
                    {
                        $emailE = "Invalid Email! Please try again.";
                    }
                }
            }
             
        ?>

        <header>
            <a href="index.php"> <img class="logo" src="Pictures/icon.png" alt="Jacqueline Cheers System"> </a>
        </header><br><br>
        
        <div class="column">

                <h3 class="login">Please enter your registered email.</h3>
                <hr id="line"/><br><br><br><br>
                <form name="getemail" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                    <div class="row">
                        <input id="email" name="email" ><br><br><br>
                        <span class="error"> <?php echo $emailE; ?> </span>
                    </div>

                    <div class="button">
                        <input type="submit" name = "submit" value="Submit" > 
                    </div>
                    <br><br><br><br>

                </form>
        </div>
        <br><br><br><br><br>

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