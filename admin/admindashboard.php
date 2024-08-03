<?php include "../connection.php";session_start();?>
<?php include "connection.php";

if(!isset($_SESSION['adminEmail']))
header("Location: adminLogin.php");

function display_totalAppt()
{
	global $conn;

  $mon = new DateTime();    
  $month = $mon->format('F');
  $year = $mon->format('Y');  

  $sql = "SELECT * FROM appointment WHERE MONTHNAME(appointmentdate)= $month AND YEAR(appointmentdate)=$year";
  $result = $conn->query($sql) ;

  echo"<br>";
  echo"<u>$month $year </u>";
  echo"<br>";
  echo"<br>";
  echo"<br>";
  echo"<br>";
  
	$sql2="SELECT * FROM appointment WHERE MONTH(appointmentdate) = MONTH(CURRENT_DATE()) AND YEAR(appointmentdate) = YEAR(CURRENT_DATE())";
	$result2 = $conn->query($sql2) ;
	$row2 = $result2->num_rows;

  echo"Total Appointments : $row2";
  echo"<br>";
  echo"<br>";
  echo"<br>";
}

function display_totalSales()
{
	global $conn;

  $mon = new DateTime();    
  $month = $mon->format('F');
  $year = $mon->format('Y');  

  $sql = "SELECT * FROM appointment WHERE MONTHNAME(appointmentdate)= $month AND YEAR(appointmentdate)=$year";
  $result = $conn->query($sql) ;

  echo"<br>";
  echo"<u>$month $year </u>";
  echo"<br>";
  echo"<br>";
  echo"<br>";
  echo"<br>";

  $sql3="SELECT * FROM saleorder WHERE MONTH(_date) = MONTH(CURRENT_DATE()) AND YEAR(_date) = YEAR(CURRENT_DATE())";
	$result3 = $conn->query($sql3) ;
	$row3 = $result3->num_rows;

  echo"Total Orders : $row3";
  echo"<br>";
  echo"<br>";
  echo"<br>";
  
}
?>

<html>
<head>
        <title>JCS</title>
        <link rel="icon" href="Pictures/smallicon.png">
        <link rel="shortcut icon" href="Pictures/smallicon.png">
        <link rel="stylesheet" href="CSS/adminStyle.css">
        <link rel="stylesheet" href="../CSS/footer.css">
        <link rel="stylesheet" href="CSS/calendar.css">
        <script src="slides.js"></script>
    </head>

