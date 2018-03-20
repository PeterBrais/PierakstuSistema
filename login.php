<?php

	session_start();

	$inputs = ['usr', 'pwd'];

	foreach($inputs as $input)
	{
		if(!isset($_POST[$input]))
		{
			header("Location: /");
			exit();
		}
	}

		$username = htmlspecialchars($_POST['usr']);
		$password = htmlspecialchars($_POST['pwd']);

		if(empty($username) || empty($password))
		{
			$_SESSION['error'] = "Lūdzu aizpildiet visus laukus!";
			$_SESSION['username_login'] = $_POST['usr'];
			header("Location: /");
			exit();
		}
		else
		{
			include_once "includes/user.class.php";

			$user = new User();
			$user->Login($username, $password);
		}

?>