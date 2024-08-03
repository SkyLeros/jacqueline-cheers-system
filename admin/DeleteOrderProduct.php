<?php include "../connection.php";ob_start();session_start();?>
<?php

include "connection.php";

if(!isset($_SESSION['adminEmail']))
header("Location: adminLogin.php");

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

    if(isset($_POST['deleteProduct']))
    {
        $id = $_POST['eraseProduct'];
        
        $query = "DELETE FROM saleorder WHERE id='$id' ";
        $run = mysqli_query($conn, $query);

        if($run)
        {
            $_SESSION['success'] = "Deleting data successfully";
            header('Location: manageProductOrder.php');
        }
        else
        {
            $_SESSION['success'] = "Failed to delete data";
            header('Location: manageProductOrder.php');
        }

    } ob_end_flush();
?>