<?php

	session_start();

/****************** Includes ******************/
	include_once "../includes/validate.class.php";
	include_once "../includes/sawmill_production.class.php";
	include_once "../includes/sawmill_maintenance.class.php";
	include_once "../includes/employees_sawmill_productions.class.php";
	include_once "../includes/manager.class.php";
	include_once "../includes/working_times.class.php";
	include_once "../includes/times.class.php";
	include_once "../includes/beam_size.class.php";
/****************** Includes ******************/

	//Check if sawmill production in database exists
	if(!SawmillProduction::ExistsProductionWithInvoiceAndID($_POST['sawmill_production_id'], $_POST['sawmill_production_invoice']))
	{
		header("Location: 404");
		exit();
	}

	$inputs = ['sawmill_production_id', 'sawmill_production_invoice', 'date', 'time_from', 'time_to', 'invoice', 'beam_count', 'sizes', 'lumber_count', 'lumber_capacity', 'note', 'maintenance_times', 'maintenance_notes', 'shifts'];

	foreach($inputs as $input)
	{
		if(!isset($_POST[$input]))
		{
			header("Location: /");
			exit();
		}
	}

	//Sets variables
	$sawmill_production_id = htmlspecialchars($_POST['sawmill_production_id']);
	$sawmill_production_invoice = htmlspecialchars($_POST['sawmill_production_invoice']);
	$date = htmlspecialchars($_POST['date']);
	$time_from = htmlspecialchars($_POST['time_from']);
	$time_to = htmlspecialchars($_POST['time_to']);
	$invoice = htmlspecialchars($_POST['invoice']);

	if(empty($_POST['note']))
	{
		$note = NULL;
	}
	else
	{
		$note = htmlspecialchars($_POST['note']);
	}

	$beam_count = htmlspecialchars($_POST['beam_count']);
	$beam_size = htmlspecialchars($_POST['sizes']);

	$lumber_count = htmlspecialchars($_POST['lumber_count']);
	$lumber_capacity = htmlspecialchars($_POST['lumber_capacity']);

	$maintenance_times = $_POST['maintenance_times'];
	$maintenance_notes = $_POST['maintenance_notes'];

	$shift = htmlspecialchars($_POST['shifts']);

	//Error handlers
	//Check if fields are empty
	if(empty($sawmill_production_id) || empty($sawmill_production_invoice) || empty($date) || empty($time_from) || empty($time_to) || empty($invoice) || empty($beam_count) || empty($lumber_count) || empty($lumber_capacity) || empty($shift))
	{
		$_SESSION['error'] = "Lūdzu aizpildiet visus obligātos laukus!";
		$_SESSION['edit_sawmill_prod'] = $_POST;
		header("Location: edit_production?id=$sawmill_production_id");
		exit();
	}

	//Checks if shift number exists in database
	if(!Manager::ExistsShift($shift))
	{
		$_SESSION['error'] = "Radās kļūda, lūdzu mēģiniet vēlreiz!";
		$_SESSION['edit_sawmill_prod'] = $_POST;
		header("Location: edit_production?id=$sawmill_production_id");
		exit();
	}

	//Checks if employees input fields are set
	$inputs = ['id', 'working_hours', 'nonworking'];
	foreach($inputs as $input)
	{
		if(!isset($_POST[$input]))
		{
			header("Location: /");
			exit();
		}
	}

	//Sets variables
	$ids = $_POST['id'];
	$working_hours = $_POST['working_hours'];
	$nonworking = $_POST['nonworking'];

	//Checks if date is correct, like yyyy/mm/dd or yyyy-mm-dd
	if(!Validate::IsValidDate($date))
	{
		$_SESSION['date'] = "Lūdzu ievadiet korektu datumu, formā: gggg-mm-dd vai gggg/mm/dd!";
		$_SESSION['edit_sawmill_prod'] = $_POST;
		header("Location: edit_production?id=$sawmill_production_id");
		exit();
	}

	//Check if production times are correct
	if(!Validate::IsValidTime($time_from))
	{
		$_SESSION['time'] = "Lūdzu ievadiet korektu laiku, formā: hh:mm!";
		$_SESSION['edit_sawmill_prod'] = $_POST;
		header("Location: edit_production?id=$sawmill_production_id");
		exit();
	}
	if(!Validate::IsValidTime($time_to))
	{
		$_SESSION['time'] = "Lūdzu ievadiet korektu laiku, formā: hh:mm!";
		$_SESSION['edit_sawmill_prod'] = $_POST;
		header("Location: edit_production?id=$sawmill_production_id");
		exit();
	}

	//Check if invoice is number
	if(!Validate::IsValidIntegerNumber($invoice))
	{
		$_SESSION['invoice'] = "Ievadītais pavadzīmes numurs ir neatbilstošs! Tas var sastāvēt tikai no cipariem!";
		$_SESSION['edit_sawmill_prod'] = $_POST;
		header("Location: edit_production?id=$sawmill_production_id");
		exit();
	}

	//Checks if entered invoice number already exists
	if(SawmillProduction::CurrentInvoiceExists($invoice, $sawmill_production_id))	
	{
		$_SESSION['invoice'] = "Pavadzīme ar šādu numuru jau eksistē!";
		$_SESSION['edit_sawmill_prod'] = $_POST;
		header("Location: edit_production?id=$sawmill_production_id");
		exit();
	}

	//Check if beam_count is number
	if(!Validate::IsValidIntegerNumber($beam_count))
	{
		$_SESSION['beam_count'] = "Apaļkoku skaits drīkst sastāvēt tikai no cipariem!";
		$_SESSION['edit_sawmill_prod'] = $_POST;
		header("Location: edit_production?id=$sawmill_production_id");
		exit();
	}

	//Check if beam_size is sellected
	if(empty($beam_size))
	{
		$_SESSION['beam_size'] = "Lūdzu izvēlieties kubatūras izmēru";
		$_SESSION['edit_sawmill_prod'] = $_POST;
		header("Location: edit_production?id=$sawmill_production_id");
		exit();
	}
	else if(!BeamSize::ExistsId($beam_size)) //Checks if position with this id exists
	{
		$_SESSION['error'] = "Radās kļūda, lūdzu mēģiniet vēlreiz!";
		$_SESSION['edit_sawmill_prod'] = $_POST;
		header("Location: edit_production?id=$sawmill_production_id");
		exit();
	}

	//Check if lumber_count is number
	if(!Validate::IsValidIntegerNumber($lumber_count))
	{
		$_SESSION['lumber_count'] = "Zāģmatariālu skaits drīkst sastāvēt tikai no cipariem!";
		$_SESSION['edit_sawmill_prod'] = $_POST;
		header("Location: edit_production?id=$sawmill_production_id");
		exit();
	}

	//If user typed number with comma, it changes it to dot
	$lumber_capacity = str_replace(',', '.', $lumber_capacity); 

	//Check if lumber_capacity is float number with comma or dot
	if(!Validate::IsValidFloatNumber($lumber_capacity))
	{
		$_SESSION['lumber_capacity'] = "Zāģmatariālu tilpums drīkst saturēt tikai ciparus ar komatu! (Maksimums 3 cipari aiz komata)";
		$_SESSION['edit_sawmill_prod'] = $_POST;
		header("Location: edit_production?id=$sawmill_production_id");
		exit();
	}
	if($lumber_capacity <= 0)
	{
		$_SESSION['lumber_capacity'] = "Zāģmatariālu tilpums drīkst saturēt tikai ciparus ar komatu! (Maksimums 3 cipari aiz komata)";
		$_SESSION['edit_sawmill_prod'] = $_POST;
		header("Location: edit_production?id=$sawmill_production_id");
		exit();
	}

	//Checks if note is filled, then matches its content with regular expression
	if(!empty($note))
	{
		if(!Validate::IsValidTextLength($note))
		{
			$_SESSION['note'] = "Citas piezīmes jābūt garumā no 3 simboliem līdz 255 simboliem!";
			$_SESSION['edit_sawmill_prod'] = $_POST;
			header("Location: edit_production?id=$sawmill_production_id");
			exit();
		}

		if(!Validate::IsValidText($note))
		{
			$_SESSION['note'] = "Citas piezīmes drīkst saturēt tikai latīņu burtus, ciparus un speciālos simbolus!";
			$_SESSION['edit_sawmill_prod'] = $_POST;
			header("Location: edit_production?id=$sawmill_production_id");
			exit();
		}
	}

	//Check maintenances
	for($i=0; $i < count($maintenance_times); $i++)
	{
		if(!empty($maintenance_times[$i]) && !empty($maintenance_notes[$i]))
		{
			if(!Validate::IsValidIntegerNumber($maintenance_times[$i]))
			{
				$_SESSION['maintenance'] = "Remonta laiks drīkst sastāvēt tikai no cipariem!";
				$_SESSION['edit_sawmill_prod'] = $_POST;
				header("Location: edit_production?id=$sawmill_production_id");
				exit();
			}

			if(!Validate::IsValidTextLength($maintenance_notes[$i]))
			{
				$_SESSION['maintenance'] = "Piezīme jābūt garumā no 3 simboliem līdz 255 simboliem!";
				$_SESSION['edit_sawmill_prod'] = $_POST;
				header("Location: edit_production?id=$sawmill_production_id");
				exit();
			}

			if(!Validate::IsValidText($maintenance_notes[$i]))
			{
				$_SESSION['maintenance'] = "Piezīme drīkst saturēt tikai latīņu burtus, ciparus un speciālos simbolus!";
				$_SESSION['edit_sawmill_prod'] = $_POST;
				header("Location: edit_production?id=$sawmill_production_id");
				exit();
			}
		}
		else if((empty($maintenance_times[$i]) && !empty($maintenance_notes[$i])) || (!empty($maintenance_times[$i]) && empty($maintenance_notes[$i]))) //One or other is filled
		{
			$_SESSION['maintenance'] = "Lūdzu ievadiet remonta laiku un piezīmi!";
			$_SESSION['edit_sawmill_prod'] = $_POST;
			header("Location: edit_production?id=$sawmill_production_id");
			exit();
		}
	}

	//Checks employees working fields, only one filled is allowed
	for($i = 0; $i < count($working_hours); $i++) 
	{
		if(empty($working_hours[$i]) && empty($nonworking[$i]))
		{
			$_SESSION['shift'] = "Lūdzu aizpildiet darbinieku tabulu!";
			$_SESSION['edit_sawmill_prod'] = $_POST;
			header("Location: edit_production?id=$sawmill_production_id");
			exit();
		}
		else if(!empty($working_hours[$i]) && empty($nonworking[$i]))
		{
			//Check if working hour is number
			if(!Validate::IsValidHours($working_hours[$i]))
			{
				$_SESSION['shift'] = "Nostrādātās darba stundas drīkst sastāvēt tikai no cipariem!";
				$_SESSION['edit_sawmill_prod'] = $_POST;
				header("Location: edit_production?id=$sawmill_production_id");
				exit();
			}
		}
		else if(empty($working_hours[$i]) && !empty($nonworking[$i]))
		{
			//Check nonworking select values
			if($nonworking[$i] != "1" && $nonworking[$i] != "2" && $nonworking[$i] != "3")
			{
				$_SESSION['error'] = "Lūdzu mēģiniet vēlreiz!";
				$_SESSION['edit_sawmill_prod'] = $_POST;
				header("Location: edit_production?id=$sawmill_production_id");
				exit();
			}
		}
		else
		{
			$_SESSION['shift'] = "Lūdzu aizpildiet tikai vienu ievadlauku katram darbiniekam!";
			$_SESSION['edit_sawmill_prod'] = $_POST;
			header("Location: edit_production?id=$sawmill_production_id");
			exit();
		}
	}

	$beamSize = BeamSize::Get($beam_size);
	(float)$beam_capacity = (int)$beam_count * (float)$beamSize->size; //Calculates beam_capacity

	(float)$percentage = ((float)$lumber_capacity / (float)$beam_capacity) * 100; //Calculates percentage

	$percentage = round($percentage, 2);

	//Saves updated sawmill production data
	$sawmillProduction = new SawmillProduction();
	$sawmillProduction->date = $date;
	$sawmillProduction->time_from = $time_from;
	$sawmillProduction->time_to = $time_to;
	$sawmillProduction->invoice = $invoice;
	$sawmillProduction->beam_count = $beam_count;
	$sawmillProduction->beam_capacity = $beam_capacity;
	$sawmillProduction->lumber_count = $lumber_count;
	$sawmillProduction->lumber_capacity = $lumber_capacity;
	$sawmillProduction->percentage = $percentage;
	$sawmillProduction->note = $note;
	$sawmillProduction->beam_size_id = $beam_size;
	$sawmillProduction->id = $sawmill_production_id;
	$sawmillProduction->Update();

	//Updates sawmill production maintenance times and notes
	$sawmillMaintenance = new SawmillMaintenance();
	$sawmillMaintenance->DeleteAllSawmillProductionMaintenances($sawmill_production_id);
	for($i=0; $i < count($maintenance_times); $i++)
	{
		if(!empty($maintenance_times[$i]) && !empty($maintenance_notes[$i]))
		{
			$sawmillMaintenance->time = $maintenance_times[$i];
			$sawmillMaintenance->note = $maintenance_notes[$i];
			$sawmillMaintenance->sawmill_production_id = $sawmill_production_id;
			$sawmillMaintenance->Save();
		}
	}

	//Saves data to tables: employees_sawmill_productions, working_times, times
	$employees_sawmill_productions = new EmployeeSawmillProductions();
	$employees_sawmill_productions->DeleteAllSawmillProductionEmployees($sawmill_production_id);

	$working_times = new WorkingTimes();
	$working_times->DeleteAllWorkingEmployees($sawmill_production_invoice);

	$times = new Times();
	$times->DeleteAllNonWorkingEmployees($sawmill_production_invoice);

	for($i = 0; $i < count($ids); $i++)
	{
		$employees_sawmill_productions->employee_id = $ids[$i];
		$employees_sawmill_productions->sawmill_id = $sawmillProduction->id;
		$employees_sawmill_productions->Save();

		if($working_hours[$i] != '' && $working_hours > 0)
		{
			$working_times->date = $date;
			$working_times->invoice = $invoice;
			$working_times->working_hours = $working_hours[$i];
			$working_times->employee_id = $ids[$i];
			$working_times->Save();
		}
		else if($working_hours[$i] == '')
		{	
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
			else if($nonworking[$i] == "3")
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


	$_SESSION['success'] = "Zāģētavas produkcija pievienota veiksmīgi!";
	header("Location: show_sawmill_production");
	exit();

?>