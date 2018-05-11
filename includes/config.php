<?php

$DBServer = "localhost";
$DBUser = "root";
$DBPassword = "";
// $DBName = "pieraksts";
$DBName = "pierakststest";	//test database

//Creating connection with Database
$conn = new mysqli($DBServer, $DBUser, $DBPassword, $DBName);

//Error message
if ($conn->connect_errno) {
	die("Savienojums ar datubāzi neizdevās!".$conn->connect_errno);
}

$conn->set_charset("utf8");

?>