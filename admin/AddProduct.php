<?php include "../connection.php";ob_start();session_start();?>
<?php 

include "connection.php";

$name_error = $detail_error = $price_error =  $stock_error = null;
$name = $detail = $price = $stock = null;

if(!isset($_SESSION['adminEmail']))
header("Location: adminLogin.php");

if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit']))
{
  $name = $_POST['productname'];
  $detail = $_POST['productdetail'];
  $price = $_POST['price'];
  $stock = $_POST['stock'];

  if(empty($name))
    {
        $name_error ='Please insert item\'s name';
    }
    else
    {
        $name_error ='';
    }
  
    if(empty($detail))
    {
        $detail_error ='Please insert item\'s details';
    }
    else
    {
        $detail_error ='';
    }

    if(empty($price))
    {
        $price_error ='Please insert item\'s price';
    }
    else
    {
        $price_error ='';
    }

    if(empty($stock))
    {
        $stock_error ='Please insert item\'s stock';
    }
    else
    {
        $stock_error ='';
    }

  if(isset($_POST['submit']) && isset($_FILES['upload']) && empty($name_error) && empty($detail_error) && empty($price_error) && empty($stock_error))
        {
            $img_name = $_FILES['upload']['name'];
            $img_size = $_FILES['upload']['size'];
            $tmp_name = $_FILES['upload']['tmp_name'];
            $error = $_FILES['upload']['error'];

            if($error === 0)
            {
                //maximum file size is 3Mb
                if($img_size > 3145728)
                {
                    $error_message = "Sorry,your image is too large!! maximum is 3 MB<br>";
                    header("Location: AddProduct.php?error=$error_message");
                }
                else
                {
                    $img_ex = pathinfo($img_name, PATHINFO_EXTENSION);//image type
                    $img_ex_lower = strtolower($img_ex);

                    //image format allowed are jpg, jpeg and png only, no jfif
                    $img_format_allowed_exs = array("jpg", "jpeg", "png");
                    if(in_array($img_ex_lower, $img_format_allowed_exs))
                    {
                        //upload the image to a folder named itempic
                        $upload_path = './itempic/'.$img_name;
                        move_uploaded_file($tmp_name,$upload_path);

                        //upload image into database
                        $query_image2 = "INSERT INTO product(productpic, productname, productdetail, price, stock) VALUES('$upload_path','$name', '$detail', '$price', '$stock')";
                        $result_image2 = mysqli_query($conn,$query_image2);
                            
                        if($result_image2)
                        {
                            $_SESSION['success'] = "Data successfully created";
                            header('Location: product.php');
                        }
                        else
                        {
                            $_SESSION['status'] = "Data failed to create";
                            header('Location: product.php');
                        }
                    }
                    else
                    {
                        $error_message = "Please upload jpg, jpeg or png files <br>";
                        header("Location: AddProduct.php?error=$error_message");
                        
                    }
                 
                }
            }
            else
            {
                $error_message = "No Image have been chosen!!! <br>";
                header("Location: AddProduct.php?error=$error_message");
            }
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
  	  <h3>Add New Product</h3>
    </div> 
    <br>
    <?php if(isset($_GET['error'])){ ?>
        <h4 style="color:red;"><?php echo $_GET['error']; ?> </h4>
    <?php }?>
    <form method="post" enctype="multipart/form-data" auto_complete="off" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) ;?>" >
        <div>
            <label style="padding-right: 1.5%;padding-left:15%;" for="name">Name</label>
            <input type="text" class="productField" id="productname" name="productname" placeholder="Item Name" value="<?php echo ($name);?>"></input>
            <span style="color : red; font-size: 24px;"><?php if(isset($_POST['submit'])) echo $name_error; ?></span>
        </div>
        <br/>
        <div>
            <label style="padding-right: 0.5%;padding-left:15%;" for="details">Details</label>
            <input type="text" class="productField" id="productdetail" name="productdetail" placeholder="Item Details" value="<?php echo ($detail);?>"></input>
            <span style="color : red; font-size: 24px;"><?php if(isset($_POST['submit'])) echo $detail_error; ?></span>
        </div>
        <br/>
        <div>
            <label style="padding-right: 2.4%;padding-left:15%;" for="stock">Stock</label>
            <input type="text" class="productField" id="stock" name="stock" placeholder="Item Stock" value="<?php echo ($stock);?>"></input>
            <span style="color : red; font-size: 24px;"><?php if(isset($_POST['submit'])) echo $stock_error; ?></span>
        </div>
        <br/>
        <div>
            <label style="padding-right: 3.0%;padding-left:15%;" for="price">Price</label>
            <input type="text" class="productField" id="price" name="price" placeholder="Item Price" value="<?php echo ($price);?>"></input>
            <span style="color : red; font-size: 24px;"><?php if(isset($_POST['submit'])) echo $price_error; ?></span>
        </div>
        <br/>
        <div>
            <label style="padding-left:20%;" for="image">Please upload an image of the item</label>
            <input type="file" name="upload"></input>
        </div>
        <br/><br/><br/>
        <div>
            <button  style="margin-top:0;margin-bottom:0;margin-left:15%;" type="submit" class="generateBtn" name="submit">Create</button>
        </div>
        <br/>
    </form>
    <form action="product.php">
        <button  style="margin-top:0;margin-bottom:0;margin-left:15%;" type="submit" class="generateBtn" onclick="return confirm('Are You Sure You Want To Return?')">Cancel</button>
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
