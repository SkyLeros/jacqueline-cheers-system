<?php include "../connection.php";ob_start();session_start();?>
<?php 

include "connection.php";

$userID_error = $productID_error = $transDate_error = $transTime_error = $shippingfee_error = $merchandiseTotal_error = $totalPrice_error = $shippingOption_error =$quantity_error= null;
$userID = $productID = $transDate = $transTime = $shippingfee = $merchandiseTotal = $totalPrice = $shippingOption =$Quantity= null;

if(!isset($_SESSION['adminEmail']))
header("Location: adminLogin.php");

if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit']))
{
  $userID = $_POST['userID'];
  $productID = $_POST['productID'];
  $transDate = $_POST['transDate'];
  $transTime = $_POST['transTime'];
  $shippingfee = $_POST['shippingfee'];
  $merchandiseTotal = $_POST['merchandiseTotal'];
  $totalPrice = $_POST['totalPrice'];
  $shippingOption = $_POST['shippingOption'];
  $Quantity = $_POST['quantity'];


  if(empty($userID))
    {
        $userID_error ='Please insert user ID';
    }
    else
    {
        $userID_error ='';
    }

    if(empty($productID))
    {
        $productID_error ='Please insert product ID';
    }
    else
    {
        $productID_error ='';
    }

    if(empty($Quantity))
    {
        $quantity_error ='Please insert quantity of the product';
    }
    else
    {
        $quantity_error ='';
    }
  
    if(empty($transDate))
    {
        $transDate_error ='Please insert transaction date';
    }
    else
    {
        $transDate_error ='';
    }

    if(empty($transTime))
    {
        $transTime_error ='Please insert transaction time';
    }
    else
    {
        $transTime_error ='';
    }

    if(empty($shippingfee))
    {
        $shippingfee_error ='Please insert shipping fees';
    }
    else
    {
        $shippingfee_error ='';
    }

    if(empty($merchandiseTotal))
    {
        $merchandiseTotal_error ='Please insert merchandise total';
    }
    else
    {
        $merchandiseTotal_error ='';
    }

    if(empty($totalPrice))
    {
        $totalPrice_error ='Please insert total prices';
    }
    else
    {
        $totalPrice_error ='';
    }

    if (!isset($_POST['shippingOption'])) 
        $shippingOption_error ='Please select shipping option';
    else
        $shippingOption_error ='';
	
	if (isset($_POST['submit']) && empty($userID_error) && empty($productID_error) && empty($transDate_error) && empty($transTime_error) && empty($shippingfee_error) && empty($merchandiseTotal_error) && empty($totalPrice_error) && empty($shippingOption_error)) 
	{
            if($shippingOption=="StandaryDelivery")
                $status = 1;
            else
                $status = 0;

        	$sql = " INSERT INTO saleorder (userid, _date, _time, shipping_fee, merchandise_total, grand_total, _status,ShippingOption) VALUES ('$userID','$transDate','$transTime','$shippingfee','$merchandiseTotal','$totalPrice','$status','$shippingOption')";
        	$result = mysqli_query($conn, $sql);

        	if ($result === TRUE) 
        	{
                $last_id = mysqli_insert_id($conn);

                //Get the product name
                $sql2 = "SELECT productname FROM product WHERE id=$productID";
                $result2 = mysqli_query($conn, $sql2);
                $row2 = mysqli_fetch_assoc($result2);
                $productname = $row2['productname'];

                //Get the orderID
                $sql3 = "INSERT INTO order_details (order_id, product_name, quantity, total_price) 
                VALUES ('$last_id','$productname','$Quantity','$totalPrice')";
                $result3 = mysqli_query($conn, $sql3);
                

            	header('Location: manageProductOrder.php');
        	} 
        	else 
        	{
            	echo "Error: " . $sql . "<br>" . $conn->error;
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
  	  <h3>Add New Order</h3>
    </div> 
    <br>
    <?php if(isset($_POST['submit']) && isset($_GET['error'])){ ?>
        <h4 style="color:red;"><?php echo $_GET['error']; ?> </h4>
    <?php }?>
    <form style="margin:0;" method="post" enctype="multipart/form-data" auto_complete="off" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) ;?>" >
        <div>
            <label style="padding-right: 1.5%;padding-left:25%;" for="userID">User ID</label>
            <input type="text" class="orderField" id="userID" name="userID" placeholder="User ID" value="<?php echo ($userID);?>"></input>
            <span style="color : red; font-size: 24px;"><?php if(isset($userID_error)) echo $userID_error; ?></span>
        </div>
        <br/>
        <div>
            <label style="padding-right: 1.5%;padding-left:20%;" for="userID">Product ID</label>
            <input type="text" class="orderField" id="productID" name="productID" placeholder="Product ID" value="<?php echo ($productID);?>"></input>
            <span style="color : red; font-size: 24px;"><?php if(isset($productID_error)) echo $productID_error; ?></span>
        </div>
        </br>
        <div>
            <label style="padding-right: 1.5%;padding-left:24%;" for="userID">Quantity</label>
            <input type="text" class="orderField" id="quantity" name="quantity" placeholder="Quantity" value="<?php echo ($Quantity);?>"></input>
            <span style="color : red; font-size: 24px;"><?php if(isset($quantity_error)) echo $quantity_error; ?></span>
        </div>
        <br/>
        <div>
            <label style="padding-right: 1.5%;padding-left: 6.8%;" for="shippingfee">Shipping Fees (RM)</label>
            <input type="text" class="orderField" id="shippingfee" name="shippingfee" placeholder="Shipping Fees" value="<?php echo ($shippingfee);?>"></input>
            <span style="color : red; font-size: 24px;"><?php if(isset($shippingfee_error)) echo $shippingfee_error; ?></span>
        </div>
        </br>
        <div>
            <label style="padding-right: 1.5%" for="merchandiseTotal">Merchandise Total (RM)</label>
            <input type="text" class="orderField" id="merchandiseTotal" name="merchandiseTotal" placeholder="Merchandise Total" value="<?php echo ($merchandiseTotal);?>"></input>
            <span style="color : red; font-size: 24px;"><?php if(isset($merchandiseTotal_error)) echo $merchandiseTotal_error; ?></span>
        </div>
        </br>
        <div>
            <label style="padding-right: 1.5%;padding-left:12%;" for="totalPrice">Total Price (RM)</label>
            <input type="text" class="orderField" id="totalPrice" name="totalPrice" placeholder="Total Price" value="<?php echo ($totalPrice);?>"></input>
            <span style="color : red; font-size: 24px;"><?php if(isset($totalPrice_error)) echo $totalPrice_error; ?></span>
        </div>
        </br>
        <div>
        <label style="padding-right: 1.5%;padding-left:18%;" for="shippingOption">Shipping Option</label>
        <input id="status" type="radio" name="shippingOption" value="StandaryDelivery" <?php if (isset($_POST["shippingOption"]) && $_POST["shippingOption"]=="StandaryDelivery") echo "checked";?> ><label for="deliver" style="font-size:25px;">Standard Delivery</label>
		<input id="status" type="radio" name="shippingOption" value="SelfCollection" <?php if (isset($_POST["shippingOption"]) && $_POST["shippingOption"]=="SelfCollection") echo "checked";?> style="font-size:20px; margin-left: 50px;" ><label for="collect" style="font-size:25px;">Self-Collection</label>
        <span style="color : red; font-size: 24px;"><?php if(isset($shippingOption_error)) echo $shippingOption_error; ?></span>
    </div></br>
        <div style="padding-right: 10.4%;"> 
            <label style="padding-right: 1.5%" for="transDate">Transaction Date</label>
            <input style="padding:10px;" type="date" name="transDate" value="<?php echo $transDate?>">
            <span style="color : red; font-size: 24px;"><?php if(isset($transDate_error)) echo $transDate_error; ?></span>
        </div>
        <br/>
        <div style="padding-right: 11.5%;">
            <label style="padding-right: 1.0%" for="transTime">Transaction Time</label>
            <input style="padding:10px;" type="time" name="transTime" value="<?php echo $transTime?>">
            <span style="color : red; font-size: 24px;"><?php if(isset($transTime_error)) echo $transTime_error; ?></span>
        </div>
        </br></br></br>
        <div>
            <button style="margin-top:0;margin-bottom:0;margin-left:15%;" type="submit" class="generateBtn" name="submit">Create</button>
        </div>
        <br/></br>
    </form>
    <form action="manageProductOrder.php"  >
        <button style="margin-top:0;margin-bottom:0;margin-left:15%;"  type="submit" class="generateBtn" onclick="return confirm('Are You Sure You Want To Return?')">Cancel</button>
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
