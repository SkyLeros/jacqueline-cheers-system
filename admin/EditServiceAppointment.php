<?php include "../connection.php";ob_start();session_start();?>
<?php 

include "connection.php";

if(!isset($_SESSION['adminEmail']))
header("Location: adminLogin.php");

$id = $_GET['id'];
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    if(isset($_POST['updateService'])){

        $id = $_POST['id'];
        $service = $_POST['service'];
        $date = $_POST['date'];
        $time = $_POST['time'];
        $status = $_POST['status'];

        $sql = " SELECT * FROM (appointment INNER JOIN user ON appointment.userid=user.id) INNER JOIN service ON appointment.serviceid = service.id  WHERE appointment.id='$id'";
        $result_get_appointmentInfo = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result_get_appointmentInfo);
        
        // if admin cancel an upcoming appointment then send mail to user
        if($row['status'] == "Upcoming" && $status == "Cancelled")
        {   
            $Email = $row['email'];
            $firstname = $row['firstname'];
            $fullName = $row['firstname'].' '.$row['lastname'];
            $appointmentdate = $row['appointmentdate'];
            $appointmenttime = $row['appointmenttime'];
            $service_name = $row['servicename'];
            $addtionalinformation = $row['addtionalinformation'];
            $CreateAt = $row['createdAt'];

            $to = $Email; //email destination
            $subject = "Jacqueline Cheers System";

            $message = "
                <img src=\"https://i.imgur.com/qS64mJ9.png\">
                <h1 style=\"color:red;\" >Appointment Cancellation </h1>
                <h3>Dear $firstname,</h3>
                <h3>The appointment created at $CreateAt has been canceled by Jacqueline Hair Saloon!</h3>
                <br>
                <hr >
                <h2>Appointment Details</h2>
                <hr >
                <h4>Customer Name :  <strong style=\"color:#FF1493;\"> $fullName </strong> </h4><hr >
                <h4>Date :  <strong style=\"color:#FF1493;\"> $appointmentdate </strong> </h4><hr >
                <h4>Time :  <strong style=\"color:#FF1493;\"> $appointmenttime </strong> </h4><hr >
                <h4>Service selected :  <strong style=\"color:#FF1493;\"> $service_name </strong> </h4><hr >
                <h4>Additional Information :  <strong style=\"color:#FF1493;\"> $addtionalinformation </strong> </h4><hr >";


            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

            $headers .= 'From: JCS <noreply@jcs.com>' . "\r\n";
            $headers .= 'Cc: danny.yii@hotmail.com' . "\r\n";

            mail($to,$subject,$message,$headers);
        }

        $query = "UPDATE appointment SET serviceid='$service', appointmentdate='$date', appointmenttime='$time', status='$status' WHERE id='$id'";
        $result = mysqli_query($conn,$query);

        if($result){
            header('Location: manageServiceAppointment.php');
        }
        else
        {
            echo "Failed: " . mysqli_error($conn);
        }
    }else {
        header("location: manageServiceAppointment.php");
    }
}ob_end_flush();

?>

<html>
<head>
        <title>JCS</title>
        <link rel="icon" href="Pictures/smallicon.png">
        <link rel="shortcut icon" href="Pictures/smallicon.png">
        <link rel="stylesheet" href="CSS/adminStyle.css">
        <link rel="stylesheet" href="../CSS/footer.css">
        <script src="slides.js"></script>
    </head>

