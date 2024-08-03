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
  		<a href="manageProductOrder.php"><span><img src="Pictures/productOrder.png" alt="product"> Products Orders</span></a>
  		<a href="reportPage.php"><span><img src="Pictures/report.png" alt="transaction"> Reports</span></a>
        <a class="active"href="services.php"><span><img src="Pictures/services.png" alt="transaction"> Services</span></a>
        <a href="product.php"><span><img src="Pictures/products.png" alt="transaction"> Products</span></a>

  		<div class="fixed">
		  <a href="logoutPage.php"><span><i class="fa fa-sign-out" style="font-size: 30px;"></i> Log Out </span></a>
		</div>
	</div>

    <?php
      $sql = "SELECT * FROM service";
      $result = $conn->query($sql) ;
    ?>

  <div class="transaction">
    <div class="tranTitle"> 
  	  <h3>Manage Services</h3>
    </div> <br>

    <!-- <form name="form"  method="POST">
    <input type="submit" name="add" class="deleteBtn" value="Add New Service" onclick="">
        <input type="submit" name="edit" class="deleteBtn" value="Edit Service" onclick="">
    <input type="submit" name="delete" class="deleteBtn" value="Delete Service" onclick="return confirm('Delete Service will delete all records in database. Are you confirm to delete?')">
     -->
    <div>
    <form action="AddService.php" method="POST">
        <input type="hidden" name="add">
        <button type="submit" name="add" class="addBtn"> Add New Service </button>
      </form>
    </div>

    <table style="width:115%">
    <tr>
        <th>Service ID</th>
        <th>Service Name</th>
        <th>Service Details</th>
        <th>Price (RM)</th>
        <th>Service Picture </th>
        <th></th>
        <th></th>
      </tr>
      <?php
            if ($result->num_rows > 0) 
            {
              while($row = $result->fetch_assoc())
              {
          ?>

          <tr>
              <td><?=$row['id'];?></td>
              <td><?=$row['servicename'];?></td>
              <td><?=$row['servicedetail'];?></td>
              <td><?=$row['price'];?></td>
              <td><img class="itempic" width="200px" height="100px" src="<?php echo $row['servicepic'];?>"></td>
              <td>
                <form action="EditService.php" method="POST">
                <input type="hidden" name="changeService" value="<?php echo $row['id'];?>">
                <button type="submit" name="editService" > Edit Service </button>
                </form>
              </td>
              <td>
                <form action="DeleteService.php" method="POST">
                <input type="hidden" name="eraseService" value="<?php echo $row['id']; ?>">
                <button type="submit" name="deleteService" onclick="return confirm('Are You Sure You Want To Delete?')"> Delete Service </button>
                </form>
              </td>
          </tr>

          <?php } }?>
    </table>
</div>
              <!-- </form> -->
    

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