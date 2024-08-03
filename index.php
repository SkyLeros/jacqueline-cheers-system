<?php session_start();  ob_start();?> 

<!DOCTYPE html>

<html lang="en">

    <head>
        <title>JCS</title>
        <link rel="icon" href="Pictures/smallicon.png">
        <link rel="shortcut icon" href="Pictures/smallicon.png">
        <link rel="stylesheet" href="CSS/stylesheet.css">
        <link rel="stylesheet" href="CSS/footer.css">
        <script src="slides.js"></script>
    </head>
    <body>

    <?php

        // Create database & connection
        include 'connection.php';

        //Declarations
        $signup = $login = $logout = $myprofile = "";

        //Check if session login is defined
        if (isset($_SESSION['login'])) 
        {
            //If it is defined, then we check is it Logged in 
            if($_SESSION['login'] === "Logged In")
            {
                //Show logout and profile button  
                $logout = "Logout"; 
                $myprofile = "My Profile";
            }
        }
        else //session login not set yet
        {
            //Show sign up and login button  
            $signup = "Sign Up"; 
            $login = "Login";
        } 
        //END SESSION DECLARATION 

        //Create Table USER
        $sql = "CREATE TABLE user (
          id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
          firstname VARCHAR(30) NOT NULL,
          lastname VARCHAR(30) NOT NULL,
          email VARCHAR(50) NOT NULL,
          region int(5) NOT NULL,
          phone INT(10) NOT NULL,
          pwd VARCHAR(30) NOT NULL,
          gender CHAR(30) NOT NULL,
          _state CHAR(30) NOT NULL,
          postcode INT(10) NOT NULL,
          _address VARCHAR(255) NOT NULL,
          city CHAR(30) NOT NULL,
          _login TINYINT(1) NOT NULL
          )";

        //_login: logged in = 1, logged out = 0
        //Inserting some Preset Users 
          if ($conn->query($sql) === TRUE) {
            echo "Table MyGuests created successfully";

            $sql = " INSERT INTO user (id, firstname, lastname, email, region, phone, pwd, gender, _state, postcode, _address, city, _login) VALUES
            (1, 'Jerry', 'Mander', 'JM1@gmail.com', 60, 1910001000, 'Cc123@', 'Male', 'Selangor', 53110, '3, Jalan Terringgi 3/5 C,', 'Kuala Lumpur', 0),
            (2, 'Holly', 'Maddson', 'HM2@gmail.com', 60, 1110001000, 'Aa123@', 'Female', 'Penang', 37701, '2, Jalan Ferringgi 5/7B', 'GeorgeTown', 0),
            (3, 'Barry', 'Halselhoff', 'BH3@gmail.com', 60, 1320001000, 'Bb123@', 'Male', 'Selangor', 48000, '4, Jalan University,', 'Petaling Jaya', 0),
            (4, 'Darrel', 'Wong', 'DW4@gmail.com', 60, 1420001000, 'Bb123@', 'Male', 'Sarawak', 93000, '6, Jalan Rodway,', 'Kuching', 0),
            (5, 'Siti', 'Ahmad', 'MA5@gmail.com', 60, 1530002000, 'Bb123@', 'Female', 'Pahang', 27600, '7, Jalan Lipis,', 'Raub', 0)";

            $result = mysqli_query($conn, $sql);
            if ($result === TRUE) {
                echo "New record created successfully";
            } else {
                echo "Error: " .  mysqli_error($conn);
            }
          } else {
       //     echo "Error creating table: " . $conn->error;
          }
        //END USER

        //Create CART
        $sql = "CREATE TABLE cart (
          id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
          userid INT(10) UNSIGNED NOT NULL,
          grand_total FLOAT(20, 2) NOT NULL,
          FOREIGN KEY (userid) REFERENCES user(id)
          ON DELETE CASCADE
          ON UPDATE CASCADE
          )";

          $result = mysqli_query($conn, $sql);
          if ($result === TRUE) 
          {
              echo "Table cart created successfully or Table exists".'<br>';
              $sql = "INSERT INTO cart (id, userid, grand_total) VALUES
              (1, 1, 0.00),
              (2, 2, 0.00),
              (3, 3, 0.00),
              (4, 4, 0.00),
              (5, 5, 0.00)";
              if ($conn->query($sql) === TRUE) {
                  echo "New CART created successfully";
              } else {
                  echo "Error: " . $sql . "<br>" . $conn->error;
              }
          }
          else
          {
        //      echo "Error creating table cart: " . mysqli_error($conn);
          }
        //END CART
        
        //Create table Cart_Item to link user's cart and products
        $sql = "CREATE TABLE cart_item (
          cart_id INT(11) UNSIGNED NOT NULL,
          product_id INT(11) UNSIGNED NOT NULL,
          quantity INT(30) NOT NULL,
          subtotal FLOAT (20,2) NOT NULL,
          FOREIGN KEY (cart_id) REFERENCES cart(id)
          ON DELETE CASCADE
          ON UPDATE CASCADE,
          FOREIGN KEY (product_id) REFERENCES product(id)
          ON DELETE CASCADE
          ON UPDATE CASCADE
          )";

          $result = mysqli_query($conn, $sql);    
          if ($result === TRUE)
          {
              echo "Table cartITEM created successfully or Table exists".'<br>';
          }
          else
          {
         //     echo "Error creating table cart: " . mysqli_error($conn);
          }
        //END CART ITEM

        //Create Order TABLE
        //_status: Paid = 1, Pending for pickup(not paid) = 0
           $sql = "CREATE TABLE saleorder(
            id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            userid int(15) UNSIGNED NOT NULL,
            _date date NOT NULL,
            _time time NOT NULL,
            shipping_fee double(15,2) NOT NULL,
            merchandise_total double(15,2) NOT NULL,
            grand_total double(15,2) NOT NULL,
            ShippingOption VARCHAR(30) NOT NULL,
            _status TINYINT(1) NOT NULL,
            FOREIGN KEY (userid) REFERENCES user(id)
            ON DELETE CASCADE
            ON UPDATE CASCADE
          )";

        $result = mysqli_query($conn, $sql);
        if ($result === TRUE){
            echo "Table Order Created Successfully".'<br>';

            //Insert Dummy order for testing purpose 
            $sql = "INSERT INTO saleorder (id, userid, _date, _time, shipping_fee, merchandise_total, grand_total,ShippingOption, _status) VALUES
            (1, 1, '2022-10-28', '12:55:45', 12, 76, 88.0,'SelfCollection', 1),
            (2, 2, '2022-10-29', '08:10:05', 0, 88, 88.0, 'StandaryDelivery', 1), 
            (3, 3, '2022-11-28', '10:20:11', 8, 10, 18.0,'SelfCollection', 1), 
            (4, 4, '2022-12-08', '15:49:59', 8, 25, 33.0, 'StandaryDelivery',1), 
            (5, 5, '2022-12-08', '21:13:12', 14, 125, 139.0,'SelfCollection', 1)";

            $result = mysqli_query($conn, $sql);
            if ($result === TRUE) {
                echo "New record created successfully";
            } else {
                echo "Error: " .  mysqli_error($conn);
            }

        }else{
       //     echo "Error creating table: " . $conn->error;
        }
        //END ORDER

        //Create order_details TABLE
            $sql = "CREATE TABLE order_details (
                order_id int(11) UNSIGNED NOT NULL, 
                product_name varchar(255) NOT NULL,
                quantity int(15) NOT NULL,
                total_price double(15,2) NOT NULL,
                PRIMARY KEY (order_id, product_name)
                )";

            $result = mysqli_query($conn, $sql);
            if ($result === TRUE){
                echo "Table Order Details Created Successfully".'<br>';

                //Insert Dummy Transactions details for testing purpose 
                $sql = "INSERT INTO order_details (order_id, product_name, quantity, total_price) VALUES
                (1, 'Mowensi Hair Care Essential Oil 60ML', 2, 76.00),
                (2, 'Diwei Keratin Deep Nourishing Treatment Spray 150ML', 2, 50),
                (2, 'Keratin Moisturizing & Smooth Creamy Hair Mask 500ML', 1, 38),
                (3, 'Luodais Hair Oil 50ML', 1, 10.00),
                (4, 'Diwei Keratin Deep Nourishing Treatment Spray 150ML', 1, 25.00),
                (5, 'Diwei Keratin Deep Nourishing Treatment Spray 150M', 3, 75.00),
                (5, 'AT Professional Essential Vitamin C 260ML', 1, 50.00)";

                
                $result = mysqli_query($conn, $sql);
                if ($result === TRUE) {
                    echo "New record created successfully";
                } else {
                    echo "Error: " .  mysqli_error($conn);
                }

            }else{
        //        echo "Error creating table: " . $conn->error;
            }
        //END ORDER DETAILS

        //PRODUCT
        //Create Table Products for the haircare products
        $sql = "CREATE TABLE product (
        id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        productname VARCHAR(255) NOT NULL,
        productdetail VARCHAR(255) NOT NULL,
        price int(11) NOT NULL,
        productpic BLOB (255) NOT NULL,
        stock int(11) NOT NULL
        )";

        $result = mysqli_query($conn, $sql);
        //Insert preset products into database table
        if ($result === TRUE) {
          //echo "Table u created successfully or Table exists".'<br>';
          
          //setup array for preset products
          $productname = array("Wini Rose Wax 100ML", 
          "Luodais Hair Oil 50ML", 
          "Mowensi Hair Care Essential Oil 60ML", 
          "AT Professional Essential Vitamin C 260ML", 
          "Diwei Keratin Deep Nourishing Treatment Spray 150ML", 
          "Keratin Moisturizing & Smooth Creamy Hair Mask 500ML");

          $productdetail = array("Japan Formulation: A strong fibre wax for high control. Leaves hair strong hold and shiny, for daily use instantly adds texture and volume.", 
          "Professional hair perfume rich oil with sweet aroma, soft and non greasy formula. Its formula, highly concentrated to nourish dry hair.", 
          "Contains moroccan argan oil, which can effectively treat damage hair and make hair supple. Nourish the hair and scalp, supplement the nutrients and moisture needed.", 
          "Giving your hair a smooth and frizz-free finish which is easy to manage. Help repair those ragged ends with our secrets for strong-looking strands.",
          "For soft, moist, manageable, beautiful strands. Reduce fly-aways, tame frizz and keep hair soft and smooth.The light weight formulation is easily absorbed.", 
          "Deep conditioning mask treatment for coarse, hard to manage/damaged hair. A perfect balance of all-natural extracts that together improve hair texture.");

          $price = array("50", "10", "38", "50", "25", "38");

          $productpic = array("itempic/item1.jpg", "itempic/item2.jpg", "itempic/item3.jpg", "itempic/item4.jpg", "itempic/item5.jpg", "itempic/item6.jpg");

          
          $index = 0;
          foreach($productname as $value){
            $sql = "INSERT INTO product(productname, productdetail, price, productpic, stock) 
            VALUES ('$value', '$productdetail[$index]', '$price[$index]', '$productpic[$index]', 50)";
            $result = mysqli_query($conn, $sql);
            if ($result === TRUE) {
              echo "New record created successfully";
            } else {
              echo "Error: " . $sql  . mysqli_error($conn);
            }
            $index++;
          }
          
        } else {
      //    echo "Error creating table: " . mysqli_error($conn);
        }
        //END PRODUCT

        //SERVICE
        //Create Table Service for the Hair Sloon's Services
        $sql1 = "CREATE TABLE service (
          id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
          servicename VARCHAR(255) NOT NULL,
          servicedetail VARCHAR(255) NOT NULL,
          price CHAR(11) NOT NULL,
          servicepic BLOB (255) NOT NULL
          )";
  
          $result1 = mysqli_query($conn, $sql1);
          //Insert preset services into database table
          if ($result1 === TRUE) {
            //echo "Table u created successfully or Table exists".'<br>';
            
            //setup array for preset services
            $servicename = array("Haircut", 
            "Eyebrow Trimming", 
            "Hair Dye",  
            "Straighten Hair/Curly Hair" 
            );
  
            $servicedetail = array("Baby/Child - RM 10 ; Teenanger - RM 12 ; Adult - RM 15", 
            "Standard Price", 
            "Short Hair - RM 50 ; Medium Length Hair - RM 100 ; Long Hair - RM 150",
            "Short Hair - RM 100 ; Medium Length Hair - RM 120 ; Long Hair - RM 150" );
  
            $price = array("10-15", "10", "50-150", "100-150");
  
            $servicepic = array("itempic/service1.jpg", "itempic/service2.jpg", "itempic/service3.jpg", "itempic/service4.jpg");
  
            
            $index = 0;
            foreach($servicename as $value){
              $sql = "INSERT INTO service(servicename, servicedetail, price, servicepic) 
              VALUES ('$value', '$servicedetail[$index]', '$price[$index]', '$servicepic[$index]')";
              $result = mysqli_query($conn, $sql);
              if ($result === TRUE) {
                echo "New record created successfully";
              } else {
                echo "Error: " . $sql  . mysqli_error($conn);
              }
              $index++;
            }
            
          } else {
       //     echo "Error creating table: " . mysqli_error($conn);
          }
        // END Serivice

        //Appointment
        //Create Table Appointment for the Hair Sloon's Services
        $sql2 = "CREATE TABLE appointment (
          id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
          userid INT(10) UNSIGNED NOT NULL,
          serviceid INT(10) UNSIGNED NOT NULL,
          addtionalinformation VARCHAR(150) NOT NULL,
          appointmentdate DATE NOT NULL,
          appointmenttime TIME NOT NULL,
          createdAt DATETIME NOT NULL,
          status VARCHAR(10) NOT NULL,
          FOREIGN KEY (userid) REFERENCES user(id),
          FOREIGN KEY (serviceid) REFERENCES service(id)
          )";
  
          $result2 = mysqli_query($conn, $sql2);
          if ($result2 === TRUE){
            $sql = "INSERT INTO appointment (userid, serviceid, addtionalinformation, appointmentdate,appointmenttime,createdAt,status) VALUES
                  (1, 1, 'Teenanger', '2022-12-25','09:00:00','2022-11-30 21:10:50','Completed'),
                  (2, 4, 'Short Hair', '2022-12-27','13:00:00','2022-12-13 09:22:35','Cancelled'),
                  (2, 3, 'Medium Length Hair', '2022-12-30','15:00:00','2022-12-15 20:17:59','Completed'),
                  (3, 3, 'Long Hair', '2023-01-03','09:00:00','2022-01-01 13:12:11','Completed'),
                  (4, 2, 'Male', '2023-01-03','10:00:00','2022-01-01 21:55:00','Cancelled'),
                  (5, 1, 'Baby', '2023-01-27','14:00:00','2022-01-03 15:20:40','Upcoming'),
                  (5, 1, 'Teenanger', '2023-01-30','16:00:00','2022-01-07 23:45:26','Upcoming')";

                  
            $result = mysqli_query($conn, $sql);
          }

        // END APPOINTMENT
        
        mysqli_close($conn);
    ?>
    <!---END APPOINTMENT Details-->
    
        <header class="HeaderHeader" id="Push_header">

            <div class="login_register">
                <ul id = abc>
                  <li id= "topnav"><a href="aboutus.php">About Us</a></li>
                  <li id= "topnav"><a href="orderhistory.php">Order History</a></li>
                  <li id= "topnav"><a href="appointment.php">Appointment</a></li>  
                  <li id= "active"><a href="index.php">Home</a></li>
                  <li><a href="index.php"><img class="logo" src="Pictures/icon.png" alt="Jacqueline Cheers System"></a></li>
                  <li><a id="expand_sidenav"><span onclick="sidebar()">&#9776;</span></a></li>
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

        <div id="mySidenav" class="sidenav">
              <a href="javascript:void(0)" class="closebtn" onclick="closesidebar()">&times;</a>
              <h6> - Category - </h6>
              <br><br><br>
              <a href="#service" >Service</a>
              <br>
              <a href="#product" >Product</a>
        </div>

        <main id="Push_main">
        <div class="slidebanner-container">
            <section class="Showcase">

              <!--SERVIECS HERE!!-->
              <div class="row">
                <h3 id = "service"> Service </h3>
                <?php 
                include 'connection.php';
                $sql = "SELECT * FROM service";
                $result = $conn->query($sql);
    
                if ($result->num_rows > 0) {
                    // output data of each row
                    while($row = $result->fetch_assoc()) {
                      echo '<form name="make_appoinment" method="post" action="appointment.php?action=makeAppointment" >';
                      echo '<div class="column"> <div class = "card_service" style="background-image: url('.$row["servicepic"].');"> <div class="layer"> ';
                 //     echo '<img src = "' . $row['servicepic'] . '"/>';
                      echo '  <div class="grid-container_1"> <h2>' . $row["servicename"]. '</h2></div>';
                      echo '  <div class="grid-container_2"> <h2 class="title">'. "$" . $row["price"] .  '</h2></div>';
                      echo '  <div class="grid-container_3_service"> </div>';
                      echo '<p class = "grid-container_4">' . $row["servicedetail"] . ' </p>';
                      echo '<input type="hidden" name="service_id" value="' .$row["id"]. '">';
                      echo '<input type="hidden" name="service_name" value="' .$row["servicename"]. '">';
                      echo '</div><input class="button" type="submit" value="Make Appointment">   ';
                      echo '</div></div>';
                      echo '</form>';
                    }
    
                } else {
                  echo '<p align=center style="font-size:18px;font-family: Arial, Helvetica, sans-serif;">'."0 results";
                }
                ?>    
                
              </div>

              <br><br><br><br>
              <div class="row">
                <h3 id = "product"> Product </h3>
                <?php 
                include 'connection.php';
                $sql = "SELECT * FROM product WHERE NOT stock = '0' ";
                $result = $conn->query($sql);
    
                if ($result->num_rows > 0) {
                    // output data of each row
                    while($row = $result->fetch_assoc()) {
                      echo '<form name="add_cart" method="post" action="cart.php">';
                      echo '<div class="column"> <div class = "card">';
                      echo '' .'<img src = "' . $row['productpic'] . '"/>' . '';
                      echo '  <div class="grid-container_1"> <h2>' . $row["productname"]. '</h2></div>';
                      echo '  <div class="grid-container_2"> <h2 class="title">'. "$" . $row["price"] .  '</h2></div>';
                      echo '  <div class="grid-container_3"> <p>' . $row["productdetail"] . '</p></div>';
                      echo '<p class = "grid-container_4"> Quantity: <input type="number" name="quantity" value="1" min="1" style="text-align:center" width="100%"> </p>';
                      echo '<input type="hidden" name="product_id" value="' .$row["id"]. '">';
                      echo '<input class="button" type="submit" value="Add Cart">   ';
                      echo '</div></div>';
                      echo '</form>';
                    }
                    
                } else {
                  echo '<p align=center style="font-size:18px;font-family: Arial, Helvetica, sans-serif;">'."0 results";
                }
                ?>            
              </div>

            </section>

        
        </main><br><br><br><br><br><br>

        <footer class="FooterFooter" id="Push_footer">
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