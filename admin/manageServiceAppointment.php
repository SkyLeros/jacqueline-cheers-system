<?php include "../connection.php";session_start();?>
<?php include "connection.php";

if(!isset($_SESSION['adminEmail']))
header("Location: adminLogin.php");

?>

<html>
<head>
        <title>JCS</title>
        <link rel="icon" href="Pictures/smallicon.png">
        <link rel="shortcut icon" href="Pictures/smallicon.png">
        <link rel="stylesheet" href="CSS/adminStyle.css">
        <link rel="stylesheet" href="../CSS/footer.css">
        <script src="slides.js"></script>

        <style>
          .SpecificDate{
            display:none;
          }
        </style>
</head>
    
<body>
 <header class="header-border">
    <div class="header-content">
      <a href="admindashboard.php"><img src="Pictures/icon.png" class="logo" ></a>
    </div>
  </header>


  <div class="sidenav">
		<a href="admindashboard.php"><span><img src="Pictures/sidebar.png" alt="sidebar"> Dashboard</span></a>
  		<a class="active"href="manageServiceAppointment.php"><span><img src="Pictures/serviceApp.png" alt="account"> Services Appointments</span></a>
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
  	  <h3>Manage Service Appointments</h3>
    </div> <br>

    <div>
    <form action="AddAppointment.php" method="POST">
        <input type="hidden" name="insertAppointment">
        <button type="submit" name="addAppointment" class="addBtn"> Add New Appointment </button>
      </form>
    </div>

    <div id="SpecificDate" class="SpecificDate" style="float:right;margin-left:10px;">
      <input type="date" <?php if(isset($_GET['date'])) {?> value=<?php echo $_GET['date'];}?> style="padding:5px;margin-bottom: 25px;border: 2px solid #ff531a;" onchange="specificdate(this)">
    </div>    

    <div id="filters" style="float:right;">
      <select style="padding:5px;margin-bottom: 25px;border: 2px solid #ff531a;" name="sortstatus" id="sortstatus" onchange="sort(this)";>
        <option value="" disabled="" selected="">Select Filter</option>
        <option value="Upcoming" <?php if(isset($_GET['request']) && $_GET['request']=="Upcoming"){ echo 'selected="selected"';}?>>Upcoming Appointment</option>
        <option value="Completed" <?php if(isset($_GET['request']) && $_GET['request']=="Completed"){ echo 'selected="selected"';}?>>Completed Appointment</option>
        <option value="Cancelled" <?php if(isset($_GET['request']) && $_GET['request']=="Cancelled") {echo 'selected="selected"';}?>>Cancelled Appointment</option>
        <option value="ASC" <?php if(isset($_GET['request']) && $_GET['request']=="ASC") {echo 'selected="selected"';}?>>Sort By Date (Ascending) </option>
        <option value="DESC" <?php if(isset($_GET['request']) && $_GET['request']=="DESC") {echo 'selected="selected"';}?>>Sort By Date (Descending) </option>
        <option value="Specific" <?php if(isset($_GET['date']) && !isset($_GET['request'])) {echo 'selected="selected"';}?>>Sort By Specific Date </option>
        <option value="All" <?php if(isset($_GET['request']) && $_GET['request']=="All") {echo 'selected="selected"';}?>>All Appointment</option>
      </select>
    </div>


    
    <script type="text/javascript">
      function sort(answer){
        if(answer.value == "All")
          window.location="manageServiceAppointment.php";
        else if(answer.value == "Specific")
          document.getElementById("SpecificDate").classList.remove("SpecificDate");
        else
          window.location="manageServiceAppointment.php?request="+answer.value; 
      }

      function specificdate(answer){
        <?php if(isset($_GET['request']) ){ ?>
          var requestvalue = <?php echo json_encode($_GET['request'])?>;
          window.location="manageServiceAppointment.php?request="+requestvalue + "&date="+answer.value; 
        <?php  }else {?>
          window.location="manageServiceAppointment.php?date="+answer.value; 
        <?php }?>
      }

      <?php if((isset($_GET['request']) && isset($_GET['date'])) || (isset($_GET['date']) && !isset($_GET['request'])) ){ ?>
        document.getElementById("SpecificDate").classList.remove("SpecificDate");
      <?php } ?>
    </script>
    
  
    <table style="width:115%">
    <tr>
        <th>Appointment ID</th>
        <th>User ID</th>
        <th>Services</th>
        <th>Additional Information</th>
        <th>Appointment Date </th>
        <th>Appointment Time </th>
        <th>Status </th>
        <th></th>
   <!--     <th></th> -->
      </tr>

      <?php
      if(isset($_GET['request']) && !isset($_GET['date']))
      { 
        $value = $_GET['request'];
        if($value=="Upcoming" || $value=="Completed" || $value=="Cancelled")
          $sql = "SELECT * FROM appointment WHERE status ='$value'";
        else
          $sql = "SELECT * FROM appointment ORDER BY appointmentdate ".$value ;
      }
      else if(isset($_GET['date']) && !isset($_GET['request']))
      {
        $value = $_GET['date'];
        $sql = "SELECT * FROM appointment WHERE appointmentdate ='$value'";
      }else if(isset($_GET['date']) && $_GET['request'])
      {
        $value = $_GET['request'];
        $value2 = $_GET['date'];
        if($value=="Upcoming" || $value=="Completed" || $value=="Cancelled")
          $sql = "SELECT * FROM appointment WHERE status ='$value' AND appointmentdate ='$value2'";
        else
          $sql = "SELECT * FROM appointment WHERE AND appointmentdate ='$value2' ORDER BY appointmentdate ".$value ;
      }else
            $sql = "SELECT * FROM appointment";

            $result = $conn->query($sql) ;
            if ($result->num_rows > 0) 
            {
              while($row = $result->fetch_assoc())
              {
          ?>

          <tr>
              <td><?php echo $row['id'];?></td>
              <td><?php echo $row['userid'];?></td>
              <td><?php echo $row['serviceid'];?></td>
              <td><?php echo $row['addtionalinformation'];?></td>
              <td><?php echo $row['appointmentdate'];?></td>
              <td><?php echo $row['appointmenttime'];?></td>
              <td><?php echo $row['status'];?></td>
              <td>
                <form action="EditServiceAppointment.php" method="POST" >
                <input type="hidden" name="changeService" value="<?php echo $row['id'];?>">
                <button type="submit" name="editService" >
                <a style="color:black; text-decoration: none;" href="EditServiceAppointment.php?id=<?php echo $row['id'] ?>">Edit Appointment</a> </button>
                </form>
              </td>
       <!--       <td>
                <form action="DeleteServiceAppointment.php" method="POST">
                <input type="hidden" name="eraseService" value="<?php /*echo $row['id']; */?>">
                <button type="submit" name="deleteService" onclick="return confirm('Are You Sure You Want To Delete?')"> Delete Service </button>
                </form>
              </td> -->
          </tr>

          <?php } }?>

    </table>
    <!-- <input type="submit" name="add" class="deleteBtn" value="Add New Appointment" onclick="">
        <input type="submit" name="edit" class="deleteBtn" value="Edit Appointment" onclick="">
    <input type="submit" name="delete" class="deleteBtn" value="Delete Appointment" onclick="return confirm('Delete Appointment will delete all records in database. Are you confirm to delete?')"> -->
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