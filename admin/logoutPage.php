<?php session_start();  
	session_unset();
    session_destroy();
?>

<!DOCTYPE html>

<html lang="en">

    <head>
        <title>Admin Logout - JCS</title>
        <link rel="icon" href="Pictures/smallicon.png">
        <link rel="shortcut icon" href="Pictures/smallicon.png">
        <link rel="stylesheet" href="CSS/login.css">
    </head>
    <body>

  
        <header>
            <img class="logo" src="Pictures/icon.png" alt="Jacqueline Cheers System">
        </header><br><br>

        <div class="column">

                <h3 class="login">You have successfully logged out!</h3>
                <?php
                    date_default_timezone_set("Asia/Kuala_Lumpur");
                    echo "Date / Time : " . date("d-M-Y H:i:s T") . "<br>";
                    
                ?>
                <br>
                Click here to <a href="adminLogin.php">login</a> again.
                <br><br>
        </div>

        <br><br><br><br><br><br>  
    </body>
</html>
