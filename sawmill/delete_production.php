<?php

	session_start();

/****************** Includes ******************/
	include_once "../includes/sawmill_production.class.php";
	include_once "../includes/sawmill_maintenance.class.php";
	include_once "../includes/employees_sawmill_productions.class.php";
	include_once "../includes/working_times.class.php";
	include_once "../includes/times.class.php";
/****************** Includes ******************/

	if(!isset($_SESSION['id']) && !isset($_SESSION['role']))	//Check if user is logged in
	{
		header("Location: 404");
		exit();
	}
	if(($_SESSION['role'] != "p") && ($_SESSION['role'] != "a") && ($_SESSION['active'] != 1))	//Check if user have permission to delete data
	{
		header("Location: 404");
		exit();
	}

	//Check if ID and invoice is set
	if(!isset($_GET['id']) || !isset($_GET['invoice']))
	{
		header("Location: 404");
		exit();
	}

	//Check if sawmill production in database exists
	$production_id = $_GET['id'];
	$production_invoice = $_GET['invoice'];
	if(!SawmillProduction::ExistsProductionWithInvoiceAndID($production_id, $production_invoice))
	{
		header("Location: 404");
		exit();
	}

	//Returns all sawmill_productions data
	$production = SawmillProduction::GetSawmillProductionData($production_id);

	//Object
	$sawmillProduction = new SawmillProduction();
	$sawmillProduction->id = $production_id;
	$sawmillProduction->Delete();

	$sawmillMaintenance = new SawmillMaintenance();
	$sawmillMaintenance->DeleteAllSawmillProductionMaintenances($production_id);

	$employees_sawmill_productions = new EmployeeSawmillProductions();
	$employees_sawmill_productions->DeleteAllSawmillProductionEmployees($production_id);

	$working_times = new WorkingTimes();
	$working_times->DeleteAllWorkingEmployees($production_invoice, $production['date'], $production['datetime']);

	$times = new Times();
	$times->DeleteAllNonWorkingEmployees($production_invoice, $production['date'], $production['datetime']);


	$_SESSION['success'] = "Zāģētavas produkcija izdzēsta!";
	header("Location: show_sawmill_production");
	exit();

?>