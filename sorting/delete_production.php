<?php

	session_start();

/****************** Includes ******************/
	include_once "../includes/sorting_production.class.php";
	include_once "../includes/sorted_production.class.php";
	include_once "../includes/employees_sorted_productions.class.php";
	include_once "../includes/working_times.class.php";
	include_once "../includes/times.class.php";
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
	if(!SortingProduction::ExistsNonReservedProductionWithID($production_id))
	{
		header("Location: 404");
		exit();
	}

	//Returns all sorting_productions data
	$production = SortingProduction::GetSortingProductionData($sorting_production_id);

	//Object
	$sortingProduction = new SortingProduction();
	$sortedProduction = new SortedProduction();
	$employees_sorted_procutions = new EmployeeSortedProductions();
	$working_times = new WorkingTimes();
	$times = new Times();

	//Delete
	$sortingProduction->id = $production_id;
	$sortingProduction->Delete();

	//Deletes each sorted production data in foreach
	$all_sorted_productions = Manager::GetSortedProductionsByID($production_id);
	foreach($all_sorted_productions as $all_sorted_production)
	{
		$employees_sorted_procutions->DeleteAllSortedProductionEmployees($all_sorted_production['id']);

		$working_times->DeleteAllWorkingEmployees($all_sorted_production['id'], $production['date'], $production['datetime']);

		$times->DeleteAllNonWorkingEmployees($all_sorted_production['id'], $production['date'], $production['datetime']);
	}
	$sortedProduction->DeleteAllSortingProductionSortedProductions($production_id);


	$_SESSION['success'] = "Šķirotavas produkcija izdzēsta!";
	header("Location: show_sorting_production");
	exit();

?>