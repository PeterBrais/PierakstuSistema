<?php

	session_start();

/****************** Includes ******************/
	include_once "../includes/validate.class.php";
	include_once "../includes/sorting_production.class.php";
	include_once "../includes/sorted_production.class.php";
/****************** Includes ******************/

	$inputs = ['date', 'time_from', 'time_to', 'invoice', 'thick', 'width', 'length', 'sawn_count', 'defect_count', 'type', 'sorted_count', 'sorted_thick', 'sorted_width', 'sorted_length'];

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
	$defect_count = htmlspecialchars($_POST['defect_count']);

	$types = $_POST['type'];
	$sorted_counts = $_POST['sorted_count'];
	$sorted_thicknesses = $_POST['sorted_thick'];
	$sorted_widths = $_POST['sorted_width'];
	$sorted_lengths = $_POST['sorted_length'];


	//Error handlers
	//Check if fields are empty
	if(empty($date) || empty($time_from) || empty($time_to) || empty($invoice) || empty($thickness) || empty($width) || empty($length) || empty($sawn_count))
	{
		$_SESSION['error'] = "Lūdzu aizpildiet visus obligātos laukus!";
		header("Location: add_sorting_production");
		exit();
	}

	//Checks if date is correct, like yyyy/mm/dd or yyyy-mm-dd
	if(!Validate::IsValidDate($date))
	{
		$_SESSION['date'] = "Lūdzu ievadiet korektu datumu, formā: gggg-mm-dd vai gggg/mm/dd!";
		header("Location: add_sorting_production");
		exit();
	}

	//Check if sorting times are correct
	if(!Validate::IsValidTime($time_from))
	{
		$_SESSION['time'] = "Lūdzu ievadiet korektu laiku, formā: hh:mm!";
		header("Location: add_sorting_production");
		exit();
	}
	if(!Validate::IsValidTime($time_to))
	{
		$_SESSION['time'] = "Lūdzu ievadiet korektu laiku, formā: hh:mm!";
		header("Location: add_sorting_production");
		exit();
	}

	//Check if invoice is number
	if(!Validate::IsValidIntegerNumber($invoice))
	{
		$_SESSION['invoice'] = "Ievadītais pavadzīmes numurs ir neatbilstošs! Tas var sastāvēt tikai no cipariem!";
		header("Location: add_sorting_production");
		exit();
	}

	//Check if sizes are numbers
	if(!Validate::IsValidIntegerNumber($thickness))
	{
		$_SESSION['sizes'] = "Biezums skaits drīkst sastāvēt tikai no cipariem!";
		header("Location: add_sorting_production");
		exit();
	}
	if($thickness <= 0)
	{
		$_SESSION['sizes'] = "Biezums skaits drīkst sastāvēt tikai no cipariem!";
		header("Location: add_sorting_production");
		exit();
	}
	if(!Validate::IsValidIntegerNumber($width))
	{
		$_SESSION['sizes'] = "Platums skaits drīkst sastāvēt tikai no cipariem!";
		header("Location: add_sorting_production");
		exit();
	}
	if($width <= 0)
	{
		$_SESSION['sizes'] = "Platums skaits drīkst sastāvēt tikai no cipariem!";
		header("Location: add_sorting_production");
		exit();
	}
	if(!Validate::IsValidIntegerNumber($length))
	{
		$_SESSION['sizes'] = "Garums skaits drīkst sastāvēt tikai no cipariem!";
		header("Location: add_sorting_production");
		exit();
	}
	if($length <= 0)
	{
		$_SESSION['sizes'] = "Garums skaits drīkst sastāvēt tikai no cipariem!";
		header("Location: add_sorting_production");
		exit();
	}

	//Check if sawn_count is number
	if(!Validate::IsValidIntegerNumber($sawn_count))
	{
		$_SESSION['sawn_count'] = "Skaits drīkst sastāvēt tikai no cipariem!";
		header("Location: add_sorting_production");
		exit();
	}
	if($sawn_count <= 0)
	{
		$_SESSION['sawn_count'] = "Skaits drīkst sastāvēt tikai no cipariem!";
		header("Location: add_sorting_production");
		exit();
	}

	//Check if defect_count is number
	if(!empty($defect_count))
	{
		if(!Validate::IsValidIntegerNumber($defect_count))
		{
			$_SESSION['defect_count'] = "Defektu skaits drīkst sastāvēt tikai no cipariem!";
			header("Location: add_sorting_production");
			exit();
		}
	}
	else
	{
		$defect_count = NULL;
	}

	//Check sorted productions inputs
	for($i = 0; $i < count($types); $i++)
	{
		if(empty($types[$i]) || empty($sorted_counts[$i]) || empty($sorted_thicknesses[$i]) || empty($sorted_widths[$i]) || empty($sorted_lengths[$i]))
		{
			$_SESSION['error'] = "Lūdzu aizpildiet visus obligātos laukus!";
			header("Location: add_sorting_production");
			exit();
		}

		//Chekc if sorted production type is chosen
		if($types[$i] =! "1" && $types[$i] != "2")
		{
			$_SESSION['error'] = "Lūdzu mēģiniet vēlreiz!";
			header("Location: add_sorting_production");
			exit();
		}

		//Check if sorted_count is number
		if(!Validate::IsValidIntegerNumber($sorted_counts[$i]))
		{
			$_SESSION['sorted_count'] = "Skaits drīkst sastāvēt tikai no cipariem!";
			header("Location: add_sorting_production");
			exit();
		}
		if($sorted_counts[$i] <= 0)
		{
			$_SESSION['sorted_count'] = "Skaits drīkst sastāvēt tikai no cipariem!";
			header("Location: add_sorting_production");
			exit();
		}

		//Check if sorted sizes are number
		if(!Validate::IsValidIntegerNumber($sorted_thicknesses[$i]))
		{
			$_SESSION['sorted_sizes'] = "Biezums skaits drīkst sastāvēt tikai no cipariem!";
			header("Location: add_sorting_production");
			exit();
		}
		if($sorted_thicknesses[$i] <= 0)
		{
			$_SESSION['sorted_sizes'] = "Biezums skaits drīkst sastāvēt tikai no cipariem!";
			header("Location: add_sorting_production");
			exit();
		}
		if(!Validate::IsValidIntegerNumber($sorted_widths[$i]))
		{
			$_SESSION['sorted_sizes'] = "Platums skaits drīkst sastāvēt tikai no cipariem!";
			header("Location: add_sorting_production");
			exit();
		}
		if($sorted_widths[$i] <= 0)
		{
			$_SESSION['sorted_sizes'] = "Platums skaits drīkst sastāvēt tikai no cipariem!";
			header("Location: add_sorting_production");
			exit();
		}
		if(!Validate::IsValidIntegerNumber($sorted_lengths[$i]))
		{
			$_SESSION['sorted_sizes'] = "Garums skaits drīkst sastāvēt tikai no cipariem!";
			header("Location: add_sorting_production");
			exit();
		}
		if($sorted_lengths[$i] <= 0)
		{
			$_SESSION['sorted_sizes'] = "Garums skaits drīkst sastāvēt tikai no cipariem!";
			header("Location: add_sorting_production");
			exit();
		}
	}

	$sawn_capacity = round((($thickness * $width * $length)/1000000000)*$sawn_count, 3);

	//Saves sorting production
	$sortingProduction = new SortingProduction();
	$sortingProduction->date = $date;
	$sortingProduction->time_from = $time_from;
	$sortingProduction->time_to = $time_to;
	$sortingProduction->invoice = $invoice;
	$sortingProduction->thickness = $thickness;
	$sortingProduction->width = $width;
	$sortingProduction->length = $length;
	$sortingProduction->count = $sawn_count;
	$sortingProduction->capacity = $sawn_capacity;
	$sortingProduction->defect_count = $defect_count;
	$sortingProduction->Save();

	//Saves sorted productions
	$sortedProduction = new SortedProduction();
	for($i = 0; $i < count($sorted_counts); $i++)
	{
		$sorted_capacity[$i] = round((($sorted_thicknesses[$i]*$sorted_widths[$i]*$sorted_lengths[$i])/1000000000)*$sorted_counts[$i], 3);
		$sorted_capacity_piece[$i] = round(($sorted_thicknesses[$i]*$sorted_widths[$i]*$sorted_lengths[$i])/1000000000, 5);

		if($types[$i] == "1")
		{
			$sortedProduction->type = "S";
		}
		else if($types[$i] == "2")
		{
			$sortedProduction->type = "G";
		}
		else
		{
			$_SESSION['error'] = "Lūdzu mēģiniet vēlreiz!";
			header("Location: add_sorting_production");
			exit();
		}
		$sortedProduction->count = $sorted_counts[$i];
		$sortedProduction->thickness = $sorted_thicknesses[$i];
		$sortedProduction->width = $sorted_widths[$i];
		$sortedProduction->length = $sorted_lengths[$i];
		$sortedProduction->capacity = $sorted_capacity[$i];
		$sortedProduction->capacity_per_piece = $sorted_capacity_piece[$i];
		$sortedProduction->sorting_id = $sortingProduction->id;
		$sortedProduction->Save();
	}

	$_SESSION['success'] = "Šķirotavas produkcija pievienota veiksmīgi!";
	header("Location: add_sorting_production");
	exit();

?>