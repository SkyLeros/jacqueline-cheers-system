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
        <a href="services.php"><span><img src="Pictures/services.png" alt="transaction"> Services</span></a>
        <a class="active" href="product.php"><span><img src="Pictures/products.png" alt="transaction"> Products</span></a>

  		<div class="fixed">
		  <a href="logoutPage.php"><span><i class="fa fa-sign-out" style="font-size: 30px;"></i> Log Out </span></a>
		</div>
	</div>

    <?php
      $sql = "SELECT * FROM product";
      $result = $conn->query($sql) ;
    ?>

  <div class="transaction">
    <div class="tranTitle"> 
  	  <h3>Manage Products</h3>
    </div> <br>
    
    <div>
    <form action="AddProduct.php" method="POST">
        <input type="hidden" name="insertProduct">
        <button type="submit" name="addProduct" class="addBtn"> Add New Product </button>
      </form>
    </div>

    <table style="width:115%">
    <tr>
        <th>Prodcut ID</th>
        <th>Product Name</th>
        <th>Product Details</th>
        <th>Price (RM)</th>
        <th>Product Picture </th>
        <th>Stock </th>
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
              <td><?=$row['productname'];?></td>
              <td><?=$row['productdetail'];?></td>
              <td><?=$row['price'];?></td>
              <td><img class="itempic" width="200px" height="100px" src="<?php echo $row['productpic'];?>"></td>
              <td><?=$row['stock'];?></td>
              <td>
                <form action="EditProduct.php" method="POST">
                <input type="hidden" name="changeProduct" value="<?php echo $row['id'];?>">
                <button type="submit" name="editProduct" > Edit Product </button>
                </form>
              </td>
              <td>
                <form action="DeleteProduct.php" method="POST">
                <input type="hidden" name="eraseProduct" value="<?php echo $row['id']; ?>">
                <button type="submit" name="deleteProduct" onclick="return confirm('Are You Sure You Want To Delete?')"> Delete Product </button>
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