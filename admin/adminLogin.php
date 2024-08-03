<?php session_start();
    include 'connection.php'; ob_start();?>
<!DOCTYPE html>
<html>
    <head>
        <title>Admin Login - JCS</title>
        <meta charset="UTF-8">
        <link rel="icon" href="Pictures/smallicon.png">
        <link rel="shortcut icon" href="Pictures/smallicon.png">
        <link rel="stylesheet" href="CSS/login.css">
        <style>
            .button{
                padding-top: 50px;
                text-align: center;
                margin-bottom: 4%;
            }

            .button button{
                padding: 0px 20px;
                height: 50px;
                width: 150px;
                font-family: Arial, Helvetica, sans-serif;
                border-radius: 5px;
                border: 2px solid #f9a66c;
                font-size: 18px;
                margin: 0 20px;
                background-color: #ffc94b;
            }

            .button button:hover{
                background-color: #f9a66c;
                color:white;
                box-shadow: 1px 1px 10px #f9a66c ;
            }
        </style>
    </head>

    <body>
        <?php 
                
            if (isset($_POST["email"]) && isset($_POST["pw"])) {
                function validate($data){
                    $data = trim($data);
                    $data = stripslashes($data);
                    $data = htmlspecialchars($data);
                    return $data;
                }
            
                $adminEmail = validate($_POST["email"]);
                $password = validate($_POST["pw"]);
                    
                if (empty($adminEmail)) {
                    header("Location: adminLogin.php?errorEmail=*Email is required!");
                    exit();
                }
                else if(empty($password)){
                    header("Location: adminLogin.php?errorPW=*Password is required!");
                    exit();
                }
                else{
                    $sql = "SELECT * FROM admin WHERE adminEmail='$adminEmail' AND adminPW='$password'";
                    $result = mysqli_query($connection, $sql);
                            
                    if (mysqli_num_rows($result) === 1) {
                        $row = mysqli_fetch_assoc($result);
                            if ($row['adminEmail'] === $adminEmail && $row['adminPW'] === $password){
                                $_SESSION['adminEmail'] = $adminEmail;
                                $_SESSION['adminID'] = $num['adminID'];
                                header("Location: admindashboard.php");
                                exit();
                            }
                            else{
                                header("Location: adminLogin.php?error=Login Failed! Incorrect email or password, Please try again!");
                                exit();
                            }
                    }
                    else{
                        header("Location: adminLogin.php?error=Login Failed! Incorrect email or password, Please try again!");
                        exit();
                        }
                }
            }ob_end_flush();
        ?>
        
        <header>
            <img class="logo" src="Pictures/icon.png" alt="Jacqueline Cheers System">
        </header><br><br>
        
        <div class="column">

                <h3 class="login">JCS Admin Login</h3>
                <hr id="line"/><br><br><br><br>
                <form name="login" method="post" action="<?php $_SERVER['PHP_SELF']; ?>">
                    <div class="row">
                        <div class="col1">
                            <img id= "icon" src="Pictures/email.png">
                            <label for="Email">Email</label>
                        </div>
                        <div class="col2">
                            <input type="email" id="email" name="email" placeholder="abc123@gmail.com"  ><br>
                            <?php if (isset($_GET['errorEmail'])) { ?>
                            <span class="error"> <?php echo $_GET['errorEmail']; ?></p>
                            <?php } ?> </span>
                        </div>
                    </div>
                    <br>

                    <div class="row">
                        <div class="col1">
                            <img id= "icon" src="Pictures/pw.png">
                            <label for="pw">Password</label>
                        </div>
                        <div class="col2">
                            <input type="password" id="pw" name="pw" placeholder="abc123" ><br>
                            <?php if (isset($_GET['errorPW'])) { ?>
                            <span class="error"> <?php echo $_GET['errorPW']; ?></p>
                            <?php } ?> </span>
                        </div>
                    </div><br><br><br>

                    <div class = "login_error">
                        <?php if (isset($_GET['error'])) { ?>
                        <span class="login_fail"> <?php echo $_GET['error']; ?></p>
                        <?php } ?> </span>
                    </div>

                    <div class="button">
                        <button type="submit">Login</button> 
                    </div>

                    <div class ="forgotpw">
                        <a href ="getemail.php">Forgotten password?</a>
                    </div>

                    

                    <div class="header_login">
                        
                    </div>

                </form>
        </div>
        <br><br><br><br><br><br><br><br><br>

    </body>

</html>