<?php
	$servername = "localhost";
	$username = "root";
	$password = "";
	$dbname = "JCS";

	//Create connection
	$connection = mysqli_connect($servername, $username, $password, $dbname);

	//Check connection
	if (!$connection) {
		die("Connection failed: " . mysqli_connect_error());
	}
	
    // Create Database
	$sql = "CREATE DATABASE IF NOT EXISTS JCS";

	if (mysqli_query($connection, $sql)) {
		$connection->select_db('JCS');
	} else {
		echo "Error creating database: " . mysqli_error($connection);
	}

	// Create Table Admin
	$sqlAdmin = "CREATE TABLE IF NOT EXISTS admin(
		adminID INT(5) NOT NULL AUTO_INCREMENT,
		adminFName VARCHAR(200) NOT NULL,
        adminLName VARCHAR(200) NOT NULL,
		adminEmail VARCHAR(100) NOT NULL,
		adminPhone VARCHAR(15) NOT NULL,
		adminPW VARCHAR(200) NOT NULL,
		PRIMARY KEY(adminID)
		)";

	if (mysqli_query($connection, $sqlAdmin))
	{
	}
	else {
		echo "Error creating Table admin: " . mysqli_error($connection);
	}

    //Insert Admin Data
	$sqlAdminInsert = "INSERT INTO admin(adminID, adminFName, adminLName, adminEmail, adminPhone, adminPW)
			SELECT * FROM (SELECT '1' AS adminID, 'Jacqueline' AS adminFName, 'Ngo' AS adminLName, 'jjcs02578@gmail.com' AS adminEmail, '0123456789' AS adminPhone, 'AdminJcs@123' AS adminPW)
			AS temp
			WHERE NOT EXISTS (
				SELECT adminFName FROM admin WHERE adminFName = 'Jacqueline'
			) LIMIT 1";
	if (mysqli_query($connection, $sqlAdminInsert))
	{
	}
	else {
		echo "Error insert admin: " . mysqli_error($connection);
	}		
?>
