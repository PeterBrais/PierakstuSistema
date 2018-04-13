<?php

	session_start();

/****************** Includes ******************/
	include_once "../includes/validate.class.php";
	include_once "../includes/sorting_production.class.php";
	include_once "../includes/sorted_production.class.php";
	include_once "../includes/employee.class.php";
	include_once "../includes/employees_sorted_productions.class.php";
	include_once "../includes/working_times.class.php";
	include_once "../includes/times.class.php";
	include_once "../includes/manager.class.php";
/****************** Includes ******************/

	$this_date = date('Y-m'); //This year and month
	$employees_count = Manager::GetSortingEmployees($this_date);
	$employees_occasion = $employees_count = count($employees_count); //Gets count of sorting employees

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

		if($types[$i] != "1" && $types[$i] != "2" && $types[$i] != "3")
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

	//Check if array from one one value to other is filled
	for($i = 0; $i < count($sorted_counts); $i++)
	{
		$j = $i*$employees_count;

		if($types[$i] == "1" || $types[$i] == "2")
		{
			if(Validate::IsArrayEmptyFromTo($working_hours, $j, $employees_occasion) && Validate::IsArrayEmptyFromTo($nonworking, $j, $employees_occasion))
			{
				$_SESSION['error'] = "Lūdzu aizpildiet darbinieku tabulu!";
				$_SESSION['sorting_prod'] = $_POST;
				header("Location: add_sorting_production");
				exit();
			}

			for($k = $i*$employees_count; $k < $employees_occasion; $k++)
			{
				if(!Employee::ExistsSortingEmployee($ids[$k])) //Checks if employee with this id exists
				{
					$_SESSION['error'] = "Radās kļūda, lūdzu mēģiniet vēlreiz!";
					$_SESSION['sorting_prod'] = $_POST;
					header("Location: add_sorting_production");
					exit();
				}

				if(!empty($working_hours[$k]) && !empty($nonworking[$k]))
				{
					$_SESSION['error'] = "Lūdzu aizpildiet tikai vienu darbinieka ievadlauku!";
					$_SESSION['sorting_prod'] = $_POST;
					header("Location: add_sorting_production");
					exit();
				}

				if(!empty($working_hours[$k]) && empty($nonworking[$k]))
				{
					//Check if working hour is number
					if(!Validate::IsValidHours($working_hours[$k]))
					{
						$_SESSION['error'] = "Darbinieka nostrādātās darba stundas drīkst sastāvēt tikai no cipariem!";
						$_SESSION['sorting_prod'] = $_POST;
						header("Location: add_sorting_production");
						exit();
					}
				}

				if(empty($working_hours[$k]) && !empty($nonworking[$k]))
				{
					//Check nonworking select values
					if($nonworking[$k] != "1" && $nonworking[$k] != "2" && $nonworking[$k] != "3")
					{
						$_SESSION['error'] = "Radās kļūda, lūdzu mēģiniet vēlreiz!";
						$_SESSION['sorting_prod'] = $_POST;
						header("Location: add_sorting_production");
						exit();
					}
				}
			}
		}
		else if($types[$i] == "3")
		{
			for($k = $i*$employees_count; $k < $employees_occasion; $k++)
			{
				$working_hours[$k] = NULL;
				$nonworking[$k] = NULL;
			}
		}
		else
		{
			$_SESSION['error'] = "Radās kļūda, lūdzu mēģiniet vēlreiz!";
			$_SESSION['sorting_prod'] = $_POST;
			header("Location: add_sorting_production");
			exit();
		}

		$employees_occasion = $employees_count + $employees_occasion;
	}

	$employees_occasion = $employees_count;


	$sawn_capacity = round((($thickness * $width * $length)/1000000000)*$sawn_count, 3);

	//Get timestamp
	$timestamp = new DateTime('now', new DateTimezone('Europe/Riga'));
	$timestamp = $timestamp->format('Y-m-d H:i:s');

	//Saves sorting production
	$sortingProduction = new SortingProduction();
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
	$sortingProduction->defect_count = $defect_count;
	$sortingProduction->reserved = 0;
	$sortingProduction->Save();

	//Saves sorted productions
	$sortedProduction = new SortedProduction();
	for($i = 0; $i < count($sorted_counts); $i++)
	{
		$sorted_capacity[$i] = round((($sorted_thicknesses[$i]*$sorted_widths[$i]*$sorted_lengths[$i])/1000000000)*$sorted_counts[$i], 3);
		$sorted_capacity_piece[$i] = round(($sorted_thicknesses[$i]*$sorted_widths[$i]*$sorted_lengths[$i])/1000000000, 5);

		if($types[$i] == "1" || $types[$i] == "2")
		{
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


			//Saves data to tables: employees_sorting_productions, working_times, times 
			$employees_sorted_procutions = new EmployeeSortedProductions();
			$working_times = new WorkingTimes();
			$times = new Times();
			for($j = $i*$employees_count; $j < $employees_occasion; $j++)
			{
				if($working_hours[$j] != '' && $working_hours > 0)
				{
					$employees_sorted_procutions->employee_id = $ids[$j];
					$employees_sorted_procutions->sorted_id = $sortedProduction->id;
					$employees_sorted_procutions->Save();

					$working_times->date = $date;
					$working_times->datetime = $timestamp;
					$working_times->invoice = $sortedProduction->id;
					$working_times->working_hours = $working_hours[$j];
					$working_times->employee_id = $ids[$j];
					$working_times->Save();
				}
				else if($working_hours[$j] == '' && !empty($nonworking[$j]))
				{	
					$employees_sorted_procutions->employee_id = $ids[$j];
					$employees_sorted_procutions->sorted_id = $sortedProduction->id;
					$employees_sorted_procutions->Save();

					if($nonworking[$j] == "1")
					{
						$times->vacation = "A";
						$times->sick_leave = NULL;
						$times->nonattendance = NULL;
					}
					else if($nonworking[$j] == "2")
					{
						$times->vacation = NULL;
						$times->sick_leave = "S";
						$times->nonattendance = NULL;
					}
					else if($nonworking[$j] == "3")
					{
						$times->vacation = NULL;
						$times->sick_leave = NULL;
						$times->nonattendance = "N";
					}

					$times->date = $date;
					$times->datetime = $timestamp;
					$times->invoice = $sortedProduction->id;
					$times->pregnancy = NULL;
					$times->employee_id = $ids[$j];
					$times->Save();
				}
			}
		}
		else if($types[$i] == "3")
		{
			if($types[$i] == "3")
			{
				$sortedProduction->type = "W";
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


		$employees_occasion = $employees_count + $employees_occasion;
	}
	

	$_SESSION['success'] = "Šķirotavas produkcija pievienota veiksmīgi!";
	header("Location: show_sorting_production");
	exit();

?>