<body>
 <header class="header-border">
    <div class="header-content">
      <a href="admindashboard.php"><img src="Pictures/icon.png" class="logo" ></a>
    </div>
  </header>


  <div class="sidenav">
		<a href="admindashboard.php"><span><img src="Pictures/sidebar.png" alt="sidebar"> Dashboard</span></a>
  		<a class="active" href="manageServiceAppointment.php"><span><img src="Pictures/serviceApp.png" alt="account"> Services Appointments</span></a>
  		<a href="manageProductOrder.php"><span><img src="Pictures/productOrder.png" alt="product"> Products Orders</span></a>
  		<a href="reportPage.php"><span><img src="Pictures/report.png" alt="transaction"> Reports</span></a>
        <a href="services.php"><span><img src="Pictures/services.png" alt="transaction"> Services</span></a>
        <a href="product.php"><span><img src="Pictures/products.png" alt="transaction"> Products</span></a>

  		<div class="fixed">
		  <a href="logoutPage.php"><span><i class="fa fa-sign-out" style="font-size: 30px;"></i> Log Out </span></a>
		</div>
	</div>

  <div class="transaction">
    <div class="tranTitle"> 
  	  <h3>Edit Service Details</h3>
    </div> <br>

    <?php if(isset($_GET['error'])){ ?>
        <h4 style="color:red;"><?php echo $_GET['error']; ?> </h4>
    <?php }?>

    <?php
		$sql = "SELECT * FROM appointment WHERE id=$id LIMIT 1";
		$result = mysqli_query($conn, $sql);
		$row = mysqli_fetch_assoc($result);
	?>

    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) ;?>" method="POST" enctype="multipart/form-data">  
        <input type="hidden" name="id" value="<?php echo $id ?>">
        
        <div>
        <?php 
            $sql2 = "SELECT * FROM `user`
            INNER JOIN appointment ON user.id = appointment.userid 
            WHERE appointment.id=$id";
            $result2 = mysqli_query($conn, $sql2);
            $row2 = mysqli_fetch_assoc($result2);
            $customername = $row2['firstname'] . " " . $row2['lastname']; 
        ?>
            <label style="padding-right: 2.5%;padding-left:8%;" for="name">Customer Name</label>
            <input type="text" style="padding: 12px 20px" rows="2" cols="40" id="customername" name="customername"  value="<?php echo $customername?>" readonly>
        </div>
        <br/>
        <div>
        <?php 
            $sql3 = "SELECT * FROM `service`
            INNER JOIN appointment ON service.id = appointment.serviceid 
            WHERE appointment.id=$id";
            $result3 = mysqli_query($conn, $sql3);
            $row3 = mysqli_fetch_assoc($result3);
            $servicename = $row3['servicename']; 
        ?>
            <label style="padding-right: 2.5%;padding-left:21%;" for="name">Service</label>
            <select id="service" name="service" style="padding: 12px 20px">
                <option value="<?php echo $row['serviceid'] ?>">Current service: <?php echo $servicename ?></option>
                <option value="1">Haircut</option>
                <option value="2">Eyebrow Trimming</option>
                <option value="3">Hair Dye</option>
                <option value="4">Straighten Hair/Curly Hair</option>
            </select>
        </div>
        <br/>
        <div>
            <label style="padding-right: 1.5%;" for="details">Appointment Date</label>
            <input type="date" style="padding: 12px 20px" rows="5" cols="40" id="servicedetail" name="date" value="<?php echo $row['appointmentdate'] ?>">
        </div>
        <br/>
        <div>
            <label style="padding-right: 1.5%;padding-left:10%;" for="details">Appointment Time</label>

            <select id="time" name="time" style="padding: 12px 20px">
                <option value="<?php echo $row['appointmenttime'] ?>">Current appointment time: <?php echo $row['appointmenttime'] ?></option>
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
        </div>
        <br/>
        <div>
            <label style="padding-right: 1.5%;padding-left:40%;" for="name">Status</label>
            <input id="status" type="radio" name="status" value="Upcoming" <?php if ($row['status'] =="Upcoming") echo "checked";?>><label for="upcoming" style="font-size:25px;">Upcoming</label>
            <input id="status" type="radio" name="status" value="Completed" <?php if ($row['status'] =="Completed") echo "checked";?>><label for="completed" style="font-size:25px;">Completed</label>
            <input id="status" type="radio" name="status" value="Cancelled" <?php if ($row['status'] =="Cancelled") echo "checked";?>><label for="cancelled" style="font-size:25px;">Cancelled</label>
        </div>
        <br/>
        
        <br/>
        <div>
            <button style="margin-top:0;margin-bottom:0;margin-left:15%;" type="submit" class="generateBtn" name="updateService">Update</button>
        </div>
    </form>
    <form action="manageServiceAppointment.php">
        <button style="margin-top:0;margin-bottom:0;margin-left:15%;" type="submit" name="return" class="generateBtn" onclick="return confirm('Are You Sure You Want To Return?')">Cancel</button>
    </form>
</div>
    

  <footer class="FooterFooter" id="Push_footer" >
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
                      <li><img src="../Pictures/wechat.png" alt="wechat">siew2249</li>
                      <li><a href="https://www.facebook.com/jacquelinengosaloon?mibextid=ZbWKwL" ><img src="../Pictures/facebook.png" alt="facebook">Jacqueline Ngo</a></li>
                    </ul>
            </div>

            <div class="FFooterLowerPortion" >
              <sub class="Disclaimer">©2022 Jacqueline Cheers System - All Rights Reserved</sub>
            </div>
        </footer>   
</body>
</html>