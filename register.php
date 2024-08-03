<!DOCTYPE html>
<html>
    <head>
        <title>Registration - JCS</title>
        <meta charset="UTF-8">
        <link rel="icon" href="Pictures/smallicon.png">
        <link rel="shortcut icon" href="Pictures/smallicon.png">
        <link rel="stylesheet" href="CSS/register.css">
        <link rel="stylesheet" href="CSS/footer.css">
    </head>

    <body>

        <?php 
            include 'connection.php';
            //Validation
            $welcome = "";
            $fname = $lname = ""; 
            $fnameE = $lnameE = "";

            //Other declaration
            $email = $mobile = $pw = $cpw = $gender = $terms = "";
            $emailE = $mobileE = $cpwE = $genderE = $termsE ="";
            $postcode = $add = $city ="";
            $postcodeE = $addE = $cityE ="";
            $cpw_match = $pw_strong = "";
            $pwE="";
            $pwHint= "*Password must be At least six digits, containing at least one number, one uppercase and one lowercase letter, one special character.";
            if ($_SERVER["REQUEST_METHOD"] == "POST")
            {
                //Validate fname
                if(empty($_POST["fname"]))
                {
                    $fnameE = "*First Name is required!";
                }
                else
                {
                    $fname = test($_POST["fname"]);
                }

                //Validate lname
                if(empty($_POST["lname"]))
                {
                    $lnameE = "*Last Name is required!";
                }
                else
                {
                    $lname = test($_POST["lname"]);
                }
                
                //Validate email
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

                //Validate mobile
                if(empty($_POST["mobile"]))
                {
                    $mobileE = "*Phone number is required!";
                }
                else
                {
                    $mobile = test($_POST["mobile"]);
                    //Check is it number
                    if(!is_numeric($mobile))
                    {
                        $mobileE = "*Invalid! Please use only numbers 0-9!";
                    }
                    else if(strlen($mobile) > 10 || strlen($mobile) <9) //Check length
                    {
                        $mobileE = "*Invalid phone number length!";
                    }
                }

                //Validate password
                if(empty($_POST["pw"]))
                {
                    $pwE = "*Password is required!";
                    $pwHint = "";
                }
                else
                {
                    $pw = test($_POST["pw"]);
                    $pwHint = "";
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
                        $pw_strong = "Strong Password!";
                    }
                }

                //Validate confirm password
                if(empty($_POST["cpw"]) && !empty($_POST["pw"]))
                {
                    $cpwE = "*Please confirm your password!";
                }
                else 
                {
                    $cpw = test($_POST["cpw"]);
                    if($cpw !== $_POST["pw"])
                    {
                        $cpwE = "*The password confirmation does not match!";
                    }
                    else if($cpw === $_POST["pw"] && !empty($_POST["pw"]))
                    {
                        $cpw_match = "Password Match!";
                    }
                }

                //Validate gender
                if(empty($_POST["gender"]))
                {
                    $genderE = "*Gender is required!";
                }
                else
                {
                    $gender = test($_POST["gender"]);
                }

                //Validate terms
                if(empty($_POST["terms"]))
                {
                    $termsE = "*Please accept the terms and conditions!";
                }
                else
                {
                    $terms = test($_POST["terms"]);
                }

                //Validate address
                if(empty($_POST["Address"]))
                {
                    $addE = "*Address is required!";
                }
                else
                {
                    $add = test($_POST["Address"]);
                }

                //Validate postcode
                if(empty($_POST["Postcode"]))
                {
                    $postcodeE = "*Postcode is required!";
                }
                else
                {
                    $postcode = test($_POST["Postcode"]);
                    if(!is_numeric($postcode))
                    {
                        $postcodeE = "*Invalid PostCode! Only numbers are Allowed!";
                    }
                    else if (strlen($postcode) > 5 || strlen($postcode) <5)
                    {
                        $postcodeE = "*Invalid PostCode length!";
                    }
                }

                //Validate city
                if(empty($_POST["city"]))
                {
                    $cityE = "*City is required!";
                }
                else
                {
                    $city = test($_POST["city"]);
                }

                $state = test($_POST["state"]);
                $region = test($_POST["region"]);

                
            } //End Validation

            function test($data)
            {
                $data = trim($data);
                $data = stripslashes($data);
                $data = htmlspecialchars($data);
                return $data;
            }

            //Actual Registration Logic
            if($emailE == "" && $mobileE == "" && $pwE == "" && $cpwE == "" && $genderE == "" && $termsE =="" && $fnameE == "" && $lnameE == "" && $addE == "" && $postcodeE == "" && $cityE == ""
            &&  $fname != "" && $lname != "" && $email != "" && $mobile != "" && $pw != "" && $cpw != "" && $gender != "" && $terms != "" && $add != "" && $postcode != "" && $city != "")
            {
                $welcome = "Thank you for your Registration, " .$fname. "!<br> Press here to Login!";

                //Insert registered user's data into the table
                $sql = "INSERT INTO user (firstname, lastname, email, region, phone, pwd, gender, _state, postcode, _address, city,_login)
                VALUES ('$fname', '$lname', '$email','$region','$mobile', '$pw','$gender', '$state', '$postcode', '$add', '$city', 0)";
                if ($conn->query($sql) === TRUE) {
                    //echo "New record created successfully";
                  } else {
                    echo "Error: " . $sql . "<br>" . $conn->error;
                  }

                $sql = "SELECT id FROM user WHERE email='$email'"; //Select the user id
                $isFound = mysqli_query($conn,$sql); 
                $result = mysqli_fetch_assoc($isFound);
                $id = $result["id"];

                //Insert cart data
                $sql = "INSERT INTO cart (userid, grand_total)
                VALUES ('$id', '0.00')";
                if ($conn->query($sql) === TRUE) {
                    //echo "New CART created successfully";
                  } else {
                    echo "Error: " . $sql . "<br>" . $conn->error;
                  }
            }

            //Close Connection
            mysqli_close($conn);

        ?>

        <header>
            <a href="index.php"><img class="logo" src="Pictures/icon.png" alt="Jacqueline Cheers System" ></a>
        </header>
        
        <div class="column"> 
            <div class="welcome"> 
                <br>
                <a href="login.php"><?php echo $welcome; ?></a>
                <br>
            </div>
               
            <h3 class="signup">Create your JCS Account</h3>
            <hr id="line"/><br><br> 

                <form name="reg" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                    <div class="row">
                        <div class="col1">
                            <img id=icon src="Pictures/name.png">
                            <label for="fname">First Name</label>
                        </div>
                        <div class="col2">
                            <input type="text" id="fname" name="fname" placeholder="Your first name.." value="<?php echo $fname;?>">
                            <span class="error"> <br> <?php echo $fnameE; ?> </span>
                        </div>
                    </div>
                    <br>

                    <div class="row">
                        <div class="col1">
                            <img id=icon src="Pictures/name.png">
                            <label for="lname">Last Name</label>
                        </div>
                        <div class="col2">
                            <input type="text" id="lname" name="lname" placeholder="Your last name.." value="<?php echo $lname;?>">
                            <span class="error"> <br> <?php echo $lnameE; ?> </span>
                        </div>
                    </div>
                    <br>

                    <div class="row">
                        <div class="col1">
                            <img id=icon src="Pictures/email.png">
                            <label for="Email">Email</label>
                        </div>
                        <div class="col2">
                            <input type="email" id="email" name="email" placeholder="Email.." value="<?php echo $email;?>" >
                            <span class="error"> <br> <?php echo $emailE; ?> </span>
                        </div>
                    </div>
                    <br>

                    <div class="row">
                        <div class="col1">
                            <img id=icon src="Pictures/phone.png">
                            <label for="mobile">Mobile</label>
                        </div>
                        <div class="col2">
                            <select id="region" name="region">
                                <option value="+60">+60</option>
                                <option value="+1">+1</option>
                                <option value="+44">+44</option>
                            </select>
                            <input type="text" id="mobile" name="mobile" placeholder="Phone number.." value="<?php echo $mobile;?>" >
                            <span class="error"> <br> <?php echo $mobileE; ?> </span>
                        </div>
                    </div>
                    <br>

                    <div class="row">
                        <div class="col1">
                            <img id=icon src="Pictures/pw.png">
                            <label for="pw">Password</label>
                        </div>
                        <div class="col2">
                            <input type="password" id="pw" name="pw" placeholder="Password.." value="<?php echo $pw;?>" >
                            <span class="error"> <br> <?php echo $pwE; ?> </span>
                            <span class="correct"> <?php echo $pw_strong; ?> </span>
                            <span class="hint"> <br> <?php echo $pwHint; ?> </span>
                        </div>
                    </div>
                    <br>

                    <div class="row">
                        <div class="col1">
                            <img id=icon src="Pictures/pw.png">
                            <label for="cpw">Confirm Password</label>
                        </div>
                        <div class="col2">
                            <input type="password" id="cpw" name="cpw" placeholder="Confirm password.." value="<?php echo $cpw;?>" >
                            <span class="error"> <br> <?php echo $cpwE; ?> </span>
                            <span class="correct"> <?php echo $cpw_match; ?> </span>
                        </div>
                    </div>
                    <br>

                    <div class="row">
                        <div class="col1">
                            <img id=icon src="Pictures/gender.png">
                            <label for="gender">Gender</label>
                        </div>
                        <div class="col2">
                            <div class="gender">
                                <input type="radio" id="male" name="gender" value="Male"
                                <?php if (isset($gender) && $gender=="Male") echo "checked";?> >
                                <label for="male">Male</label>
                                <input type="radio" id="female" name="gender" value="Female"
                                <?php if (isset($gender) && $gender=="Female") echo "checked";?> >
                                <label for="female">Female</label>
                                <span class="errorGender"><?php echo $genderE; ?> </span>
                            </div>
                        </div>
                    </div>
                    <br><br>

                    <div class="row">
                        <div class="col1">
                            <img id=icon src="Pictures/location.png">
                            <label for="address">Address</label>
                        </div>
                        <div class="col2">
                            <input type="text" id="address" name="Address" placeholder="Address.." value="<?php echo $add;?>" >
                            <span class="error"> <br> <?php echo $addE; ?> </span>
                        </div>
                    </div>
                    <br>

                    <div class="row">
                        <div class="col1">
                            <img id=icon src="Pictures/postcode.png">
                            <label for="postcode">Postcode</label>
                        </div>
                        <div class="col2">
                            <input type="text" id="postcode" name="Postcode" placeholder="Postcode.." value="<?php echo $postcode;?>" >
                            <span class="error"> <br> <?php echo $postcodeE; ?> </span>
                        </div>
                    </div>
                    <br>

                    <div class="row">
                        <div class="col1">
                            <img id=icon src="Pictures/location.png">
                            <label for="city">City</label>
                        </div>
                        <div class="col2">
                            <input type="text" id="city" name="city" placeholder="City.." value="<?php echo $city;?>" >
                            <span class="error"> <br> <?php echo $cityE; ?> </span>
                        </div>
                    </div>
                    <br>

                    <div class="row">
                        <div class="col1">
                            <img id=icon src="Pictures/location.png">
                            <label for="state">State</label>
                        </div>
                        <div class="col2">
                            <select id="state" name="state">
                                <option value="Johor">Johor</option>
                                <option value="Kedah">Kedah</option>
                                <option value="Kelantan">Kelantan</option>
                                <option value="Melaka">Melaka</option>
                                <option value="Negeri Sembilan">Negeri Sembilan</option>
                                <option value="Pahang">Pahang</option>
                                <option value="Penang">Penang</option>
                                <option value="Perak">Perak</option>
                                <option value="Perlis">Perlis</option>
                                <option value="Sabah">Sabah</option>
                                <option value="Sarawak">Sarawak</option>
                                <option value="Selangor">Selangor</option>
                                <option value="Terengganu">Terengganu</option>
                            </select>
                        </div>
                    </div>
                    <br>
                    
                    <br>

                    <div class="Terms_Con">
                        <input type="checkbox" id="terms" name="terms" value="Accepted"
                        <?php if (isset($terms) && $terms == "Accepted") echo "checked";?> >
                        <label for="terms">I accept the above Terms and Conditions</label>
                        <div class="errorTerms"> <br> <?php echo $termsE; ?> </div>
                    </div> 

                    <div class="button">
                        <input type="submit" name = "submit" value="Register" >
                    </div>

                    <div class="header_signup">
                            <a href="login.php" >Already has an Account? Press here to Login!</a>
                    </div>
                    
                </form>
                
        </div>
        
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