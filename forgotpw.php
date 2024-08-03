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
            include 'connection.php';
            $code = $_SESSION['code'];
            //Checking submitted code
            $codeE = "";
            if ($_SERVER["REQUEST_METHOD"] == "POST") //Submitted code
            {
                if($_POST["code"] == $code) //Correct code
                {
                    header('Location:resetpw.php');
                    ob_end_flush();
                }
                else
                {
                    $codeE = "Wrong Code, Please try again.";
                }
            }
             
        ?>

        <header>
            <a href="index.php"> <img class="logo" src="Pictures/icon.png" alt="Jacqueline Cheers System"> </a>
        </header><br><br>
        
        <div class="column">

                <h3 class="login">Please enter the 6-digit code sent to your registred email.</h3>
                <hr id="line"/><br><br><br><br>
                <form name="forgotpw" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                    <div class="row">
                        <input id="code" name="code" ><br><br><br>
                        <span class="error"> <?php echo $codeE; ?> </span>
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