<?php

	session_start();

/****************** Includes ******************/
	include_once "../includes/position.class.php";
	include_once "../includes/validate.class.php";
/****************** Includes ******************/

	//Check if position exists in database
	if(!Position::Exists($_POST['position_id']))
	{
		header("Location: 404");
		exit();
	}

	$inputs = ['name', 'position_id'];

	foreach($inputs as $input)
	{
		if(!isset($_POST[$input]))
		{
			header("Location: /");
			exit();
		}
	}

	//Sets variables
	$position = htmlspecialchars($_POST['name']);
	$position_id = htmlspecialchars($_POST['position_id']);

	//Error handlers
	//Check if position and id is set
	if(empty($position) || empty($position_id))
	{
		$_SESSION['update_position'] = "Lūdzu, aizpildiet Amats lauku!";
		$_SESSION['edit_position'] = $_POST;
		header("Location: edit_position?id=$position_id");
		exit();
	}

	//Check position length
	if(!Validate::IsValidPositionLength($position))
	{
		$_SESSION['update_position'] = "Amats jābūt garumā no 3 simboliem līdz 255 simboliem!";
		$_SESSION['edit_position'] = $_POST;
		header("Location: edit_position?id=$position_id");
		exit();
	}

	//Check if position match complexity
	if(!Validate::IsValidText($position))
	{
		$_SESSION['update_position'] = "Amats drīkst saturēt tikai latīņu burtus, ciparus un speciālos simbolus!";
		$_SESSION['edit_position'] = $_POST;
		header("Location: edit_position?id=$position_id");
		exit();
	}

	//Check if Position already exists
	if(Position::CurrentPositionExists($position, $position_id))
	{
		$_SESSION['update_position'] = "Amats jau eksistē!";
		$_SESSION['edit_position'] = $_POST;
		header("Location: edit_position?id=$position_id");
		exit();
	}

	//Object
	$pos = new Position();
	$pos->name = $position;
	$pos->id = $position_id;
	$pos->Update();

	$_SESSION['success'] = "Amats atjaunots veiksmīgi!";
	header("Location: show_positions");
	exit();

?>