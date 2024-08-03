<?php include "../connection.php";ob_start();session_start();?>
<?php 

include "connection.php";

if(!isset($_SESSION['adminEmail']))
header("Location: adminLogin.php");

$id = $_GET['id'];

if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['updateStatus']))
    {
        if(isset($_POST['updateStatus']) && !empty($_POST['status']))
        {
            $id = $_POST['id'];
            $status = $_POST['status'];

            date_default_timezone_set("Asia/Kuching");
            $date = date("Y-m-d");
            $time = date("H:i:s");

            $query = "UPDATE saleorder SET _status='$status',_date = '$date', _time = '$time' WHERE id='$id'";
            $result = mysqli_query($conn,$query);

            if($result){
                 header('Location: ../receipt_invoice.php?receipt='.$id.'&check=true&type=Receipt&userType=Admin');
            }
            else
            {
                echo "Failed: " . mysqli_error($conn);
            }
                
        }else {
            $error = "Please selected the option provided";
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
  		<a href="manageServiceAppointment.php"><span><img src="Pictures/serviceApp.png" alt="account"> Services Appointments</span></a>
  		<a class="active" href="manageProductOrder.php"><span><img src="Pictures/productOrder.png" alt="product"> Products Orders</span></a>
  		<a href="reportPage.php"><span><img src="Pictures/report.png" alt="transaction"> Reports</span></a>
        <a href="services.php"><span><img src="Pictures/services.png" alt="transaction"> Services</span></a>
        <a href="product.php"><span><img src="Pictures/products.png" alt="transaction"> Products</span></a>

  		<div class="fixed">
		  <a href="logoutPage.php"><span><i class="fa fa-sign-out" style="font-size: 30px;"></i> Log Out </span></a>
		</div>
	</div>

  <div class="transaction">
    <div class="tranTitle"> 
  	  <h3>Edit Product Orders</h3>
    </div> <br>

    <?php if(isset($error)){ ?>
        <h4 style="color:red;"><?php echo $error; ?> </h4>
    <?php }?>
    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) ;?>">  
        <input type="hidden" name="id" value="<?php echo $id ?>">
        
        <div>
            <label style="padding-right: 1.5%;" for="name">Status</label>
            <input id="status" type="radio" name="status" value="1"><label for="pickup" style="font-size:25px;">Picked Up</label>
        </div>
        <br/>       
        
        <div>
            <input type="submit" class="generateBtn" style="margin-bottom:10px;" name="updateStatus" value="Update">
        </div>
        
    </form>
        <form action="manageProductOrder.php">
            <button type="submit" name="return" class="generateBtn" style="margin-top:-25px;" onclick="return confirm('Are You Sure You Want To Return?')">Cancel</button>
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