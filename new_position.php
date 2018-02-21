<?php

	session_start();

	if(isset($_POST['position']))
	{
		$position = htmlspecialchars($_POST['position']);

		//Error handlers
		//Check if position is set
		if(empty($position))
		{
			$_SESSION['new_position'] = "Lūdzu aizpildiet amats lauku!";
			header("Location: ../add_position");
			exit();
		}

		//Check position length
		if(strlen($position) < 3 || strlen($position) > 40)
		{
			$_SESSION['new_position'] = "Amats jāsatur simbolu skaits robežās no 3 līdz 40!";
			header("Location: ../add_position");
			exit();
		}

		//Check if position match complexity
		if(!preg_match("/^[a-zA-Z0-9\/\s.,_-]*$/", $position))
		{
			$_SESSION['new_position'] = "Amats var saturēt tikai latīņu burtus, ciparus un speciālos simbolus!";
			header("Location: ../add_position");
			exit();
		}

		//Check if possition already exists
		include_once "includes/position.class.php";
		if(Position::ExistsName($position))
		{
			$_SESSION['success'] = "Amats jau eksistē, jums nav nepieciešams to ievadīt vēlreiz!";
			header("Location: ../add_position");
			exit();
		}

		//Object
		$pos = new Position();
		$pos->position = $position;
		$pos->Save();	//Saving position in database

		$_SESSION['success'] = "Amats pievienots veiksmīgi!";
		header("Location: ../add_position");
		exit();
	}
	else
	{
		header("Location: /");
		exit();
	}