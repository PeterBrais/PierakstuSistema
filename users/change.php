<?php

	session_start();

/****************** Includes ******************/
	include_once "../includes/user.class.php";
	include_once "../includes/validate.class.php";
	include_once "../includes/administrator.class.php";
/****************** Includes ******************/

	//Check if user exists in database
	if(!Administrator::ExistsUserWithID($_POST['user_id']))
	{
		header("Location: 404");
		exit();
	}

	$inputs = ['user_id', 'current_pwd', 'pwd', 'pwd2'];

	foreach($inputs as $input)
	{
		if(!isset($_POST[$input]))
		{
			header("Location: /");
			exit();
		}
	}

	//Returns all users data
	$user = Administrator::GetUsersData($_POST['user_id']);
	//Admin cannot change other admins password except first admin
	if((($_POST['user_id'] != $_SESSION['id']) && ($user['role'] == "a") && ($user['active'] == 1)) && ($_SESSION['id'] != 1))
	{
		header("Location: 404");
		exit();
	}

	//Sets variables
	$password = htmlspecialchars($_POST['pwd']);
	$password2 = htmlspecialchars($_POST['pwd2']);
	$current_password = htmlspecialchars($_POST["current_pwd"]);
	$user_id = htmlspecialchars($_POST['user_id']);
	$this_user = htmlspecialchars($_SESSION['id']);

	//Error handlers
	//Check if fields are empty
	if(empty($password) || empty($password2) || empty($current_password) || empty($user_id) || empty($this_user))
	{
		$_SESSION['error'] = "Lūdzu, aizpildiet visus obligātos laukus!";
		header("Location: reset?id=$user_id");
		exit();
	}

	//Check password length
	if(!Validate::IsValidPasswordLength($password))
	{
		$_SESSION['pwd'] = "Parolei jābūt garumā no 8 simboliem līdz 64 simboliem!";
		header("Location: reset?id=$user_id");
		exit();
	}

	//Check password complexity
	if(!Validate::IsValidPassword($password))
	{
		$_SESSION['pwd'] = "Parolei jāsastāv vismaz no viena cipara, lielā un mazā latīņu burta un speciālā simbola!";
		header("Location: reset?id=$user_id");
		exit();
	}

	//Passwords dont match
	if($password != $password2)
	{
		$_SESSION['pwd'] = "Ievadītās paroles nesakrīt!";
		$_SESSION['pwd2'] = "Ievadītās paroles nesakrīt!";
		header("Location: reset?id=$user_id");
		exit();
	}

	//Check password with database password
	if(!Administrator::Confirm($current_password, $this_user))
	{
		$_SESSION['current_pwd'] = "Ievadītā parole nav pareiza!";
		header("Location: reset?id=$user_id");
		exit();
	}

	//Check if new password is equal to old one
	if(Administrator::Confirm($password, $user_id))
	{
		$_SESSION['error'] = "Jaunā parole nevar būt vienāda ar veco!";
		header("Location: reset?id=$user_id");
		exit();
	}

	//Object
	$user = new User();
	$user->SetPassword($password);
	$user->id = $user_id;
	$user->UpdatePassword();


	$_SESSION['success'] = "Paroles nomaiņa veiksmīga!";
	header("Location: /");
	exit();

?>