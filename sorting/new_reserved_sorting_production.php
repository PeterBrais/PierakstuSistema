<?php

	session_start();

/****************** Includes ******************/
	include_once "../includes/validate.class.php";
	include_once "../includes/sorting_production.class.php";
/****************** Includes ******************/

	$inputs = ['date', 'time_from', 'time_to', 'invoice', 'thick', 'width', 'length', 'sawn_count'];

	foreach($inputs as $input)
	{
		if(!isset($_POST[$input]))
		{
			header("Location: /");
			exit();
		}
	}

	//Sets variables
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
	if(empty($date) || empty($time_from) || empty($time_to) || empty($invoice) || empty($thickness) || empty($width) || empty($length) || empty($sawn_count))
	{
		$_SESSION['error'] = "Lūdzu aizpildiet visus obligātos laukus!";
		$_SESSION['reserved_sorting_prod'] = $_POST;
		header("Location: add_reserved_sorting_production");
		exit();
	}

	//Checks if date is correct, like yyyy/mm/dd or yyyy-mm-dd
	if(!Validate::IsValidDate($date))
	{
		$_SESSION['date'] = "Lūdzu ievadiet korektu datumu, formā: gggg-mm-dd vai gggg/mm/dd!";
		$_SESSION['reserved_sorting_prod'] = $_POST;
		header("Location: add_reserved_sorting_production");
		exit();
	}

	//Check if sorting times are correct
	if(!Validate::IsValidTime($time_from))
	{
		$_SESSION['time'] = "Lūdzu ievadiet korektu laiku, formā: hh:mm!";
		$_SESSION['reserved_sorting_prod'] = $_POST;
		header("Location: add_reserved_sorting_production");
		exit();
	}
	if(!Validate::IsValidTime($time_to))
	{
		$_SESSION['time'] = "Lūdzu ievadiet korektu laiku, formā: hh:mm!";
		$_SESSION['reserved_sorting_prod'] = $_POST;
		header("Location: add_reserved_sorting_production");
		exit();
	}

	//Check if invoice is number
	if(!Validate::IsValidIntegerNumber($invoice))
	{
		$_SESSION['invoice'] = "Ievadītais pavadzīmes numurs ir neatbilstošs! Tas var sastāvēt tikai no cipariem!";
		$_SESSION['reserved_sorting_prod'] = $_POST;
		header("Location: add_reserved_sorting_production");
		exit();
	}

	//Check if sizes are numbers
	if(!Validate::IsValidIntegerNumber($thickness))
	{
		$_SESSION['sizes'] = "Biezums skaits drīkst sastāvēt tikai no cipariem!";
		$_SESSION['reserved_sorting_prod'] = $_POST;
		header("Location: add_reserved_sorting_production");
		exit();
	}
	if($thickness <= 0)
	{
		$_SESSION['sizes'] = "Biezums skaits drīkst sastāvēt tikai no cipariem!";
		$_SESSION['reserved_sorting_prod'] = $_POST;
		header("Location: add_reserved_sorting_production");
		exit();
	}
	if(!Validate::IsValidIntegerNumber($width))
	{
		$_SESSION['sizes'] = "Platums skaits drīkst sastāvēt tikai no cipariem!";
		$_SESSION['reserved_sorting_prod'] = $_POST;
		header("Location: add_reserved_sorting_production");
		exit();
	}
	if($width <= 0)
	{
		$_SESSION['sizes'] = "Platums skaits drīkst sastāvēt tikai no cipariem!";
		$_SESSION['reserved_sorting_prod'] = $_POST;
		header("Location: add_reserved_sorting_production");
		exit();
	}
	if(!Validate::IsValidIntegerNumber($length))
	{
		$_SESSION['sizes'] = "Garums skaits drīkst sastāvēt tikai no cipariem!";
		$_SESSION['reserved_sorting_prod'] = $_POST;
		header("Location: add_reserved_sorting_production");
		exit();
	}
	if($length <= 0)
	{
		$_SESSION['sizes'] = "Garums skaits drīkst sastāvēt tikai no cipariem!";
		$_SESSION['reserved_sorting_prod'] = $_POST;
		header("Location: add_reserved_sorting_production");
		exit();
	}

	//Check if sawn_count is number
	if(!Validate::IsValidIntegerNumber($sawn_count))
	{
		$_SESSION['sawn_count'] = "Skaits drīkst sastāvēt tikai no cipariem!";
		$_SESSION['reserved_sorting_prod'] = $_POST;
		header("Location: add_reserved_sorting_production");
		exit();
	}
	if($sawn_count <= 0)
	{
		$_SESSION['sawn_count'] = "Skaits drīkst sastāvēt tikai no cipariem!";
		$_SESSION['reserved_sorting_prod'] = $_POST;
		header("Location: add_reserved_sorting_production");
		exit();
	}

	$sawn_capacity = round((($thickness * $width * $length)/1000000000)*$sawn_count, 3);

	//Get timestamp
	$timestamp = new DateTime('now', new DateTimezone('Europe/Riga'));
	$timestamp = $timestamp->format('Y-m-d H:i:s');

	//Object
	$sortingProduction = new SortingProduction();

	//Saves reserved sorting production
	$sortingProduction->date = $date;
	$sortingProduction->datetime = $timestamp;
	$sortingProduction->time_from = $time_from;
	$sortingProduction->time_to = $time_to;
	$sortingProduction->invoice = $invoice;
	$sortingProduction->thickness = $thickness;
	$sortingProduction->width = $width;
	$sortingProduction->length = $length;
	$sortingProduction->count = $sawn_count;
	$sortingProduction->capacity = $sawn_capacity;
	$sortingProduction->defect_count = 0;
	$sortingProduction->reserved = 1;
	$sortingProduction->Save();


	$_SESSION['success'] = "Šķirotavas rezervētā produkcija pievienota veiksmīgi!";
	header("Location: show_sorting_production");
	exit();

?>