<?php

	session_start();

/****************** Includes ******************/
	include_once "../includes/user.class.php";
	include_once "../includes/validate.class.php";
/****************** Includes ******************/

	$inputs = ['usr', 'pwd', 'pwd2', 'role'];

	foreach($inputs as $input)
	{
		if(!isset($_POST[$input]))
		{
			header("Location: /");
			exit();
		}
	}

	//Sets variables
	$username = htmlspecialchars($_POST['usr']);
	$password = htmlspecialchars($_POST['pwd']);
	$password2 = htmlspecialchars($_POST['pwd2']);
	$role = htmlspecialchars($_POST["role"]);

	//Error handlers
	//Check if fields are empty
	if(empty($username) || empty($password) || empty($password2) || empty($role))
	{
		$_SESSION['error'] = "Lūdzu, aizpildiet visus obligātos laukus!";
		$_SESSION['register'] = $_POST;
		header("Location: signup");
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
	elseif($role == "1")
	{
		$role = "l";
	}
	else
	{
		$_SESSION['usr_role'] = "Lūdzu, izvēlieties lietotāja lomu!";
		$_SESSION['register'] = $_POST;
		header("Location: signup");
		exit();
	}

	//Check username length
	if(!Validate::IsValidNameLength($username))
	{
		$_SESSION['usr_name'] = "Lietotājvārdam jābūt garumā no 3 simboliem līdz 50 simboliem!";
		$_SESSION['register'] = $_POST;
		header("Location: signup");
		exit();
	}

	//Check if username input values are correct
	if(!Validate::IsValidUsername($username))
	{
		$_SESSION['usr_name'] = "Lietotājvārds drīkst saturēt tikai latīņu burtus un ciparus!";
		$_SESSION['register'] = $_POST;
		header("Location: signup");
		exit();
	}

	//Username already exists
	if(User::Exists($username))
	{
		$_SESSION['usr_name'] = "Lietotājvārds jau eksistē!";
		$_SESSION['register'] = $_POST;
		header("Location: signup");
		exit();
	}

	//Check password length
	if(!Validate::IsValidPasswordLength($password))
	{
		$_SESSION['pwd'] = "Parolei jābūt garumā no 8 simboliem līdz 64 simboliem!";
		$_SESSION['register'] = $_POST;
		header("Location: signup");
		exit();
	}

	//Check password complexity
	if(!Validate::IsValidPassword($password))
	{
		$_SESSION['pwd'] = "Parolei jāsastāv vismaz no viena cipara, lielā un mazā latīņu burta un speciālā simbola!";
		$_SESSION['register'] = $_POST;
		header("Location: signup");
		exit();
	}

	//Passwords dont match
	if($password != $password2)
	{
		$_SESSION['pwd'] = "Ievadītās paroles nesakrīt!";
		$_SESSION['pwd2'] = "Ievadītās paroles nesakrīt!";
		$_SESSION['register'] = $_POST;
		header("Location: signup");
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

?>