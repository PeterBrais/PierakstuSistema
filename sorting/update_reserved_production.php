<?php

	session_start();

/****************** Includes ******************/
	include_once "../includes/validate.class.php";
	include_once "../includes/sorting_production.class.php";
/****************** Includes ******************/

	//Check if sorting production in database exists
	if(!SortingProduction::ExistsReservedProductionWithID($_POST['sorting_production_id']))
	{
		header("Location: 404");
		exit();
	}

	$inputs = ['sorting_production_id', 'date', 'time_from', 'time_to', 'invoice', 'thick', 'width', 'length', 'sawn_count'];

	foreach($inputs as $input)
	{
		if(!isset($_POST[$input]))
		{
			header("Location: /");
			exit();
		}
	}

	//Sets variables
	$sorting_production_id = htmlspecialchars($_POST['sorting_production_id']);
	$date = htmlspecialchars($_POST['date']);
	$time_from = htmlspecialchars($_POST['time_from']);
	$time_to = htmlspecialchars($_POST['time_to']);
	$invoice = htmlspecialchars($_POST['invoice']);
	$thickness = htmlspecialchars($_POST['thick']);
	$width = htmlspecialchars($_POST['width']);
	$length = htmlspecialchars($_POST['length']);
	$sawn_count = htmlspecialchars($_POST['sawn_count']);

	//Error handlers
	//Check if fields are empty
	if(empty($sorting_production_id) || empty($date) || empty($time_from) || empty($time_to) || empty($invoice) || empty($thickness) || empty($width) || empty($length) || empty($sawn_count))
	{
		$_SESSION['error'] = "Lūdzu aizpildiet visus obligātos laukus!";
		$_SESSION['edit_sorting_prod'] = $_POST;
		header("Location: edit_reserved_production?id=$sorting_production_id");
		exit();
	}

	//Checks if date is correct, like yyyy/mm/dd or yyyy-mm-dd
	if(!Validate::IsValidDate($date))
	{
		$_SESSION['date'] = "Lūdzu ievadiet korektu datumu, formā: gggg-mm-dd vai gggg/mm/dd!";
		$_SESSION['edit_sorting_prod'] = $_POST;
		header("Location: edit_reserved_production?id=$sorting_production_id");
		exit();
	}

	//Check if sorting times are correct
	if(!Validate::IsValidTime($time_from))
	{
		$_SESSION['time'] = "Lūdzu ievadiet korektu laiku, formā: hh:mm!";
		$_SESSION['edit_sorting_prod'] = $_POST;
		header("Location: edit_reserved_production?id=$sorting_production_id");
		exit();
	}
	if(!Validate::IsValidTime($time_to))
	{
		$_SESSION['time'] = "Lūdzu ievadiet korektu laiku, formā: hh:mm!";
		$_SESSION['edit_sorting_prod'] = $_POST;
		header("Location: edit_reserved_production?id=$sorting_production_id");
		exit();
	}

	//Check if invoice is number
	if(!Validate::IsValidIntegerNumber($invoice))
	{
		$_SESSION['invoice'] = "Ievadītais pavadzīmes numurs ir neatbilstošs! Tas var sastāvēt tikai no cipariem!";
		$_SESSION['edit_sorting_prod'] = $_POST;
		header("Location: edit_reserved_production?id=$sorting_production_id");
		exit();
	}

	//Check if sizes are numbers
	if(!Validate::IsValidIntegerNumber($thickness))
	{
		$_SESSION['sizes'] = "Biezums skaits drīkst sastāvēt tikai no cipariem!";
		$_SESSION['edit_sorting_prod'] = $_POST;
		header("Location: edit_reserved_production?id=$sorting_production_id");
		exit();
	}
	if($thickness <= 0)
	{
		$_SESSION['sizes'] = "Biezums skaits drīkst sastāvēt tikai no cipariem!";
		$_SESSION['edit_sorting_prod'] = $_POST;
		header("Location: edit_reserved_production?id=$sorting_production_id");
		exit();
	}
	if(!Validate::IsValidIntegerNumber($width))
	{
		$_SESSION['sizes'] = "Platums skaits drīkst sastāvēt tikai no cipariem!";
		$_SESSION['edit_sorting_prod'] = $_POST;
		header("Location: edit_reserved_production?id=$sorting_production_id");
		exit();
	}
	if($width <= 0)
	{
		$_SESSION['sizes'] = "Platums skaits drīkst sastāvēt tikai no cipariem!";
		$_SESSION['edit_sorting_prod'] = $_POST;
		header("Location: edit_reserved_production?id=$sorting_production_id");
		exit();
	}
	if(!Validate::IsValidIntegerNumber($length))
	{
		$_SESSION['sizes'] = "Garums skaits drīkst sastāvēt tikai no cipariem!";
		$_SESSION['edit_sorting_prod'] = $_POST;
		header("Location: edit_reserved_production?id=$sorting_production_id");
		exit();
	}
	if($length <= 0)
	{
		$_SESSION['sizes'] = "Garums skaits drīkst sastāvēt tikai no cipariem!";
		$_SESSION['edit_sorting_prod'] = $_POST;
		header("Location: edit_reserved_production?id=$sorting_production_id");
		exit();
	}

	//Check if sawn_count is number
	if(!Validate::IsValidIntegerNumber($sawn_count))
	{
		$_SESSION['sawn_count'] = "Skaits drīkst sastāvēt tikai no cipariem!";
		$_SESSION['edit_sorting_prod'] = $_POST;
		header("Location: edit_reserved_production?id=$sorting_production_id");
		exit();
	}
	if($sawn_count <= 0)
	{
		$_SESSION['sawn_count'] = "Skaits drīkst sastāvēt tikai no cipariem!";
		$_SESSION['edit_sorting_prod'] = $_POST;
		header("Location: edit_reserved_production?id=$sorting_production_id");
		exit();
	}

	$sawn_capacity = round((($thickness * $width * $length)/1000000000)*$sawn_count, 3);

	//Object
	$sortingProduction = new SortingProduction();

	//Saves updated sorting production data
	$sortingProduction->date = $date;
	$sortingProduction->time_from = $time_from;
	$sortingProduction->time_to = $time_to;
	$sortingProduction->invoice = $invoice;
	$sortingProduction->thickness = $thickness;
	$sortingProduction->width = $width;
	$sortingProduction->length = $length;
	$sortingProduction->count = $sawn_count;
	$sortingProduction->capacity = $sawn_capacity;
	$sortingProduction->defect_count = 0;
	$sortingProduction->id = $sorting_production_id;
	$sortingProduction->Update();


	$_SESSION['success'] = "Šķirotavas rezervētā produkcija pievienota veiksmīgi!";
	header("Location: show_sorting_production");
	exit();

?>