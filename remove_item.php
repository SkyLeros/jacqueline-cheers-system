<?php session_start(); 
    ob_start();
    

    include 'connection.php';

    if(isset($_SESSION['login']) && isset($_POST['update_pro']))
    {
        $_SESSION['update_pro'] = $_POST['update_pro'];
        $_SESSION['update_cart_id'] = $_POST['update_cart_id'];
    
        $cart_id = $_SESSION['update_cart_id'];
        $proid = $_SESSION['update_pro'] ;
    
        $sql = "DELETE FROM cart_item WHERE cart_id = '$cart_id' AND product_id = '$proid'";
        $isFound = mysqli_query($conn,$sql); 

        header('Location:cart.php');
    }

    
    if(isset($_POST['delete']))
    {
        $id = $_POST['erase'];
        $sql = " SELECT * FROM (appointment INNER JOIN user ON appointment.userid=user.id) INNER JOIN service ON appointment.serviceid = service.id  WHERE appointment.id='$id'";
        $result_get_appointmentInfo = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result_get_appointmentInfo);
        $fullName = $row['firstname'].' '.$row['lastname'];
        $appointmentdate = $row['appointmentdate'];
        $appointmenttime = $row['appointmenttime'];
        $service_name = $row['servicename'];
        $addtionalinformation = $row['addtionalinformation'];
        $CreateAt = $row['createdAt'];

        //Send an email to the Owner telling them that there has new upcoming appointment 
        $to = "jjcs02578@gmail.com"; //email destination
        $subject = "Jacqueline Cheers System";

        $message = "
            <img src=\"https://i.imgur.com/qS64mJ9.png\">
            <h1 style=\"color:red;\" >Appointment Cancellation </h1>
            <h3>Dear Jacqueline,</h3>
            <h3>The appointment created at $CreateAt has been canceled by customer!</h3>
            <br>
            <hr >
            <h2>Appointment Details</h2>
            <hr >
            <h4>Customer Name :  <strong style=\"color:#FF1493;\"> $fullName </strong> </h4><hr >
            <h4>Date :  <strong style=\"color:#FF1493;\"> $appointmentdate </strong> </h4><hr >
            <h4>Time :  <strong style=\"color:#FF1493;\"> $appointmenttime </strong> </h4><hr >
            <h4>Service selected :  <strong style=\"color:#FF1493;\"> $service_name </strong> </h4><hr >
            <h4>Additional Information :  <strong style=\"color:#FF1493;\"> $addtionalinformation </strong> </h4><hr >";


        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

        $headers .= 'From: JCS <jjcs02578@gmail.com>' . "\r\n";
        $headers .= 'Cc: danny.yii@hotmail.com' . "\r\n";

        mail($to,$subject,$message,$headers);
        
        $query = "UPDATE appointment SET status= 'Cancelled' WHERE id='$id' ";
        $result = mysqli_query($conn, $query);

        header('Location: appointment.php');
 
    }  
    ob_end_flush();
?>

