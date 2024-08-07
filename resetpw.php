<?php session_start(); ob_start(); ?>
<!DOCTYPE html>
<html>
    <head>
        <title>Reset Password - JCS</title>
        <meta charset="UTF-8">
        <link rel="icon" href="Pictures/smallicon.png">
        <link rel="shortcut icon" href="Pictures/smallicon.png">
        <link rel="stylesheet" href="CSS/forgotpw.css">
        <link rel="stylesheet" href="CSS/footer.css">
    </head>

    <body>
        <?php 
            include 'connection.php';
            function test($data)
            {
                $data = trim($data);
                $data = stripslashes($data);
                $data = htmlspecialchars($data);
                return $data;
            }

            $pwE = $pw_strong = "";

            if ($_SERVER["REQUEST_METHOD"] == "POST") //Submitted code
            {
                //Validate password
                if(empty($_POST["npw"]))
                {
                    $pwE = "*Password is required!";
                }
                else
                {
                    $pw = test($_POST["npw"]);
                    if(strlen($pw) <6 )
                    {
                        $pwE = "*Please use password with at least 6 digits!";
                    }
                    else if(!preg_match("#[0-9]+#",$pw))
                    {
                        $pwE = "*Must contain at least 1 number!";
                    }
                    else if (!preg_match("#[A-Z]+#",$pw))
                    {
                        $pwE = "*Must contain at least 1 uppercase letter!";
                    }
                    else if (!preg_match("#[a-z]+#",$pw))
                    {
                        $pwE = "*Must contain at least 1 lowercase letter!";
                    }
                    else if (!preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/',$pw))
                    {
                        $pwE = "*Must contain at least 1 special character!";
                    }
                    else if (preg_match('/\s/',$pw)) //find whitespace
                    {
                        $pwE = "*Must not contain any whitespace!";
                    }
                    else
                    {
                        $pw_strong = "Password Reset Successful, redirecting to login...";
                    }
                }

                if($pwE === "" && $pw_strong === "Password Reset Successful, redirecting to login...") //Correct code
                {
                    $id = $_SESSION['user_id'];
                    $sql= "UPDATE user SET pwd= '$pw' WHERE id = '$id'";
                    $result = mysqli_query($conn,$sql);
                    if ($result === TRUE) {
                        echo "Pw updated!";
                    } else {
                        echo "Error: ". $conn->error;
                    }

                    header("Refresh:2; login.php");
                    ob_end_flush();
                }
            }
             
        ?>

        <header>
            <a href="index.php"> <img class="logo" src="Pictures/icon.png" alt="Jacqueline Cheers System"> </a>
        </header><br><br>
        
        <div class="column">

                <h3 class="login">Please enter your new password.</h3>
                <h4>*Password must be At least six digits, containing at least one number, one uppercase and one lowercase letter, one special character.</h4>
                <hr id="line"/><br><br><br><br>
                <form name="pw" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                    <div class="row">
                        <input type="password" id="npw" name="npw" ><br><br><br>
                        <span class="error"> <?php echo $pwE; ?> </span>
                        <span class="correct"> <?php echo $pw_strong; ?> </span>
                    </div>

                    <div class="button">
                        <input type="submit" name = "submit" value="Reset" > 
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