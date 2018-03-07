<?php

	session_start();

/****************** Includes ******************/
	include_once "../includes/position.class.php";
	include_once "../includes/validate.class.php";
/****************** Includes ******************/

	if(!isset($_POST['name']))
	{
		header("Location: /");
		exit();
	}

	//Sets variable
	$position = htmlspecialchars($_POST['name']);

	//Error handlers
	//Check if position is set
	if(empty($position))
	{
		$_SESSION['new_position'] = "Lūdzu aizpildiet Amats lauku!";
		header("Location: add_position");
		exit();
	}

	//Check position length
	if(!Validate::IsValidPositionLength($position))
	{
		$_SESSION['new_position'] = "Amats jābūt garumā no 3 simboliem līdz 40 simboliem!";
		header("Location: add_position");
		exit();
	}

	//Check if position match complexity
	if(!Validate::IsValidText($position))
	{
		$_SESSION['new_position'] = "Amats drīkst saturēt tikai latīņu burtus, ciparus un speciālos simbolus!";
		header("Location: add_position");
		exit();
	}

	//Check if possition already exists
	if(Position::ExistsName($position))
	{
		$_SESSION['warning'] = "Amats jau eksistē, jums nav nepieciešams to ievadīt vēlreiz!";
		header("Location: add_position");
		exit();
	}

	//Object
	$pos = new Position();
	$pos->name = $position;
	$pos->Save();

	$_SESSION['success'] = "Amats pievienots veiksmīgi!";
	header("Location: add_position");
	exit();