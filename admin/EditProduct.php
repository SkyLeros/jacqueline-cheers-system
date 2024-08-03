<?php include "../connection.php";session_start();?>
<?php 

include "connection.php";

if(!isset($_SESSION['adminEmail']))
header("Location: adminLogin.php");

if(isset($_POST['editProduct']))
    {
        $id = $_POST['changeProduct'];
        $query = "SELECT * FROM product WHERE id='$id' ";
        $run = mysqli_query($conn, $query);

        $row = mysqli_fetch_assoc($run);
    }
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
        <a class="active"href="product.php"><span><img src="Pictures/products.png" alt="transaction"> Products</span></a>

  		<div class="fixed">
		  <a href="logoutPage.php"><span><i class="fa fa-sign-out" style="font-size: 30px;"></i> Log Out </span></a>
		</div>
	</div>

    
  <div class="transaction">
    <div class="tranTitle"> 
  	  <h3>Edit Product Details</h3>
    </div> <br>

    <?php if(isset($_GET['error'])){ ?>
        <h4 style="color:red;"><?php echo $_GET['error']; ?> </h4>
    <?php }?>
    <form action="UpdateProduct.php" method="POST" enctype="multipart/form-data" style="padding-left:15%;">  
        <input type="hidden" name="id" value="<?php echo $row['id'] ?>">
        <div>
            <label style="padding-right: 1.5%;" for="name">Name</label>
            <input type="text" class="productField" id="productname" name="productname" placeholder="Item Name" value="<?php echo $row['productname'] ?>"></input>
        </div>
        <br/>
        <div>
            <label for="details">Details</label>
            <input type="text" class="productField"  id="productdetail" name="productdetail" placeholder="Item Details" value="<?php  echo $row['productdetail'] ?>"></input>
        </div>
        <br/>
        <div>
            <label style="padding-right: 2.4%;" for="stock">Stock</label>
            <input type="text" class="productField"  id="stock" name="stock" placeholder="Item Stock" value="<?php echo $row['stock'] ?>"></input>
        </div>
        <br/>
        <div>
            <label style="padding-right: 3.0%;" for="price">Price</label>
            <input type="text" class="productField" id="price" name="price" placeholder="Item Price" value="<?php echo $row['price'] ?>"></input>

        </div>
        <br/>
        <div>
            <img src="<?php echo $row['productpic'] ?>" style="width:300px;"alt="">
        </div>
        <br/>
        <div>
            <label for="image">Please upload an image if you want to update it!</label><br><br>
            <input type="file" name="image" ></input>
        </div>
        <br/>
        
        <div>
            <button style="margin-top:0;margin-bottom:0;width:47%;" type="submit" class="generateBtn" name="updateProduct">Update</button>
        </div>
    </form>
    <form action="product.php">
        <button style="margin-top:0;margin-bottom:0;margin-left:15%;"  type="submit" name="return" class="generateBtn" onclick="return confirm('Are You Sure You Want To Return?')">Cancel</button>
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
