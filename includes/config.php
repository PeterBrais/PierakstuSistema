<?php

$servername = "localhost";
$username = "root";
$password = "";
$database = "pieraksts";

//Izveido savienojumu ar datubāzi
$conn = new mysqli($servername, $username, $password, $database);

if(!$conn)
{
	die("Connection failed!".mysqli_error());
}

?>