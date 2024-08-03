<?php session_start(); ob_start();?>
<!DOCTYPE html>
<html>
    <head>
        <title>My Profile - JCS</title>
        <meta charset="UTF-8">
        <link rel="icon" href="Pictures/smallicon.png">
        <link rel="shortcut icon" href="Pictures/smallicon.png">
        <script type="text/javascript" src="editprofile.js"></script>
        <link rel="stylesheet" href="CSS/myProfile.css">
        <link rel="stylesheet" href="CSS/footer.css">
    </head>

    <body>
        <header>
            <a href="index.php"> <img class="logo" src="Pictures/icon.png" alt="Jacqueline Cheers System"> </a>
            <span class = "menu">
                <a class = "cart_position" href="cart.php"> <img id="cart" src="Pictures/cart.png" alt="Cart"> </a>
            </span>
            <span class= "logout"> 
                <a id = "logout" href="logout.php">Logout</a>
                <span>&nbsp|&nbsp</span>
                <a href="myProfile.php"  >My Profile</a>
                <img class="image_login_register" src="Pictures/login_register_icon.png" alt="Login and register icon">
            </span>
        </header>

        <main>
            <?php 
                include 'connection.php';
                $id = $_SESSION['user_id'];
                //Select everything where id of people who logged in now
                $sql = "SELECT * FROM user WHERE id = '$id'"; 
                $isFound = mysqli_query($conn,$sql); //Check is it exists
                $row = mysqli_fetch_assoc($isFound); //fetch the result row

                //Show the pw in * form
                $password = str_repeat("•", strlen($row["pwd"])); 

                //Validate Edit_prodile
                $fname = $lname = ""; 
                $email = $mobile = $pw = $cpw = "";
                $postcode = $add = $city ="";

                $fnameE = $lnameE = "false"; 
                $emailE = $mobileE = $pwE = $cpwE = "false";
                $postcodeE = $addE = $cityE = "false";

                if ($_SERVER["REQUEST_METHOD"] == "POST")
                {
                    //Validate fname
                    if(empty($_POST["fname"]))
                    {
                        $fnameE = "true";
                    }
                    else
                    {
                        $fname = test($_POST["fname"]);
                    }

                    //Validate lname
                    if(empty($_POST["lname"]))
                    {
                        $lnameE = "true";
                    }
                    else
                    {
                        $lname = test($_POST["lname"]);
                    }

                    //Validate email
                    if(empty($_POST["email"]))
                    {
                        $emailE = "true";
                    }
                    else 
                    {
                        $email = test($_POST["email"]);
                        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) 
                        {
                            $emailE = "true";
                        }
                        
                    }

                    //Validate mobile
                    if(empty($_POST["mobile"]))
                    {
                        $mobileE = "true";
                    }
                    else
                    {
                        $mobile = test($_POST["mobile"]);
                        //Check is it number
                        if(!is_numeric($mobile) || strlen($mobile) > 10 || strlen($mobile) <9)
                        {
                            $mobileE = "true";
                        }
                    }

                    //Validate password
                    if(empty($_POST["pw"]))
                    {
                        $pwE = "true";
                    }
                    else
                    {
                        $pw = $_POST["pw"];
                        if(strlen($pw) <6 || !preg_match("#[0-9]+#",$pw) || !preg_match("#[A-Z]+#",$pw) || !preg_match("#[a-z]+#",$pw) || !preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/',$pw) || preg_match('/\s/',$pw))
                        {
                            $pwE = "true";
                        }
                    }

                    //Validate confirm password
                    if(empty($_POST["cpw"]))
                    {
                        $cpwE = "true";
                    }
                    else 
                    {
                        $cpw = $_POST["cpw"];
                        if($cpw !== $_POST["pw"])
                        {
                            $cpwE = "true";
                        }
                    }

                    //Validate address
                    if(empty($_POST["Address"]))
                    {
                        $addE = "true";
                    }
                    else
                    {
                        $add = test($_POST["Address"]);
                    }

                    //Validate postcode
                    if(empty($_POST["Postcode"]))
                    {
                        $postcodeE = "true";
                    }
                    else
                    {
                        $postcode = test($_POST["Postcode"]);
                        if(!is_numeric($postcode) || strlen($postcode) > 5  ||  strlen($postcode) <5)
                        {
                            $postcodeE = "true";
                        }
                    }

                    //Validate city
                    if(empty($_POST["city"]))
                    {
                        $cityE = "true";
                    }
                    else
                    {
                        $city = test($_POST["city"]);
                    }

                    $state = test($_POST["state"]);
                    $region = test($_POST["region"]);
                    $gender = test($_POST["gender"]);
                }

                function test($data)
                {
                    $data = trim($data);
                    $data = stripslashes($data);
                    $data = htmlspecialchars($data);
                    return $data;
                }
             
                //Update profile if Validation is successful
                if($fnameE === "false" && $lnameE === "false" && $emailE === "false" && $mobileE === "false" && $pwE === "false" && $cpwE === "false" && $postcodeE === "false" && $addE === "false" && $cityE === "false" 
                &&  $fname != "" && $lname != "" && $email != "" && $mobile != "" && $pw != "" && $cpw != "" && $add != "" && $postcode != "" && $city != "")
                {
                    //Update the user info in the database table
                    $sql= "UPDATE user
                    SET firstname = '$fname', lastname= '$lname', email= '$email', region= '$region', phone= '$mobile', pwd= '$pw', gender= '$gender', _state= '$state',postcode= '$postcode', _address= '$add', city= '$city'
                    WHERE id = '$id'";
                    $result = mysqli_query($conn,$sql);
                    if ($result === TRUE) {
                        //echo "Profile updated!";
                    } else {
                        echo "Error: ". $conn->error;
                    }

                    //Refresh the page after profile is updated
                    header("Refresh:1");
                    ob_end_flush();
                }
            ?>

            <div class="display_title_editbutton"> 
                <span class= "title" >- My Profile -</span>
                <noscript>Please ENABLE JavaScript to Edit Profile!</noscript>
                <span class="editbutton">
                    <a onclick="display()" ><img src="Pictures/editprofile.png"></a>
                </span>
            </div>

            
            <div class="profilepic"> 
            <?php 
                $sql = "SELECT gender FROM user WHERE id = '$id'"; //Select the user id
                $isFound = mysqli_query($conn,$sql); //Check is it exists
                
                //Found the user
                if(mysqli_num_rows($isFound) == 1) 
                {
                    //fetch the id
                    $result = mysqli_fetch_assoc($isFound);
                    $gender = $result["gender"];

                    if( $gender === "Female")
                    {
                        echo '<img src="Pictures/default_pp_female.png">';
                        echo '<img id = "genderbg" src="Pictures/female_bg.png">';
                    }
                    else
                    {
                        echo '<img src="Pictures/default_pp_male.png">';
                        echo '<img id = "genderbg" src="Pictures/male_bg.png">';
                    }
                }
            ?>
            </div><br><br>

            <hr id="designline">

            <br>

            <div class = "column">
                <div class="column1">
                    <ul>
                        <li>Email</li>
                        <li>Password</li>
                        <li id="edit_confirm_pw">Confirm Password</li>
                        <li>First Name</li>
                        <li>Last Name</li>
                        <li>Phone</li>
                        <li>Gender</li>
                        <li>Address</li>
                        <li>Postcode</li>
                        <li>City</li>
                        <li>State</li>
                    </ul>
                </div> <!-- End Column1-->

                <div class="column2">
                    <form name="edit" method="post" onsubmit="return validateEditForm()" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                        <ul>
                        <!--Display the li before editting-->
                        <li id="display0"><?php echo  ": " .$row["email"].""; ?></li>
                        <!--Display the input when users try to edit-->
                        <li><input type="email" id="email" name="email"   value="<?php echo $row["email"]; ?>"></li>

                        <li id="display1"><?php    echo ": " .$password.""; ?></li>
                        <li><input type="password" id="pw" name="pw"   value="<?php echo $row["pwd"]; ?>"></li>

                        <li id="display2_cpw"><?php    echo ": " .$password.""; ?></li>
                        <li><input type="password" id="cpw" name="cpw"  value="<?php echo $row["pwd"]; ?>" ></li>

                        <li id="display3"><?php    echo ": " .$row["firstname"].""; ?></li>
                        <li><input type="text" id="fname" name="fname"  value="<?php echo $row["firstname"];?>"></li>

                        <li id="display4"><?php    echo ": " .$row["lastname"].""; ?></li>
                        <li><input type="text" id="lname" name="lname"   value="<?php echo $row["lastname"]; ?>"></li>

                        <li id="display5"><?php    echo ": +" .$row["region"]."".$row["phone"].""; ?></li>
                        <li>
                        <span id="PHONE">
                            <select id="region" name="region">
                                        <option value="+60">+60</option>
                                        <option value="+1">+1</option>
                                        <option value="+44">+44</option>
                            </select>
                            <input type="phone" id="mobile" name="mobile"   value="<?php echo $row["phone"]; ?>">
                        </span>
                        </li>

                        <li id="display6"><?php    echo ": " .$row["gender"].""; ?></li>
                        <li>
                        <span id="GENDER">
                            <input type="radio" id="male" name="gender" value="Male"
                            <?php if ($row["gender"]=="Male") echo "checked";?> >
                            <label id="display_male" for="male">Male</label>
                            <input type="radio" id="female" name="gender" value="Female"
                            <?php if ($row["gender"]=="Female") echo "checked";?> >
                            <label id="display_female" for="female">Female</label>
                        </span>
                        </li>

                        <li id="display7"><?php    echo ": " .$row["_address"].""; ?></li>
                        <li><input type="text" id="address" name="Address" value="<?php echo $row["_address"];?>" ></li>

                        <li id="display8"><?php    echo ": " .$row["postcode"].""; ?></li>
                        <li><input type="text" id="postcode" name="Postcode" value="<?php echo $row["postcode"];?>" ></li>
                    

                        <li id="display9"><?php    echo ": " .$row["city"].""; ?></li>
                        <li><input type="text" id="city" name="city"  value="<?php echo $row["city"];?>" ></li>

                        <li id="display10"><?php    echo ": " .$row["_state"].""; ?> </li>
                        <li>
                            <select id="state" name="state">
                                <option value="Kelantan" <?php if ($row["_state"] == 'Kelantan') echo ' selected="selected"'; ?>>Kelantan</option>
                                <option value="Melaka" <?php if ($row["_state"] == 'Melaka') echo ' selected="selected"'; ?>>Melaka</option>
                                <option value="Negeri Sembilan" <?php if ($row["_state"] == 'Sembilan') echo ' selected="selected"'; ?>>Negeri Sembilan</option>
                                <option value="Pahang" <?php if ($row["_state"] == 'Pahang') echo ' selected="selected"'; ?>>Pahang</option>
                                <option value="Penang" <?php if ($row["_state"] == 'Penang') echo ' selected="selected"'; ?>>Penang</option>
                                <option value="Perak" <?php if ($row["_state"] == 'Perak') echo ' selected="selected"'; ?>>Perak</option>
                                <option value="Perlis" <?php if ($row["_state"] == 'Perlis') echo ' selected="selected"'; ?>>Perlis</option>
                                <option value="Sabah" <?php if ($row["_state"] == 'Sabah') echo ' selected="selected"'; ?>>Sabah</option>
                                <option value="Sarawak" <?php if ($row["_state"] == 'Sarawak') echo ' selected="selected"'; ?>>Sarawak</option>
                                <option value="Selangor" <?php if ($row["_state"] == 'Selangor') echo ' selected="selected"'; ?>>Selangor</option>
                                <option value="Terengganu" <?php if ($row["_state"] == 'Terengganu') echo ' selected="selected"'; ?>>Terengganu</option>
                                <option value="Kedah" <?php if ($row["_state"] == 'Kedah') echo ' selected="selected"'; ?>>Kedah</option>
                                <option value="Johor" <?php if ($row["_state"] == 'Johor') echo ' selected="selected"'; ?>>Johor</option>
                            </select>
                        </li>

                        <div id="button">
                            <input type="submit" name = "submit" value="Update" >
                            <input class="cancel" onclick="cancel_edit()" name = "cancel" value="Cancel" >
                        </div>
                    </form>
                </div> <!-- End Column2-->

            </div> <!-- End Column-->
        </main>
        <br><br>

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