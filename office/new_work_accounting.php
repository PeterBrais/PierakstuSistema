<?php

	session_start();

/****************** Includes ******************/
	include_once "../includes/validate.class.php";
	include_once "../includes/office.class.php";
	include_once "../includes/working_times.class.php";
	include_once "../includes/times.class.php";
/****************** Includes ******************/

	//Check if User with ID exists in database
	if(!Office::ExistsBureauEmployeeWithID($_POST['employee_id']))
	{
		header("Location: 404");
		exit();
	}

	//Checks if year and month is correct
	if(!Validate::IsValidPeriod($_POST['period']))
	{
		header("Location: 404");
		exit();
	}

	$inputs = ['employee_id', 'period', 'working_hours', 'overtime_hours'];

	foreach($inputs as $input)
	{
		if(!isset($_POST[$input]))
		{
			header("Location: /");
			exit();
		}
	}

	//Sets variables
	$employee_id = htmlspecialchars($_POST['employee_id']);
	$period = htmlspecialchars($_POST['period']);

	$working_hours = $_POST['working_hours'];
	$overtime_hours = $_POST['overtime_hours'];

	//Check if array length is not equal to days in period
	$period_year = date('Y', strtotime($period));
	$month_index = date('n', strtotime($period));
	$first_day_of_month = mktime(0, 0, 0, $month_index, 1, $period_year);	//Get first day of month timestamp
	$total_month_days = date('t', $first_day_of_month);	//Number of days in month
	if((count($working_hours) != $total_month_days) || (count($working_hours)) != $total_month_days)
	{
		header("Location: 404");
		exit();
	}

	//Error handlers
	//Check if fields are empty
	if(empty($employee_id ) || empty($period))
	{
		$_SESSION['error'] = "Lūdzu aizpildiet visus obligātos laukus!";
		$_SESSION['employee_times'] = $_POST;
		header("Location: work_accounting?id=$employee_id&period=$period");
		exit();
	}

	//Check arrays
	for($i = 0; $i < $total_month_days; $i++)
	{
		//Check working_hours
		if(!empty($working_hours[$i]))
		{
			if(!Validate::IsValidWorkingHours($working_hours[$i]))
			{
				$_SESSION['error'] = "Stundas drīkst sastāvēt tikai no cipariem vai burtiem! (No 1 līdz 8 vai A, N, S, C)";
				$_SESSION['employee_times'] = $_POST;
				header("Location: work_accounting?id=$employee_id&period=$period");
				exit();
			}
		}

		//Check overtime hours
		if(!empty($overtime_hours[$i]))
		{
			if(!Validate::IsValidOvertimeHours($overtime_hours[$i]))
			{
				$_SESSION['error'] = "Virsstundas drīkst sastāvēt tikai no cipariem! (No 1 līdz 8)";
				$_SESSION['employee_times'] = $_POST;
				header("Location: work_accounting?id=$employee_id&period=$period");
				exit();
			}
		}
	}

	//Get timestamp
	$timestamp = new DateTime('now', new DateTimezone('Europe/Riga'));
	$timestamp = $timestamp->format('Y-m-d H:i:s');

	//Object
	$times = new Times();
	$working_times = new WorkingTimes();

	//Delete all data from both tables
	$times->DeleteAllNonWorkingBureauEmployees($employee_id, $period);
	$working_times->DeleteAllWorkingBureauEmployees($employee_id, $period);

	//Saves data
	for($i = 0; $i < $total_month_days; $i++)
	{
		//Get each date
		$day_number  = str_pad(($i+1), 2, "0", STR_PAD_LEFT);
		$date = "$period-$day_number";

		//Check working_hours
		if(!empty($working_hours[$i]))
		{

			if(($working_hours[$i] == "A") || ($working_hours[$i] == "a"))
			{
				$times->vacation = "A";
				$times->sick_leave = NULL;
				$times->nonattendance = NULL;
				$times->pregnancy = NULL;
				$times->date = $date;
				$times->datetime = $timestamp;
				$times->invoice = NULL;
				$times->employee_id = $employee_id;
				$times->Save();
			}
			else if(($working_hours[$i] == "S") || ($working_hours[$i] == "s"))
			{
				$times->vacation = NULL;
				$times->sick_leave = "S";
				$times->nonattendance = NULL;
				$times->pregnancy = NULL;
				$times->date = $date;
				$times->datetime = $timestamp;
				$times->invoice = NULL;
				$times->employee_id = $employee_id;
				$times->Save();
			}
			else if(($working_hours[$i] == "N") || ($working_hours[$i] == "n"))
			{
				$times->vacation = NULL;
				$times->sick_leave = NULL;
				$times->nonattendance = "N";
				$times->pregnancy = NULL;
				$times->date = $date;
				$times->datetime = $timestamp;
				$times->invoice = NULL;
				$times->employee_id = $employee_id;
				$times->Save();
			}
			else if(($working_hours[$i] == "C") || ($working_hours[$i] == "c"))
			{
				$times->vacation = NULL;
				$times->sick_leave = NULL;
				$times->nonattendance = NULL;
				$times->pregnancy = "C";
				$times->date = $date;
				$times->datetime = $timestamp;
				$times->invoice = NULL;
				$times->employee_id = $employee_id;
				$times->Save();
			}
			else
			{
				if(!empty($overtime_hours[$i]))
				{
					$working_times->overtime_hours = $overtime_hours[$i];
				}
				else
				{
					$working_times->overtime_hours = NULL;
				}

				$working_times->date = $date;
				$working_times->datetime = $timestamp;
				$working_times->invoice = NULL;
				$working_times->working_hours = $working_hours[$i];
				$working_times->employee_id = $employee_id;
				$working_times->Save();
			}
		}
		else if(!empty($overtime_hours[$i]))
		{
			$working_times->overtime_hours = $overtime_hours[$i];
			$working_times->date = $date;
			$working_times->datetime = $timestamp;
			$working_times->invoice = NULL;
			$working_times->working_hours = NULL;
			$working_times->employee_id = $employee_id;
			$working_times->Save();
		}
	}


	$_SESSION['success'] = "Darba uzskaite pievienota veiksmīgi!";
	header("Location: office_time_table");
	exit();

?>