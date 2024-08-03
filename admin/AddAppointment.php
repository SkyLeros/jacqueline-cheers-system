<?php include "../connection.php";ob_start();session_start();?>
<?php 

include "connection.php";

$userID_error = $serviceID_error = $addInfo_error =  $appointmentDate_error = $appointmentTime_error = null;
$userID = $serviceID = $addInfo = $appointmentDate = $appointmentTime = null;

if(!isset($_SESSION['adminEmail']))
header("Location: adminLogin.php");

if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit']))
{
  $userID = $_POST['userid'];
  $serviceID = $_POST['serviceid'];
  $addInfo = $_POST['addInfo'];
  $appointmentDate = $_POST['appointmentDate'];
  $appointmentTime = $_POST['appointmentTime'];

  if(isset($_POST['submit']) && empty($userID))
  {
      $userID_error ='Please insert user ID';
  }
  else
  {
      $userID_error ='';
  }
    if(isset($_POST['submit']) && empty($serviceID))
    {
        $serviceID_error ='Please insert service ID ';
    }
    else
    {
        $serviceID_error ='';
    }

    if(isset($_POST['submit']) && empty($addInfo))
    {
        $addInfo_error ='Please insert additional information';
    }
    else
    {
        $addInfo_error ='';
    }

    if(isset($_POST['submit']) && empty($appointmentDate))
    {
        $appointmentDate_error ='Please insert appointment date';
    }
    else
    {
        $appointmentDate_error ='';
    }

    if(isset($_POST['submit']) && empty($appointmentTime))
    {
        $appointmentTime_error ='Please insert appointment time';
    }
    else
    {
        $appointmentTime_error ='';
    }

	if (isset($_POST['submit']) && empty($userID_error) && empty($serviceID_error) && empty($addInfo_error) && empty($appointmentDate_error) && empty($appointmentTime_error)) 
	{
        date_default_timezone_set('Asia/Kuala_Lumpur');
        $CreateAt = date('Y-m-d H:i:s',time());
        

        	$sql = " INSERT INTO appointment (userid,serviceid,addtionalinformation,appointmentdate,appointmenttime,createdAt,status) VALUES ('$userID','$serviceID','$addInfo','$appointmentDate','$appointmentTime','$CreateAt','Upcoming')";
            
            $result = mysqli_query($conn, $sql);

        	if ($result === TRUE) 
        	{
                header('Location: manageServiceAppointment.php');
                    
        	} 
        	else 
        	{
            		//echo "Error: " . $sql . "<br>" . $conn->error;
                   
            
        	}

    	}  
}
ob_end_flush();
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
  	  <h3>Add New Appointment</h3>
    </div> 
    <br>
    <?php if(isset($_POST['submit']) && isset($_GET['error'])){ ?>
        <h4 style="color:red;"><?php echo $_GET['error']; ?> </h4>
    <?php }?>
    <form method="post" enctype="multipart/form-data" auto_complete="off" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) ;?>" >
        <div>
            <label style="padding-right: 2.5%;padding-left:22%;" for="userID">User ID</label>
            <input type="text" class="appointmentField" id="userid" name="userid" placeholder="User ID" value="<?php echo ($userID);?>"></input>
            <span style="color : red; font-size: 24px;"><?php if(isset($userID_error)) echo $userID_error; ?></span>
        </div>
        <br/>
        <div>
            <label style="padding-right: 2.5%;padding-left:23%;" for="serviceID">Service</label>
            <input type="text" class="appointmentField" id="serviceid" name="serviceid" placeholder="Service ID" value="<?php echo ($serviceID);?>"></input>
            <span style="color : red; font-size: 24px;"><?php if(isset($serviceID_error)) echo $serviceID_error; ?></span>
        </div>
        <br/>
        <div>
            <label style="padding-right: 2.4%" for="addInfo">Additional Information</label>
            <input type="text" class="appointmentField" id="addInfo" name="addInfo" placeholder="Short Hair/ Medium Length Hair/ Long Hair..." value="<?php echo ($addInfo);?>"></input>
            <span style="color : red; font-size: 24px;"><?php if(isset($addInfo_error)) echo $addInfo_error; ?></span>
        </div>
        <br/>
        <div class="row" style="padding-right: 13.4%;">
            <label style="padding-right: 1.5%;"  for="appointmentDate" >Appointment Date</label>
            <input style="padding:10px;padding-right: 2.4%;" type="date" name="appointmentDate">
            <span style="color : red; font-size: 24px;"> <br> <?php if(isset($appointmentDate_error)) echo $appointmentDate_error; ?> </span>
        </div>
        <br>
        <div style="padding-right: 13.4%;">
            <label style="padding-right: 1.5%;" for="appointmentTime">Appointment Time</label>
            <select style="padding:10px;padding-right: 6.4%;" id="time" name="appointmentTime">
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
            <span style="color : red; font-size: 24px;"> <br> <?php if(isset($appointmentTime_error)) echo $appointmentTime_error; ?> </span>
        </div>
        <br><br><br>
        
        <div>
            <button style="margin-top:0;margin-bottom:0;margin-left:15%;" type="submit" class="generateBtn" name="submit">Create</button>
    </div>
    </form>
    <form action="manageServiceAppointment.php">
        <button style="margin-top:0;margin-bottom:0;margin-left:15%;" type="submit" class="generateBtn" onclick="return confirm('Are You Sure You Want To Return?')">Cancel</button>
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
