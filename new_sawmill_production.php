<?php

	session_start();

	include_once "includes/validate.class.php";
	include "includes/sawmill_production.class.php";

	$inputs = ['date', 'time_from', 'time_to', 'invoice', 'beam_count', 'sizes', 'lumber_count', 'lumber_capacity', 'note', 'maintenance_times', 'maintenance_notes'];

	foreach($inputs as $input)
	{
		if(!isset($_POST[$input]))
		{
			header("Location: /");
			exit();
		}
	}

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

		//Error handlers
		//Check if fields are empty
		if(empty($date) || empty($time_from) || empty($time_to) || empty($invoice) || empty($beam_count) || empty($lumber_count) || empty($lumber_capacity))
		{
			$_SESSION['error'] = "Lūdzu aizpildiet visus obligātos laukus!";
			header("Location: ../add_sawmill_production");
			exit();
		}

		//Checks if date is correct, like yyyy/mm/dd or yyyy-mm-dd
		if(!preg_match("/^\d{4}[\-\/\s]?((((0[13578])|(1[02]))[\-\/\s]?(([0-2][0-9])|(3[01])))|(((0[469])|(11))[\-\/\s]?(([0-2][0-9])|(30)))|(02[\-\/\s]?[0-2][0-9]))$/", $date))
		{
			$_SESSION['date'] = "Lūdzu ievadiet korektu datumu, formā: gggg-mm-dd vai gggg/mm/dd!";
			header("Location: ../add_sawmill_production");
			exit();
		}

		//Check if production times are correct
		if(!Validate::IsValidTime($time_from))
		{
			$_SESSION['time'] = "Lūdzu ievadiet korektu laiku, formā: hh:mm!";
			header("Location: ../add_sawmill_production");
			exit();
		}
		if(!Validate::IsValidTime($time_to))
		{
			$_SESSION['time'] = "Lūdzu ievadiet korektu laiku, formā: hh:mm!";
			header("Location: ../add_sawmill_production");
			exit();
		}

		//Check if invoice is number
		if(!preg_match("/^\d{1,11}+$/", $invoice))
		{
			$_SESSION['invoice'] = "Ievadītais pavadzīmes numurs ir neatbilstošs! Tas var sastāvēt tikai no cipariem!";
			header("Location: ../add_sawmill_production");
			exit();
		}

		//Checks if entered invoice number already exists
		if(SawmillProduction::ExistsInvoice($invoice))	
		{
			$_SESSION['invoice'] = "Pavadzīme ar šādu numuru jau eksistē!";
			header("Location: ../add_sawmill_production");
			exit();
		}

		//Check if beam_count is number
		if(!preg_match("/^\d{1,11}+$/", $beam_count))
		{
			$_SESSION['beam_count'] = "Apaļkoku skaits drīkst sastāvēt tikai no cipariem!";
			header("Location: ../add_sawmill_production");
			exit();
		}

		//Check if beam_size is sellected
		include "includes/beam_size.class.php";
		if(empty($beam_size))
		{
			$_SESSION['beam_size'] = "Lūdzu izvēlieties kubatūras izmēru";
			header("Location: ../add_sawmill_production");
			exit();
		}
		else if(!BeamSize::ExistsId($beam_size)) //Checks if position with this id exists
		{
			$_SESSION['error'] = "Radās kļūda, lūdzu mēģiniet vēlreiz!";
			header("Location: ../add_sawmill_production");
			exit();
		}

		//Check if lumber_count is number
		if(!preg_match("/^\d{1,11}+$/", $lumber_count))
		{
			$_SESSION['lumber_count'] = "Zāģmatariālu skaits drīkst sastāvēt tikai no cipariem!";
			header("Location: ../add_sawmill_production");
			exit();
		}

		//Check if lumber_capacity is float number with comma or dot
		if(!preg_match("/^\d{1,12}([\.\,]\d{1,3}+)?$/", $lumber_capacity))
		{
			$_SESSION['lumber_capacity'] = "Zāģmatariālu tilpums drīkst saturēt tikai ciparus ar komatu! (Maksimums 3 cipari aiz komata)";
			header("Location: ../add_sawmill_production");
			exit();
		}

		//Checks if note is filled, then matches its content with regular expression
		if(!empty($note))
		{
			if(mb_strlen($note) < 2 || mb_strlen($note) > 255)
			{
				$_SESSION['note'] = "Citas piezīmes jābūt garumā no 2 simboliem līdz 255 simboliem!";
				header("Location: ../add_sawmill_production");
				exit();
			}

			if(!preg_match("/^[0-9\p{L}][\p{L}\/0-9\s.,_-]+$/u", $note))
			{
				$_SESSION['note'] = "Citas piezīmes drīkst saturēt tikai latīņu burtus, ciparus un speciālos simbolus!";
				header("Location: ../add_sawmill_production");
				exit();
			}
		}


		if(count($maintenance_times) == 1 && count($maintenance_notes) == 1) //If only one maintenance
		{
			if(!empty($maintenance_times[0]) && !empty($maintenance_notes[0]))	//Both filled - checks
			{
				if(!preg_match("/^\d{1,11}+$/", $maintenance_times[0]))
				{
					$_SESSION['maintenance'] = "Remonta laiks drīkst sastāvēt tikai no cipariem!";
					header("Location: ../add_sawmill_production");
					exit();
				}

				if(mb_strlen($maintenance_notes[0]) < 3 || mb_strlen($maintenance_notes[0]) > 255)
				{
					$_SESSION['maintenance'] = "Piezīme jābūt garumā no 3 simboliem līdz 255 simboliem!";
					header("Location: ../add_sawmill_production");
					exit();
				}

				if(!preg_match("/^[0-9\p{L}][\p{L}\/0-9\s.,_-]+$/u", $maintenance_notes[0]))
				{
					$_SESSION['maintenance'] = "Piezīme drīkst saturēt tikai latīņu burtus, ciparus un speciālos simbolus!";
					header("Location: ../add_sawmill_production");
					exit();
				}
			}
			else if((empty($maintenance_times[0]) && !empty($maintenance_notes[0])) || (!empty($maintenance_times[0]) && empty($maintenance_notes[0]))) //One or other is filled
			{
				$_SESSION['maintenance'] = "Lūdzu ievadiet remonta laiku un piezīmi!";
				header("Location: ../add_sawmill_production");
				exit();
			}
		}
		else //More than one maintenances
		{
			foreach($maintenance_times as $maintenance_time)
			{
				if(empty($maintenance_time))
				{
					$_SESSION['maintenance'] = "Lūdzu ievadiet remonta laikus un piezīmes!";
					header("Location: ../add_sawmill_production");
					exit();
				}

				if(!preg_match("/^\d{1,11}+$/", $maintenance_time))
				{
					$_SESSION['maintenance'] = "Remonta laiks drīkst sastāvēt tikai no cipariem!";
					header("Location: ../add_sawmill_production");
					exit();
				}
			}
			foreach($maintenance_notes as $maintenance_note)
			{
				if(empty($maintenance_note))
				{
					$_SESSION['maintenance'] = "Lūdzu ievadiet remonta laikus un piezīmes!";
					header("Location: ../add_sawmill_production");
					exit();
				}

				if(mb_strlen($maintenance_note) < 3 || mb_strlen($maintenance_note) > 255)
				{
					$_SESSION['maintenance'] = "Piezīme jābūt garumā no 3 simboliem līdz 255 simboliem!";
					header("Location: ../add_sawmill_production");
					exit();
				}

				if(!preg_match("/^[0-9\p{L}][\p{L}\/0-9\s.,_-]+$/u", $maintenance_note))
				{
					$_SESSION['maintenance'] = "Piezīme drīkst saturēt tikai latīņu burtus, ciparus un speciālos simbolus!";
					header("Location: ../add_sawmill_production");
					exit();
				}
			}
		}

		$beamSize = BeamSize::Get($beam_size);
		(float)$beam_capacity = (int)$beam_count * (float)$beamSize->size;

		$lumber_capacity = str_replace(',', '.', $lumber_capacity); //If user typed float with comma, it changes it to dot

		(float)$percentage = ((float)$lumber_capacity / (float)$beam_capacity) * 100;

		$percentage = round($percentage, 2);


		$sawmillProduction = new SawmillProduction();

		$sawmillProduction->date = $date;
		$sawmillProduction->time_from = $time_from;
		$sawmillProduction->time_to = $time_to;
		$sawmillProduction->invoice = $invoice;
		$sawmillProduction->beem_count = $beam_count;
		$sawmillProduction->beem_capacity = $beam_capacity;
		$sawmillProduction->lumber_count = $lumber_count;
		$sawmillProduction->lumber_capacity = $lumber_capacity;
		$sawmillProduction->percentage = $percentage;
		$sawmillProduction->note = $note;
		$sawmillProduction->beam_size_id = $beam_size;
		$sawmillProduction->Save();

		if(!empty($maintenance_times[0]) && !empty($maintenance_notes[0]))
		{
			include "includes/sawmill_maintenance.class.php";

			for($i=0; $i<count($maintenance_times); $i++)
			{
				$sawmillMaintenance = new SawmillMaintenance();
				$sawmillMaintenance->time = $maintenance_times[$i];
				$sawmillMaintenance->note = $maintenance_notes[$i];
				$sawmillMaintenance->sawmill_production_id = $sawmillProduction->id;
				$sawmillMaintenance->Save();
			}
		}

		$_SESSION['success'] = "Zāģētavas produkcija pievienota veiksmīgi!";
		header("Location: ../add_sawmill_production");
		exit();

?>