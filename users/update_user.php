<?php

	session_start();

/****************** Includes ******************/
	include_once "../includes/validate.class.php";
	include_once "../includes/user.class.php";
	include_once "../includes/administrator.class.php";
/****************** Includes ******************/

	//Check if user exists in database
	if(!Administrator::ExistsUserWithID($_POST['user_id']))
	{
		header("Location: show_users");
		exit();
	}

	$inputs = ['usr', 'role', 'user_id'];

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
	$role = htmlspecialchars($_POST['role']);
	$user_id = htmlspecialchars($_POST['user_id']);

	if(empty($username) || empty($role) || empty($user_id))
	{
		$_SESSION['error'] = "Lūdzu aizpildiet visus obligātos laukus!";
		$_SESSION['edit_user'] = $_POST;
		header("Location: edit_user?id=$user_id");
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
		$_SESSION['usr_role'] = "Lūdzu izvēlieties lietotāja lomu!";
		$_SESSION['edit_user'] = $_POST;
		header("Location: edit_user?id=$user_id");
		exit();
	}

	//Check username length
	if(!Validate::IsValidNameLength($username))
	{
		$_SESSION['usr_name'] = "Lietotājvārdam jābūt garumā no 3 simboliem līdz 50 simboliem!";
		$_SESSION['edit_user'] = $_POST;
		header("Location: edit_user?id=$user_id");
		exit();
	}

	//Check if username input values are correct
	if(!Validate::IsValidUsername($username))
	{
		$_SESSION['usr_name'] = "Lietotājvārds drīkst saturēt tikai latīņu burtus un ciparus!";
		$_SESSION['edit_user'] = $_POST;
		header("Location: edit_user?id=$user_id");
		exit();
	}

	//Check if Username already exists
	if(User::CurrentUserUsernameExists($username, $user_id))
	{
		$_SESSION['usr_name'] = "Lietotājvārds jau eksistē!";
		$_SESSION['edit_user'] = $_POST;
		header("Location: edit_user?id=$user_id");
		exit();
	}

	//Object
	$user = new User();
	$user->username = $username;
	$user->role = $role;
	$user->id = $user_id;
	$user->Update();


	$_SESSION['success'] = "Darbinieka dati atjaunoti!";
	header("Location: show_users");
	exit();

?>