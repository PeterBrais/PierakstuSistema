<?php

	session_start();

/****************** Includes ******************/
	include_once "../includes/sorting_production.class.php";
	include_once "../includes/manager.class.php";
/****************** Includes ******************/

	if(!isset($_SESSION['id']) && !isset($_SESSION['role']))	//Check if user is logged in
	{
		header("Location: 404");
		exit();
	}
	
	if((($_SESSION['role'] != "a") && ($_SESSION['role'] != "p") && ($_SESSION['role'] != "l")) || ($_SESSION['active'] != 1))	//Check if user have permission to delete
	{
		header("Location: 404");
		exit();
	}

	//Check if ID and invoice is set
	if(!isset($_GET['id']))
	{
		header("Location: 404");
		exit();
	}

	//Check if sorting production in database exists
	$production_id = $_GET['id'];
	if(!SortingProduction::ExistsReservedProductionWithID($production_id))
	{
		header("Location: 404");
		exit();
	}

	//Object
	$sortingProduction = new SortingProduction();

	//Delete
	$sortingProduction->id = $production_id;
	$sortingProduction->Delete();


	$_SESSION['success'] = "Šķirotavas produkcija izdzēsta!";
	header("Location: show_sorting_production");
	exit();

?>