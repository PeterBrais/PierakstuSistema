<?php

	session_start();

	if(isset($_POST['usr']) && isset($_POST['pwd']))
	{
		$username = htmlspecialchars($_POST['usr']);
		$password = htmlspecialchars($_POST['pwd']);

		if(empty($username) || empty($password))
		{
			$_SESSION['error'] = "Aizpildiet visus laukus!";
			header("Location: /");
			exit();
		}
		else
		{
			include_once "includes/user.class.php";

			$user = new User();
			$user->Login($username, $password);
		}
	}
	else
	{
		header("Location: /");
		exit();
	}

?>