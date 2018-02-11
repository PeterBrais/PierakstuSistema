<?php
	session_start();
	
	include "includes/config.php";

	$username = $_POST['uid'];
	$password = $_POST['pwd'];
	$role = "p";
	$active = 1;
	$created = date("Y-m-d");

	/*
	echo $username."<br>";
	echo $password
	echo $created."<br>";
	echo $active."<br>";
	echo $role;
	*/

	$sql = "INSERT INTO users (username, password, role, active, created) 
	VALUES ('$username', '$password', '$role', $active, '$created')";

	$result = $conn->query($sql);

	header("Location: ./index.php");
	
?>