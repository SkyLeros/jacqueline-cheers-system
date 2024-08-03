<?php session_start(); ob_start();?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Order History - JCS</title>
        <link rel="icon" href="Pictures/icon.png">
        <link rel="stylesheet" href="CSS/orderhistory.css">
        <link rel="stylesheet" href="CSS/footer.css">
    </head>

    <body>
        <?php
         //For buttons in HEADER
         $signup = $login = $logout = $myprofile = "";
         //Check if session login is defined
         if (isset($_SESSION['login'])) 
         {
             //If it is defined, then we check is it Logged in 
             if($_SESSION['login'] === "Logged In")
             {
                 $logout = "Logout";
                 $myprofile = "My Profile";
             }
         }
         else //session login not set yet
         {
             $signup = "Sign Up";
             $login = "Login";
         }

         // Create onnection
         include 'connection.php';
         if(isset($_SESSION['user_id']))
            $userid = $_SESSION['user_id']; 
         ?>
        <header class="HeaderHeader">

            <div class="login_register">
                <ul id = abc>
                <li id= "topnav"><a href="aboutus.php">About Us</a></li>
                <li id= "active"><a href="orderhistory.php">Order History</a></li>
                <li id= "topnav"><a href="appointment.php">Appointment</a></li>  
                <li id= "topnav"><a href="index.php">Home</a></li>
                <li><a href="index.php"><img class="logo" src="Pictures/icon.png" alt="Jacqueline Cheers System"></a></li>
                </ul>

                <ul>
                <li><a href="logout.php"  ><?php echo $logout?></a></li>
                <li><a href="register.php"><?php echo $signup?></a></li>
                <li>&nbsp&nbsp|&nbsp</li>
                <li><a href="login.php" ><?php echo $login?></a></li>
                <li><a href="myProfile.php"  ><?php echo $myprofile?></a></li>
                <li><img class="image_login_register" src="Pictures/login_register_icon.png" alt="Login and register icon"></a>
                </ul>
                
            </div>

            <a class = "cart_position" href="cart.php"> <img id="cart" src="Pictures/cart.png" alt="Cart"> </a>
            
        </header>
        
        

            <?php
            if(!isset($_SESSION['login']))
            {
                  //DISPLAY THE NOTIFICATION
                  echo '<div class="coll">'; 
                  echo    '<div class="coll1"></div>'; 
                  echo    '<div class="coll2">'; 
                  echo        '<p class ="notify">Please <span id ="bold">Log In</span> Before You Can Start Viewing History(s)!</p><br><br>';
                  echo        '<div class="placement"><a href="login.php" class="buttons">Login</a></div>';
                  echo    '</div>';
                  echo    '<div class="coll3"></div>';
                  echo '</div>';
            }else
            {
                echo '<div class="content-box">
                        <p>Order History
                        <select style="padding:5px;margin-bottom: 25px;border: 2px solid #ff531a;float:right;" name="sortstatus" id="sortstatus" onchange="sort(this)";>
                            <option value="" disabled="" selected="">Select Filter</option>
                            <option value="Paid">Paid Order</option>
                            <option value="Unpaid">Unpaid Order</option>
                            <option value="All">All Order</option>
                        </select>
                        </p><br>';
            ?>
                        <script type="text/javascript">
                        function sort(answer){
                            if(answer.value == "All")
                            window.location="orderhistory.php";
                            else
                            window.location="orderhistory.php?request="+answer.value; 
                        }

                        </script>
            <?php
            if(isset($_GET['request']))
            {
                $value = $_GET['request'];
                if($value == "Paid")
                    $sql = "SELECT id, _date, _time, grand_total, merchandise_total,_status FROM saleorder WHERE userid = '$userid' AND _status =1";
                else
                    $sql = "SELECT id, _date, _time, grand_total, merchandise_total,_status FROM saleorder WHERE userid = '$userid' AND _status =0";
            }
            else
                $sql = "SELECT id, _date, _time, grand_total, merchandise_total,_status FROM saleorder WHERE userid = '$userid'";
                $isFound = mysqli_query($conn,$sql); 

                if(mysqli_num_rows($isFound) > 0)
                {
                    echo "<table>
                    <thead>
                        <tr>
                        <th>Order ID</th>
                        <th>Date </th>
                        <th>Time</th>
                        <th>Merchandise Total</th>
                        <th>Order Total</th>
                        <th>Status</th>
                        </tr>
                    </thead><tbody>";
                    while($row = mysqli_fetch_assoc($isFound))
                    {
                        if($row['_status'] == 1)
                        {    $status ="Paid";
                             $type = "Receipt";}
                        else
                        {    $status ="Waiting for pick up and payment";
                             $type = "Invoice";}

                        //The list is a button that generate the receipt
                    ?> 
                        <tr data-href='receipt_invoice.php?receipt=<?php echo $row['id']?>&check=false&type=<?php echo $type?>'>
                    <?php    echo'<td>'.$row['id'].'</td>'.
                        '<td>'.$row['_date'].'</td>
                        <td>'.$row['_time'].'</td>
                        <td>'.$row['merchandise_total'].'</td>
                        <td>'.$row['grand_total'].'</td>
                        <td>'.$status.'</td>
                        </tr>';
                        
                    }
                    echo"</tbody>
                    </table>";
                    echo '<div class="empty_btm"></div>';
                }
                else //mysqli_num_rows($isFound) = 0
                {
                    echo '<p class="no_trans">'."No saleorder being Made Yet!".'</p>';
                }
            }
            ?>
            </div>
            <script>
                document.addEventListener("DOMContentLoaded", () =>{
                    const rows = document.querySelectorAll("tr[data-href");
                    
                    rows.forEach(row =>{
                        
                        row.addEventListener("click",() =>{
                            $link = row.dataset.href;
                            window.open($link);
                        })
                    });
                });
            </script>
            
    <footer class="FooterFooter" id="Push_footer">
            <div class="FFooterUpperPortion">
                <div class="FFooterIcon">
                    <img  src="Pictures/icon.png" alt="Jacqueline Cheers System">
                </div>
            </div>

            <br>
            <br>
            <hr id="FooterLine"/>

            <div class="FFooterBlocks">
                    <ul>
                      <li><img src="Pictures/wechat.png" alt="wechat">siew2249</li>
                      <li><a href="https://www.facebook.com/jacquelinengosaloon?mibextid=ZbWKwL" ><img src="Pictures/facebook.png" alt="facebook">Jacqueline Ngo</a></li>
                    </ul>
            </div>

            <div class="FFooterLowerPortion" >
              <sub class="Disclaimer">©2022 Jacqueline Cheers System - All Rights Reserved</sub>
            </div>
        </footer> 
    </body>

</html>

<?php ob_end_flush();?>