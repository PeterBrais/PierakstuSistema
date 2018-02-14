<?php

	session_start();

	if(isset($_POST['usr']) && isset($_POST['pwd']) && isset($_POST['pwd2']) && isset($_POST['role']))
	{

		$username = htmlspecialchars($_POST['usr']);
		$password = htmlspecialchars($_POST['pwd']);
		$password2 = htmlspecialchars($_POST['pwd2']);
		$role = htmlspecialchars($_POST["role"]);

		//Error handlers
		//Check if username and passwords are set
		if(empty($username) || empty($password) || empty($password2) || empty($role))
		{
			$_SESSION['error'] = "Lūdzu aizpildiet visus laukus!";
			header("Location: ../signup");
			exit();
		}

		if($role == "2")
		{
			$role = "p";
		}
		elseif($role == "3")
		{
			$role = "a";
		}
		else
		{
			$_SESSION['error'] = "Lūdzu izvēlieties lietotāja lomu!";
			header("Location: ../signup");
			exit();
		}

		//Check if username input values are correct
		if(!preg_match("/^[a-zA-Z0-9]*$/", $username))
		{
			$_SESSION['error'] = "Lietotājvārds var saturēt tikai latīņu burtus un ciparus!";
			header("Location: ../signup");
			exit();
		}

		//Username already exists
		include_once "includes/user.class.php";
		if(User::Exists($username))
		{
			$_SESSION['error'] = "Lietotājvārds jau eksistē!";
			header("Location: ../signup");
			exit();
		}

		//Check password complexity
		if(strlen($password) < 8 || strlen($password) > 64)
		{
			$_SESSION['error'] = "Parolei jāsatur simbolu skaits, robežās no 8 līdz 64!";
			header("Location: ../signup");
			exit();
		}

		if(!preg_match('/^(?=.*\d)(?=.*[@#\-_$%^&+=§!\?])(?=.*[a-z])(?=.*[A-Z])[0-9A-Za-z@#\-_$%^&+=§!\?]{8,64}$/', $password))
		{
			$_SESSION['error'] = "Parolei jāsatur vismaz viens cipars, lielais un mazais latīņu burts un speciālais simbols!";
			header("Location: ../signup");
			exit();
		}

		//Passwords dont match
		if($password != $password2)
		{
			$_SESSION['error'] = "Ievadītās paroles nesakrīt!";
			header("Location: ../signup");
			exit();
		}

		$user = new User();
		$user->username = $username;
		$user->SetPassword($password);
		$user->role = $role;
		$user->Save();

		$_SESSION['success'] = "Reģistrācija veiksmīga!";
		header("Location: /");
		exit();
	}
	else
	{
		header("Location: /");
		exit();
	}

?>