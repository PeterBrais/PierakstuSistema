<?php

	session_start();

/****************** Includes ******************/
	include_once "../includes/position.class.php";
/****************** Includes ******************/

	if(!isset($_SESSION['id']) && !isset($_SESSION['role']))	//Check if user is logged in
	{
		header("Location: 404");
		exit();
	}

	if((($_SESSION['role'] != "a") && ($_SESSION['role'] != "p")) || ($_SESSION['active'] != 1))	//Check if user have permission to delete data
	{
		header("Location: 404");
		exit();
	}

	//Check if ID is set
	if(!isset($_GET['id']))
	{
		header("Location: 404");
		exit();
	}

	//Check if Position with ID exists in database
	$position_id = $_GET['id'];
	if(!Position::Exists($position_id))
	{
		header("Location: 404");
		exit();
	}

	//Check if position is not used, than allow to delete it
	if(Position::IsPositionUsed($position_id))
	{
		header("Location: 404");
		exit();
	}


	//Object
	$pos = new Position();
	$pos->id = $position_id;
	$pos->Delete();


	$_SESSION['success'] = "Amats izdzēsts!";
	header("Location: show_positions");
	exit();

?>