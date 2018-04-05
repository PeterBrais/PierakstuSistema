<?php

	session_start();

/****************** Includes ******************/
	include_once "../includes/validate.class.php";
	include_once "../includes/sorting_production.class.php";
	include_once "../includes/sorted_production.class.php";
	include_once "../includes/employee.class.php";
	include_once "../includes/employees_sorting_productions.class.php";
	include_once "../includes/working_times.class.php";
	include_once "../Includes/times.class.php";
/****************** Includes ******************/

	$inputs = ['date', 'time_from', 'time_to', 'invoice', 'thick', 'width', 'length', 'sawn_count', 'defect_count', 'sorted_types', 'sorted_count', 'sorted_thick', 'sorted_width', 'sorted_length', 'id', 'working_hours', 'nonworking'];

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

	$types = $_POST['sorted_types'];
	$sorted_counts = $_POST['sorted_count'];
	$sorted_thicknesses = $_POST['sorted_thick'];
	$sorted_widths = $_POST['sorted_width'];
	$sorted_lengths = $_POST['sorted_length'];

	$ids = $_POST['id'];
	$working_hours = $_POST['working_hours'];
	$nonworking = $_POST['nonworking'];

	//Error handlers
	//Check if fields are empty
	if(empty($date) || empty($time_from) || empty($time_to) || empty($invoice) || empty($thickness) || empty($width) || empty($length) || empty($sawn_count))
	{
		$_SESSION['error'] = "Lūdzu aizpildiet visus obligātos laukus!";
		$_SESSION['sorting_prod'] = $_POST;
		header("Location: add_sorting_production");
		exit();
	}

	//Checks if date is correct, like yyyy/mm/dd or yyyy-mm-dd
	if(!Validate::IsValidDate($date))
	{
		$_SESSION['date'] = "Lūdzu ievadiet korektu datumu, formā: gggg-mm-dd vai gggg/mm/dd!";
		$_SESSION['sorting_prod'] = $_POST;
		header("Location: add_sorting_production");
		exit();
	}

	//Check if sorting times are correct
	if(!Validate::IsValidTime($time_from))
	{
		$_SESSION['time'] = "Lūdzu ievadiet korektu laiku, formā: hh:mm!";
		$_SESSION['sorting_prod'] = $_POST;
		header("Location: add_sorting_production");
		exit();
	}
	if(!Validate::IsValidTime($time_to))
	{
		$_SESSION['time'] = "Lūdzu ievadiet korektu laiku, formā: hh:mm!";
		$_SESSION['sorting_prod'] = $_POST;
		header("Location: add_sorting_production");
		exit();
	}

	//Check if invoice is number
	if(!Validate::IsValidIntegerNumber($invoice))
	{
		$_SESSION['invoice'] = "Ievadītais pavadzīmes numurs ir neatbilstošs! Tas var sastāvēt tikai no cipariem!";
		$_SESSION['sorting_prod'] = $_POST;
		header("Location: add_sorting_production");
		exit();
	}

	//Check if sizes are numbers
	if(!Validate::IsValidIntegerNumber($thickness))
	{
		$_SESSION['sizes'] = "Biezums skaits drīkst sastāvēt tikai no cipariem!";
		$_SESSION['sorting_prod'] = $_POST;
		header("Location: add_sorting_production");
		exit();
	}
	if($thickness <= 0)
	{
		$_SESSION['sizes'] = "Biezums skaits drīkst sastāvēt tikai no cipariem!";
		$_SESSION['sorting_prod'] = $_POST;
		header("Location: add_sorting_production");
		exit();
	}
	if(!Validate::IsValidIntegerNumber($width))
	{
		$_SESSION['sizes'] = "Platums skaits drīkst sastāvēt tikai no cipariem!";
		$_SESSION['sorting_prod'] = $_POST;
		header("Location: add_sorting_production");
		exit();
	}
	if($width <= 0)
	{
		$_SESSION['sizes'] = "Platums skaits drīkst sastāvēt tikai no cipariem!";
		$_SESSION['sorting_prod'] = $_POST;
		header("Location: add_sorting_production");
		exit();
	}
	if(!Validate::IsValidIntegerNumber($length))
	{
		$_SESSION['sizes'] = "Garums skaits drīkst sastāvēt tikai no cipariem!";
		$_SESSION['sorting_prod'] = $_POST;
		header("Location: add_sorting_production");
		exit();
	}
	if($length <= 0)
	{
		$_SESSION['sizes'] = "Garums skaits drīkst sastāvēt tikai no cipariem!";
		$_SESSION['sorting_prod'] = $_POST;
		header("Location: add_sorting_production");
		exit();
	}

	//Check if sawn_count is number
	if(!Validate::IsValidIntegerNumber($sawn_count))
	{
		$_SESSION['sawn_count'] = "Skaits drīkst sastāvēt tikai no cipariem!";
		$_SESSION['sorting_prod'] = $_POST;
		header("Location: add_sorting_production");
		exit();
	}
	if($sawn_count <= 0)
	{
		$_SESSION['sawn_count'] = "Skaits drīkst sastāvēt tikai no cipariem!";
		$_SESSION['sorting_prod'] = $_POST;
		header("Location: add_sorting_production");
		exit();
	}

	//Check if defect_count is number
	if(!empty($defect_count))
	{
		if(!Validate::IsValidIntegerNumber($defect_count))
		{
			$_SESSION['defect_count'] = "Defektu skaits drīkst sastāvēt tikai no cipariem!";
			$_SESSION['sorting_prod'] = $_POST;
			header("Location: add_sorting_production");
			exit();
		}
	}
	else
	{
		$defect_count = NULL;
	}

	//Check sorted productions inputs
	for($i = 0; $i < count($sorted_counts); $i++)
	{
		if(empty($types[$i]) || empty($sorted_counts[$i]) || empty($sorted_thicknesses[$i]) || empty($sorted_widths[$i]) || empty($sorted_lengths[$i]))
		{
			$_SESSION['error'] = "Lūdzu aizpildiet visus obligātos laukus!";
			$_SESSION['sorting_prod'] = $_POST;
			header("Location: add_sorting_production");
			exit();
		}

		//Check if sorted production type is choosen
		if($types[$i] == "0")
		{
			$_SESSION['sorted_types'] = "Lūdzu izvēlieties Šķirošanas veidu!";
			$_SESSION['sorting_prod'] = $_POST;
			header("Location: add_sorting_production");
			exit();
		}

		if($types[$i] != "1" && $types[$i] != "2")
		{
			$_SESSION['error'] = "Lūdzu mēģiniet vēlreiz!";
			$_SESSION['sorting_prod'] = $_POST;
			header("Location: add_sorting_production");
			exit();
		}

		//Check if sorted_count is number
		if(!Validate::IsValidIntegerNumber($sorted_counts[$i]))
		{
			$_SESSION['sorted_count'] = "Skaits drīkst sastāvēt tikai no cipariem!";
			$_SESSION['sorting_prod'] = $_POST;
			header("Location: add_sorting_production");
			exit();
		}
		if($sorted_counts[$i] <= 0)
		{
			$_SESSION['sorted_count'] = "Skaits drīkst sastāvēt tikai no cipariem!";
			$_SESSION['sorting_prod'] = $_POST;
			header("Location: add_sorting_production");
			exit();
		}

		//Check if sorted sizes are number
		if(!Validate::IsValidIntegerNumber($sorted_thicknesses[$i]))
		{
			$_SESSION['sorted_sizes'] = "Biezums skaits drīkst sastāvēt tikai no cipariem!";
			$_SESSION['sorting_prod'] = $_POST;
			header("Location: add_sorting_production");
			exit();
		}
		if($sorted_thicknesses[$i] <= 0)
		{
			$_SESSION['sorted_sizes'] = "Biezums skaits drīkst sastāvēt tikai no cipariem!";
			$_SESSION['sorting_prod'] = $_POST;
			header("Location: add_sorting_production");
			exit();
		}
		if(!Validate::IsValidIntegerNumber($sorted_widths[$i]))
		{
			$_SESSION['sorted_sizes'] = "Platums skaits drīkst sastāvēt tikai no cipariem!";
			$_SESSION['sorting_prod'] = $_POST;
			header("Location: add_sorting_production");
			exit();
		}
		if($sorted_widths[$i] <= 0)
		{
			$_SESSION['sorted_sizes'] = "Platums skaits drīkst sastāvēt tikai no cipariem!";
			$_SESSION['sorting_prod'] = $_POST;
			header("Location: add_sorting_production");
			exit();
		}
		if(!Validate::IsValidIntegerNumber($sorted_lengths[$i]))
		{
			$_SESSION['sorted_sizes'] = "Garums skaits drīkst sastāvēt tikai no cipariem!";
			$_SESSION['sorting_prod'] = $_POST;
			header("Location: add_sorting_production");
			exit();
		}
		if($sorted_lengths[$i] <= 0)
		{
			$_SESSION['sorted_sizes'] = "Garums skaits drīkst sastāvēt tikai no cipariem!";
			$_SESSION['sorting_prod'] = $_POST;
			header("Location: add_sorting_production");
			exit();
		}
	}

	//Check if employee working times table is empty
	if(Validate::IsArrayEmpty($working_hours) && Validate::IsArrayEmpty($nonworking))
	{
		$_SESSION['error'] = "Lūdzu aizpildiet darbinieku tabulu!";
		$_SESSION['sorting_prod'] = $_POST;
		header("Location: add_sorting_production");
		exit();
	}

	for($i = 0; $i < count($ids); $i++)
	{
		if(!Employee::ExistsSortingEmployee($ids[$i])) //Checks if employee with this id exists
		{
			$_SESSION['error'] = "Radās kļūda, lūdzu mēģiniet vēlreiz!";
			$_SESSION['sorting_prod'] = $_POST;
			header("Location: add_sorting_production");
			exit();
		}

		if(!empty($working_hours[$i]) && !empty($nonworking[$i]))
		{
			$_SESSION['error'] = "Lūdzu aizpildiet tikai vienu darbinieka ievadlauku!";
			$_SESSION['sorting_prod'] = $_POST;
			header("Location: add_sorting_production");
			exit();
		}

		if(!empty($working_hours[$i]) && empty($nonworking[$i]))
		{
			//Check if working hour is number
			if(!Validate::IsValidHours($working_hours[$i]))
			{
				$_SESSION['error'] = "Darbinieka nostrādātās darba stundas drīkst sastāvēt tikai no cipariem!";
				$_SESSION['sorting_prod'] = $_POST;
				header("Location: add_sorting_production");
				exit();
			}
		}

		if(empty($working_hours[$i]) && !empty($nonworking[$i]))
		{
			//Check nonworking select values
			if($nonworking[$i] != "1" && $nonworking[$i] != "2" && $nonworking[$i] != "3")
			{
				$_SESSION['error'] = "Radās kļūda, lūdzu mēģiniet vēlreiz!";
				$_SESSION['sorting_prod'] = $_POST;
				header("Location: add_sorting_production");
				exit();
			}
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
		elseif($types[$i] == "2")
		{
			$sortedProduction->type = "G";
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

	//Saves data to tables: employees_sorting_productions, working_times, times
	$employees_sorting_procutions = new EmployeeSortingProductions();
	$working_times = new WorkingTimes();
	$times = new Times();

	for($i = 0; $i < count($ids); $i++)
	{
		if($working_hours[$i] != '' && $working_hours > 0)
		{
			$employees_sorting_procutions->employee_id = $ids[$i];
			$employees_sorting_procutions->sorting_id = $sortingProduction->id;
			$employees_sorting_procutions->Save();

			$working_times->date = $date;
			$working_times->invoice = $invoice;
			$working_times->working_hours = $working_hours[$i];
			$working_times->employee_id = $ids[$i];
			$working_times->Save();
		}
		else if($working_hours[$i] == '' && !empty($nonworking[$i]))
		{	
			$employees_sorting_procutions->employee_id = $ids[$i];
			$employees_sorting_procutions->sorting_id = $sortingProduction->id;
			$employees_sorting_procutions->Save();

			if($nonworking[$i] == "1")
			{
				$times->vacation = "A";
				$times->sick_leave = NULL;
				$times->nonattendance = NULL;
			}
			else if($nonworking[$i] == "2")
			{
				$times->vacation = NULL;
				$times->sick_leave = "S";
				$times->nonattendance = NULL;
			}
			else if($nonworking == "3")
			{
				$times->vacation = NULL;
				$times->sick_leave = NULL;
				$times->nonattendance = "N";
			}

			$times->date = $date;
			$times->invoice = $invoice;
			$times->pregnancy = NULL;
			$times->employee_id = $ids[$i];
			$times->Save();
		}
	}

	$_SESSION['success'] = "Šķirotavas produkcija pievienota veiksmīgi!";
	header("Location: add_sorting_production");
	exit();

?>