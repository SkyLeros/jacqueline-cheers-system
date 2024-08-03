<?php session_start(); 
    ob_start();
    

    include 'connection.php';

    if(isset($_SESSION['login']))
    {
        $_SESSION['update_pro'] = $_GET['update_pro'];
        $_SESSION['update_pro_quantity'] = $_GET['update_pro_quantity'];
        $_SESSION['update_cart_id'] = $_GET['update_cart_id'];
    
        $cart_id = $_SESSION['update_cart_id'];
        $proid = $_SESSION['update_pro'] ;
        $quantity = $_SESSION['update_pro_quantity'] ;
        $quantity = $quantity -1;
    
        if($quantity === 0) //Remove item
        {
            $sql = "DELETE FROM cart_item WHERE cart_id = '$cart_id' AND product_id = '$proid'";
            $isFound = mysqli_query($conn,$sql); 
        }
        else //Update Item
        {
            $sql = "SELECT price FROM product WHERE id='$proid'"; //Select the product price
            $isFound = mysqli_query($conn,$sql); 
            $result = mysqli_fetch_assoc($isFound); //Fetch the price
            $pro_price = $result["price"]; //Store the price
            $subtotal = $quantity * $pro_price; //Calculate new subtotal
            $sql = "UPDATE cart_item SET quantity='$quantity', subtotal = '$subtotal' WHERE product_id='$proid' AND cart_id='$cart_id' ";
            $result = mysqli_query ($conn,$sql);
        }
        
    }

    header('Location:cart.php');
    ob_end_flush();
    
?>

