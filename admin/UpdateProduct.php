<?php include "../connection.php";ob_start();session_start();?>
<?php

if(!isset($_SESSION['adminEmail']))
header("Location: adminLogin.php");

include "connection.php";

if(isset($_SESSION['success']) && $_SESSION['success'] !='')
{
    echo '<h3> '.$_SESSION['success'].' </h3>';
    unset($_SESSION['success']);
}
if(isset($_SESSION['status']) && $_SESSION['status'] !='')
{
    echo '<h3> '.$_SESSION['status'].' </h3>';
    unset($_SESSION['status']);
}
    
    if(isset($_POST['updateProduct']) && isset($_FILES['image']))
        {
            $id = $_POST['id'];
            $name = $_POST['productname'];
            $details = $_POST['productdetail'];
            $price = $_POST['price'];
            $stock = $_POST['stock'];
            $img_name = $_FILES['image']['name'];
            $img_size = $_FILES['image']['size'];
            $tmp_name = $_FILES['image']['tmp_name'];
            $error = $_FILES['image']['error'];

            if($error === 0)
            {
                if($img_size > 3145728)
                {
                    header("refresh:0;url=product.php"); 
                    echo '<script type="text/javascript">alert("Sorry,your image is too large!! maximum is 3 MB");</script>';
                }else
                {
                    $img_ex = pathinfo($img_name, PATHINFO_EXTENSION);//image type
                    $img_ex_lower = strtolower($img_ex);

                    //image format allowed are jpg, jpeg and png only, no jfif
                    $img_format_allowed_exs = array("jpg", "jpeg", "png");
                    if(in_array($img_ex_lower, $img_format_allowed_exs))
                    {
                        //upload the image to a folder named cart_image
                        $upload_path = './itempic/'.$img_name;
                        move_uploaded_file($tmp_name,$upload_path);

                        $query_image1 = "SELECT * FROM product WHERE id='$id'";
                        $result_image1 = mysqli_query($conn,$query_image1);

                        if($result_image1){
                            $query = "UPDATE product SET productpic='$upload_path', productname='$name', productdetail='$details', price='$price', stock='$stock' WHERE id='$id'";
                            $run = mysqli_query($conn, $query);

                            if($run)
                            {
                                $_SESSION['success'] = "Data updated successfully";
                                header('Location: product.php');
                            }
                            else
                            {
                                $_SESSION['status'] = "Data failed to be updated";
                                header('Location: product.php');
                            }
                        }else{
                            echo("Error description: " . mysqli_error($conn));
                        }
                    }else
                    {
                        
                        header("refresh:0;url=product.php"); 
                        echo '<script type="text/javascript">alert("Please upload jpg, jpeg or png files");</script>';
                       
                    }
                }
            }else
            {
                
                $query1 = "SELECT * FROM product WHERE id='$id'";
                $result1 = mysqli_query($conn,$query1);

                if($result1){
                $query = "UPDATE product SET  productname='$name', productdetail='$details', price='$price', stock='$stock' WHERE id='$id'";
                $run = mysqli_query($conn, $query);

                if($run)
                {
                    $_SESSION['success'] = "Data updated successfully";
                    header('Location: product.php');
                }
                else
                {
                    $_SESSION['status'] = "Data failed to be updated";
                    header('Location: product.php');
                }
               }
            }
        } ob_end_flush();
?>