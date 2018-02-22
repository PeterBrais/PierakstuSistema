<?php

$DBServer = "localhost";
$DBUser = "root";
$DBPassword = "";
$DBName = "pieraksts";

//Creating connection with Database
$conn = new mysqli($DBServer, $DBUser, $DBPassword, $DBName);

if(mysqli_connect_errno())
{
	die("Savienojums ar datubāzi neizdevās!");
}

$conn->set_charset("utf8");

?>