<body>
  <header class="header-border">
    <div class="header-content">
      <a href="admindashboard.php"><img src="Pictures/icon.png" class="logo"></a>
    </div>
  </header>



	<div class="sidenav">
		<a class="active" href="admindashboard.php"><span><img src="Pictures/sidebar.png" alt="sidebar"> Dashboard</span></a>
  		<a href="manageServiceAppointment.php"><span><img src="Pictures/serviceApp.png" alt="account"> Services Appointments</span></a>
  		<a href="manageProductOrder.php"><span><img src="Pictures/productOrder.png" alt="product"> Products Orders</span></a>
  		<a href="reportPage.php"><span><img src="Pictures/report.png" alt="transaction"> Reports</span></a>
        <a href="services.php"><span><img src="Pictures/services.png" alt="transaction"> Services</span></a>
        <a href="product.php"><span><img src="Pictures/products.png" alt="transaction"> Products</span></a>

  		<div class="fixed">
		  <a href="logoutPage.php"><span><i class="fa fa-sign-out" style="font-size: 30px;"></i> Log Out </span></a>
		</div>
	</div>

	<div class="admin">
		<div class="totalOrder">
        	<h3>Monthly Services Appointment</h3>
	        <hr size="2" width="70%" color=black>
	        <h1><?php display_totalAppt(); ?></h1>
      	</div>

	    <div class="totalSales">
	        <h3>Monthly Products Orders</h3>
	        <hr size="2" width="70%" color=black>
	        <h1><?php display_totalSales(); ?></h1>
	    </div>
  	</div>

    
    
    <hr id="calendarLine">
    
    <?php
      //Check month & Get month
      if(isset($_GET['month']))
      {
        $value = $_GET['month'];
        if($value == '01')
          $monthCalendar = 1;
        else if ($value == '02')
          $monthCalendar = 2;
        else if ($value == '03')
          $monthCalendar = 3;
        else if ($value == '04')
          $monthCalendar = 4;
        else if ($value == '05')
          $monthCalendar = 5;
        else if ($value == '06')
          $monthCalendar = 6;
        else if ($value == '07')
          $monthCalendar = 7;
        else if ($value == '08')
          $monthCalendar = 8;
        else if ($value == '09')
          $monthCalendar = 9;
        else if ($value == '10')
          $monthCalendar = 10;
        else if ($value == '11')
          $monthCalendar = 11;
        else if ($value == '12')
          $monthCalendar = 12;
              
      }
      else
      {
        $monthCalendar = date('n');
      }
    ?>
    
    <!--Select Month for Calendar -->
    <div class ="ChooseMonth">
      <label>Please select the Month: </label>
      <select name="selectMonth" id="selectMonth" onchange="sort(this)";>
        <option value='01' <?php if($monthCalendar == 1) echo "selected=\"selected\""; ?> >January</option>
        <option value='02' <?php if($monthCalendar == 2) echo "selected=\"selected\""; ?> >February</option>
        <option value='03' <?php if($monthCalendar == 3) echo "selected=\"selected\""; ?> >March</option>
        <option value='04' <?php if($monthCalendar == 4) echo "selected=\"selected\""; ?> >April</option>
        <option value='05' <?php if($monthCalendar == 5) echo "selected=\"selected\""; ?> >May</option>
        <option value='06' <?php if($monthCalendar == 6) echo "selected=\"selected\""; ?> >June</option>
        <option value='07' <?php if($monthCalendar == 7) echo "selected=\"selected\""; ?> >July</option>
        <option value='08' <?php if($monthCalendar == 8) echo "selected=\"selected\""; ?> >August</option>
        <option value='09' <?php if($monthCalendar == 9) echo "selected=\"selected\""; ?> >September</option>
        <option value='10' <?php if($monthCalendar == 10) echo "selected=\"selected\""; ?> >October</option>
        <option value='11' <?php if($monthCalendar == 11) echo "selected=\"selected\""; ?> >November</option>
        <option value='12' <?php if($monthCalendar == 12) echo "selected=\"selected\""; ?> >December</option> 
      </select>
    </div>

    <!--JS for Calendar Month selection-->
    <script type="text/javascript">
      function sort(answer){
          window.location="admindashboard.php?month="+answer.value; 
      }
    </script>

    <div class="calendarTitle"
      <h4 style="font-weight: bold;">Event Calendar</h4>
    </div>

    <div class="calendarBox">

      <?php 
        include 'adminCalendar.php'; //Include the calendar

        

        $dayCalendar = date("d");
        $yearCalendar = date("Y");
        $calendar = new Calendar($dayCalendar, $monthCalendar,$yearCalendar); //Create calendar obj

        $sqlCalendar = "SELECT * FROM appointment INNER JOIN service ON appointment.serviceid=service.id 
        WHERE MONTH(appointmentdate)= $monthCalendar AND YEAR(appointmentdate)=$yearCalendar ";
        $resultCalendar = $conn->query($sqlCalendar) ;
        if($resultCalendar->num_rows > 0)
        {
          while($rowCalendar = $resultCalendar->fetch_assoc())
          {
            $timeBook = $rowCalendar['appointmenttime'];
            $dayBook_Date= $rowCalendar['appointmentdate'];
            $detail = $rowCalendar['servicename'];
            $detail = $detail." - ".$timeBook; 
            $calendar->add_event($detail, $dayBook_Date, 1, 'red');
          }
        }

        echo $calendar; //Display calendar
      ?>

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


