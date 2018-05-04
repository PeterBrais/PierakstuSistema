<?php

	session_start();

/****************** Includes ******************/
	include_once "../includes/user.class.php";
	include_once "../includes/administrator.class.php";
/****************** Includes ******************/

	//Check if user exists in database
	if(!Administrator::ExistsUserWithID($_POST['user_id']))
	{
		header("Location: 404");
		exit();
	}

	//Admin cannot unblock himself
	if(($_POST['user_id']) == ($_SESSION['id']))
	{
		header("Location: 404");
		exit();
	}


	$inputs = ['pwd', 'agree', 'user_id'];

	foreach($inputs as $input)
	{
		if(!isset($_POST[$input]))
		{
			header("Location: /");
			exit();
		}
	}

	//Sets variables
	$password = htmlspecialchars($_POST['pwd']);
	$agree = htmlspecialchars($_POST['agree']);
	$user_id = htmlspecialchars($_POST['user_id']);
	$this_user = htmlspecialchars($_SESSION['id']);


	//Error handlers
	//Check if fields are empty
	if(empty($password) || empty($agree) || empty($user_id) || empty($this_user))
	{
		$_SESSION['error'] = "Lūdzu, aizpildiet visus obligātos laukus!";
		header("Location: unblock_user?id=$user_id");
		exit();
	}
	
	//Check password with database password
	if(!Administrator::Confirm($password, $this_user))
	{
		$_SESSION['error'] = "Ievadītā parole nav pareiza!";
		header("Location: unblock_user?id=$user_id");
		exit();
	}

	//Object
	$user = new User();
	$user->active = 1;
	$user->id = $user_id;
	$user->Delete();


	$_SESSION['success'] = "Lietotājs atbloķēts!";
	header("Location: show_users");
	exit();

?>