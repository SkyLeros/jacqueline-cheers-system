<?php session_start(); ob_start();?>
<!DOCTYPE html>

<html lang="en">
    <head>
        <title>Appointment - JCS</title>
        <link rel="icon" href="Pictures/smallicon.png">
        <link rel="shortcut icon" href="Pictures/smallicon.png">
        <link rel="stylesheet" href="CSS/appointment.css">
        <link rel="stylesheet" href="CSS/footer.css">

    </head>

    <body>
        <?php
            //For buttons in HEADER
            $signup = $login = $logout = $myprofile  =  "";
            //Check if session login is defined
            if (isset($_SESSION['login'])) 
            {
                //If it is defined, then we check is it Logged in 
                if($_SESSION['login'] === "Logged In")
                {
                    $logout = "Logout";
                    $myprofile = "My Profile";
                }
            }
            else //session login not set yet
            {
                $signup = "Sign Up";
                $login = "Login";
            }

            // Create onnection
            include 'connection.php';
            if(isset($_SESSION['user_id']))
                $userid = $_SESSION['user_id'];

            if(isset($_GET['action']) && $_GET['action'] == "AppointmentValidate")
            {
                $service_id = $_POST["service_id"];
                $service_name = $_POST["service_name"];
                $appointmentdate= $_POST['date'];

                //Validate date, is it thursday? (off day)
                $timestamp = strtotime($appointmentdate);
                $day = date('D', $timestamp);

                $appointmenttime= $_POST['time'];
                $addtionalinformation=$_POST['addtionalinformation'];
                
    
                if (empty($appointmentdate)) 
                {
                    $DateE= "*Appointment Date is required!"; 
                }
                else if($day === 'Thu')
                {
                    $DateE= "Thrusday is our Regular OFF DAY! Please select other date!";
                }
                else 
                {
                    $DateE= "";
                }

                if (empty($appointmenttime)) $TimeE= "*Appointment TIme is required!";  else $TimeE= "";
                if (empty($addtionalinformation)) $AddInfoE = "*Additional Information is required!";  else  $AddInfoE= "";

                If(empty($DateE) && empty($TimeE) && empty($AddInfoE))
                {
                    date_default_timezone_set('Asia/Kuala_Lumpur');
                    $CreateAt = date('Y-m-d H:i:s',time());

                    $sql = " INSERT INTO appointment (userid,serviceid,addtionalinformation,appointmentdate,appointmenttime,createdAt,status) VALUES ('$userid','$service_id','$addtionalinformation','$appointmentdate','$appointmenttime','$CreateAt','Upcoming')";
                    $result = mysqli_query($conn, $sql);

                    if ($result === TRUE) 
                    {
                        echo "New appointment created successfully";
                    } else {
                        echo "Error: " . $sql . "<br>" . $conn->error;
                    }

                    $sql = " SELECT * FROM user WHERE id='$userid'";
                    $result_get_userInfo = mysqli_query($conn, $sql);
                    $row = mysqli_fetch_assoc($result_get_userInfo);

                    $firstname = $row['firstname'];
                    $fullName = $row['firstname'].' '.$row['lastname'];
                    $Email = $row['email'];

                    //Send an email to the user telling them that the appointment successful
                    $to = $Email; //email destination
                    $subject = "Jacqueline Cheers System";

                    $message = "
                        <img src=\"https://i.imgur.com/qS64mJ9.png\">
                        <h1 style=\"color:green;\" >Booking success!</h1>
                        <h3>Dear $firstname,</h3>
                        <h3>Your appointment has been successfully created!</h3>
                        <br>
                        <hr >
                        <h2>Appointment Details</h2>
                        <hr >
                        <h4>Date :  <strong style=\"color:#FF1493;\"> $appointmentdate </strong> </h4><hr >
                        <h4>Time :  <strong style=\"color:#FF1493;\"> $appointmenttime </strong> </h4><hr >
                        <h4>Service selected :  <strong style=\"color:#FF1493;\"> $service_name </strong> </h4><hr >
                        <h4>Additional Information :  <strong style=\"color:#FF1493;\"> $addtionalinformation </strong> </h4><hr >
                        
                        <br>
                        <h4 style=\"color:#59585f;\">~~ Created At $CreateAt ~~</h4>";

                    $headers = "MIME-Version: 1.0" . "\r\n";
                    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

                    $headers .= 'From: JCS <jjcs02578@gmail.com>' . "\r\n";
                    $headers .= 'Cc: danny.yii@hotmail.com' . "\r\n";

                    mail($to,$subject,$message,$headers);
                    

                    //Send an email to the Owner telling them that there has new upcoming appointment 
                    $to = "jjcs02578@gmail.com"; //email destination
                    $subject = "Jacqueline Cheers System";

                    $message = "
                        <img src=\"https://i.imgur.com/qS64mJ9.png\">
                        <h1 style=\"color:green;\" >New Upcoming Appointment!</h1>
                        <h3>Dear Jacqueline,</h3>
                        <h3>There has new upcoming appointment!</h3>
                        <br>
                        <hr >
                        <h2>Appointment Details</h2>
                        <hr >
                        <h4>Customer Name :  <strong style=\"color:#FF1493;\"> $fullName </strong> </h4><hr >
                        <h4>Date :  <strong style=\"color:#FF1493;\"> $appointmentdate </strong> </h4><hr >
                        <h4>Time :  <strong style=\"color:#FF1493;\"> $appointmenttime </strong> </h4><hr >
                        <h4>Service selected :  <strong style=\"color:#FF1493;\"> $service_name </strong> </h4><hr >
                        <h4>Additional Information :  <strong style=\"color:#FF1493;\"> $addtionalinformation </strong> </h4><hr >
                        
                        <br>
                        <h4 style=\"color:#59585f;\">~~ Created At $CreateAt ~~</h4>";

                    $headers = "MIME-Version: 1.0" . "\r\n";
                    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

                    $headers .= 'From: JCS <dannyyiimingxi@gmail.com>' . "\r\n";
                    $headers .= 'Cc: danny.yii@hotmail.com' . "\r\n";

                    mail($to,$subject,$message,$headers);

                    header("Location: appointment.php");
                }
                else
                    header("Location: appointment.php?action=makeAppointment&service_id=$service_id&service_name=$service_name&DateE=$DateE&TimeE=$TimeE&AddInfoE=$AddInfoE");
            }
            


        ?>
        <header class="HeaderHeader">

            <div class="login_register">
                <ul id = abc>
                <li id= "topnav"><a href="aboutus.php">About Us</a></li>
                <li id= "topnav"><a href="orderhistory.php">Order History</a></li>
                <li id= "active"><a href="appointment.php">Appointment</a></li>  
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
        
        </header>
        
        <?php if(isset($_GET['action']) && $_GET['action'] == "makeAppointment" && isset($_SESSION['login'])) {
                    if ($_SERVER["REQUEST_METHOD"] == "POST"){
                    $service_id = $_POST["service_id"];
                    $service_name = $_POST["service_name"];} 
                    
                    if(isset($_GET['service_id']) && isset($_GET['service_name'])){
                        $service_id = $_GET["service_id"];
                        $service_name = $_GET["service_name"];} 
                    ?>

        <div class="column"> 
               
             <h3 class="make_appointment">Make Appointment</h3>
             <hr id="line"/><br><br> 
 
                 <form name="appointment" method="post" action="appointment.php?action=AppointmentValidate">
                     <div class="row">
                         <div class="col1">
                             <img id=icon src="Pictures/service.png">
                             <label for="name">Service Selected</label>
                         </div>
                         <div class="col2">
                             <input type="text" id="name" name="service_name" value="<?php echo $service_name;?>" readonly>
                             <input type="hidden" id="service_id" name="service_id" value="<?php echo $service_id;?>" >
                         </div>
                     </div>
                     <br>

                     <div class="row">
                         <div class="col1">
                             <img id=icon src="Pictures/appointment.png">
                             <label for="date" >Date</label>
                         </div>
                         <div class="col2">
                             <input type="date" name="date" min="<?= date('Y-m-d'); ?>">
                             <span class="error"> <br> <?php if (isset($_GET['DateE']))echo $_GET['DateE']; ?> </span>
                         </div>
                     </div>
                     <br>

                     <div class="row">
                         <div class="col1">
                             <img id=icon src="Pictures/clock.png">
                             <label for="name">Time</label>
                         </div>
                         <div class="col2">
                            <select id="time" name="time">
                                <option value="09:00:00">9:00 am</option>
                                <option value="10:00:00">10:00 am</option>
                                <option value="11:00:00">11:00 am</option>
                                <option value="12:00:00">12:00 pm</option>
                                <option value="13:00:00">1:00 pm</option>
                                <option value="14:00:00">2:00 pm</option>
                                <option value="15:00:00">3:00 pm</option>
                                <option value="16:00:00">4:00 pm</option>
                                <option value="17:00:00">5:00 pm</option>
                            </select>
                            <span class="error"> <br> <?php if (isset($_GET['TimeE'])) echo $_GET['TimeE']; ?> </span>
                         </div>
                     </div>
                     <br>

                     <div class="row" >
                         <div class="col1">
                             <img id=icon src="Pictures/information.png">
                             <label for="name">Additional Information</label>
                         </div>
                         <div class="col2">
                            <input type="text" name="addtionalinformation" placeholder="Short Hair/ Medium Length Hair/ Long Hair...">
                            <span class="error"> <br> <?php if (isset($_GET['AddInfoE'])) echo $_GET['AddInfoE']; ?> </span>
                         </div> 
                     </div>
                     <br>
                    
 
                     <div class="button">
                         <input type="submit" name = "submit" value="Book Now" >
                         <input type="reset" name = "reset" value="Clear" > 
                     </div>
                     <br><br>
                     
                 </form>
                 
         </div>
        <?php }else if(isset($_GET['action']) && $_GET['action'] == "makeAppointment" && !isset($_SESSION['login']))
        {
              //DISPLAY THE NOTIFICATION
              echo '<div class="coll">'; 
              echo    '<div class="coll1"></div>'; 
              echo    '<div class="coll2">'; 
              echo        '<p class ="notify">Please <span id ="bold">Log In</span> Before You Can Make Appointment(s)!</p><br><br>';
              echo        '<div class="placement"><a href="login.php" class="buttons">Login</a></div>';
              echo    '</div>';
              echo    '<div class="coll3"></div>';
              echo '</div>';
        }
        if(isset($_SESSION['login']) && !isset($_GET['action']))
        {?>
            <div class="col-div-10">
                <div class="box-10">
                        <div class="content-box">
                    <p>All Appointment Details
                        <select style="padding:5px;margin-bottom: 25px;border: 2px solid #ff531a;float:right;" name="sortstatus" id="sortstatus" onchange="sort(this)";>
                            <option value="" disabled="" selected="">Select Filter</option>
                            <option value="Upcoming">Upcoming Appointment</option>
                            <option value="Completed">Completed Appointment</option>
                            <option value="Cancelled">Cancelled Appointment</option>
                            <option value="All">All Appointment</option>
                        </select>
                    </p>
                    <script type="text/javascript">
                    function sort(answer){
                        if(answer.value == "All")
                        window.location="appointment.php";
                        else
                        window.location="appointment.php?request="+answer.value; 
                    }

                    </script>
                        <br/>
                    <?php
                        if(isset($_GET['request']))
                        { 
                        $value = $_GET['request'];
                        $query = "SELECT appointment.id,appointment.appointmentdate,appointment.appointmenttime,appointment.addtionalinformation,appointment.status,service.servicename FROM (appointment  INNER JOIN service ON service.id = appointment.serviceid)WHERE userid = $userid AND status ='$value' ORDER BY appointment.appointmentdate DESC;";
                        }
                        else
                        $query = "SELECT appointment.id,appointment.appointmentdate,appointment.appointmenttime,appointment.addtionalinformation,appointment.status,service.servicename FROM (appointment  INNER JOIN service ON service.id = appointment.serviceid)WHERE userid = $userid ORDER BY appointment.appointmentdate DESC;";
                        $result2 = mysqli_query($conn,$query);
                        if(mysqli_num_rows($result2)>0){
                    ?>

                    <table>
                        <thead>
                        <tr>
                        <th>Appointment Date</th>
                        <th>Appointment Time </th>
                        <th>Service </th>
                        <th>Additional Information</th>
                        <th>Status</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                            while($row = mysqli_fetch_assoc($result2))
                            {
                        ?>
                        <tr>
                            <td><?php echo $row['appointmentdate']; ?></td>
                            <td><?php echo $row['appointmenttime']; ?></td>
                            <td><?php echo $row['servicename']; ?></td>
                            <td><?php echo $row['addtionalinformation']; ?></td>
                            <td><?php echo $row['status']; ?></td>
                            <td>
                                <?php if($row['status']=="Upcoming"){ ?>
                                    <form action="remove_item.php" method="POST">
                                        <input type="hidden" name="erase" value="<?php echo $row['id']; ?>">
                                        <button type="submit" name="delete" class="delete" onclick="return confirm('Are You Sure You Want To Cancel The Appoinment?')"> <img id="buttons" src = "Pictures/remove_item.png"> </button>
                                    </form>
                                <?php } else if($row['status']=="Completed") 
                                        echo '<img id="buttons" src = "Pictures/tick-icon.png">';
                                    else
                                        echo '<img id="buttons" src = "Pictures/cross-icon.png">';
                                ?> 
                            </td>
                        </tr>
                        
                        <?php }
                        }else //mysqli_num_rows($isFound) = 0
                        {
                            echo '<p class="no_trans">'."No appoinment being Made Yet!".'</p>';
                        }?>
                        </tbody>
                    </table>
                    </div>
                </div>
            </div> 
        <?php }
        else if(!isset($_SESSION['login']) && !isset($_GET['action']))
        {
              //DISPLAY THE NOTIFICATION
              echo '<div class="coll">'; 
              echo    '<div class="coll1"></div>'; 
              echo    '<div class="coll2">'; 
              echo        '<p class ="notify">Please <span id ="bold">Log In</span> Before You Can Start Viewing Appointment(s)!</p><br><br>';
              echo        '<div class="placement"><a href="login.php" class="buttons">Login</a></div>';
              echo    '</div>';
              echo    '<div class="coll3"></div>';
              echo '</div>';
              
        }?>
        

        
        

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

<?php ob_end_flush();?>