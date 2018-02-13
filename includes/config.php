<?php

$DBServer = "localhost";
$DBUser = "root";
$DBPassword = "";
$DBName = "pieraksts";

//Izveido savienojumu ar datubāzi
$conn = new mysqli($DBServer, $DBUser, $DBPassword, $DBName);

if(mysqli_connect_errno())
{
	die("Savienojums ar datubāzi neizdevās!");
}

?>