<?php
	session_start();

	include "includes/config.php";

	$username = $_POST['uid'];
	$password = $_POST['pwd'];

	$sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";

	$result = $conn->query($sql);

	if(!$row = $result->fetch_assoc())
	{
		echo "Lietoājvārds vai parole nav pareiza!";
	}
	else
	{
		$_SESSION['id'] = $row['id'];
	}
	
	header("Location: ./index.php");
?>