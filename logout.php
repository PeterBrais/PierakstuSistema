<?php

	session_start();

	if(isset($_SESSION['id']))
	{
		include_once "includes/user.class.php";
		User::Logout();
	}
?>