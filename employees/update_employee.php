<?php

	session_start();

/****************** Includes ******************/
	include_once "../includes/position.class.php";
	include_once "../includes/validate.class.php";
	include_once "../includes/employee.class.php";
	include_once "../includes/employee_position.class.php";
/****************** Includes ******************/

	//Check if user exists in database
	if(!Employee::ExistsEmployeeWithID($_POST['employee_id']))
	{
		header("Location: show_employee");
		exit();
	}

	$inputs = ['name', 'last_name', 'employee_id', 'date_from', 'date_to', 'positions'];

	foreach($inputs as $input)
	{
		if(!isset($_POST[$input]))
		{
			header("Location: /");
			exit();
		}
	}

	//Sets variables
	$name = htmlspecialchars($_POST['name']);
	$last_name = htmlspecialchars($_POST['last_name']);
	$emp_id = htmlspecialchars($_POST['employee_id']);
	$date_from = htmlspecialchars($_POST['date_from']);
	$date_to = htmlspecialchars($_POST['date_to']);
	$positions = $_POST['positions'];

	if(empty($name) || empty($last_name) || empty($emp_id) || empty($date_from))
	{
		$_SESSION['error'] = "Lūdzu aizpildiet visus obligātos laukus!";
		$_SESSION['employee'] = $_POST;
		header("Location: edit_employee?id=$emp_id");
		exit();
	}

	//Checks names length
	if(!Validate::IsValidNameLength($name))
	{
		$_SESSION['name'] = "Vārds jābūt garumā no 3 simboliem līdz 50 simboliem!";
		$_SESSION['employee'] = $_POST;
		header("Location: edit_employee?id=$emp_id");
		exit();
	}

	//Check if name is correct
	if(!Validate::IsValidName($name))
	{
		$_SESSION['name'] = "Vārds drīkst saturēt tikai latīņu burtus!";
		$_SESSION['employee'] = $_POST;
		header("Location: edit_employee?id=$emp_id");
		exit();
	}

	//Checks last name length
	if(!Validate::IsValidNameLength($last_name))
	{
		$_SESSION['last_name'] = "Uzvārds jābūt garumā no 3 simboliem līdz 50 simboliem!";
		$_SESSION['employee'] = $_POST;
		header("Location: edit_employee?id=$emp_id");
		exit();
	}

	//Check if last name is correct
	if(!Validate::IsValidName($last_name))
	{
		$_SESSION['last_name'] = "Uzvārds drīkst saturēt tikai latīņu burtus!";
		$_SESSION['employee'] = $_POST;
		header("Location: edit_employee?id=$emp_id");
		exit();
	}

	//Checks if dates iare correct, like yyyy/mm/dd or yyyy-mm-dd
	if(!Validate::IsValidDate($date_from))
	{
		$_SESSION['date_from'] = "Lūdzu ievadiet korektu datumu, formā: gggg-mm-dd vai gggg/mm/dd!";
		$_SESSION['employee'] = $_POST;
		header("Location: edit_employee?id=$emp_id");
		exit();
	}
	if(!empty($date_to))
	{
		if(!Validate::IsValidDate($date_to))
		{
			$_SESSION['date_to'] = "Lūdzu ievadiet korektu datumu, formā: gggg-mm-dd vai gggg/mm/dd!";
			$_SESSION['employee'] = $_POST;
			header("Location: edit_employee?id=$emp_id");
			exit();
		}
	}


	//Check if user works in sawmill
	if(Employee::ExistsEmployeesWorkplaceSawmillWithID($emp_id))
	{
		//If user works in sawmill then check inputs
		$inputs = ['shift', 'capacity_rate', 'hour_rate'];

		foreach($inputs as $input)
		{
			if(!isset($_POST[$input]))
			{
				header("Location: /");
				exit();
			}
		}

		$shift = $_POST['shift'];
		$capacity_rate = $_POST['capacity_rate'];
		$hour_rate = $_POST['hour_rate'];

		if(empty($shift) || empty($capacity_rate) || empty($hour_rate))
		{
			$_SESSION['error'] = "Lūdzu aizpildiet visus obligātos laukus!";
			$_SESSION['employee'] = $_POST;
			header("Location: edit_employee?id=$emp_id");
			exit();
		}

		if($shift != "1" && $shift != "2")
		{
			$_SESSION['error'] = "Lūdzu mēģiniet vēlreiz!";
			$_SESSION['employee'] = $_POST;
			header("Location: edit_employee?id=$emp_id");
			exit();
		}

		//If user typed number with comma, it changes it to dot
		$capacity_rate = str_replace(',', '.', $capacity_rate);
		$hour_rate = str_replace(',', '.', $hour_rate);

		if(!Validate::IsValidFloatNumberWithTwoDigitsAfterDot($capacity_rate))
		{
			$_SESSION['rates'] = "Kubikmetra likme drīkst saturēt tikai ciparus ar komatu! (Maksimums 2 cipari aiz komata)";
			$_SESSION['employee'] = $_POST;
			header("Location: edit_employee?id=$emp_id");
			exit();
		}
		if(!Validate::IsValidFloatNumberWithTwoDigitsAfterDot($hour_rate))
		{
			$_SESSION['rates'] = "Stundas likme drīkst saturēt tikai ciparus ar komatu! (Maksimums 2 cipari aiz komata)";
			$_SESSION['employee'] = $_POST;
			header("Location: edit_employee?id=$emp_id");
			exit();
		}
		if($capacity_rate <= 0)
		{
			$_SESSION['rates'] = "Kubikmetra likme drīkst saturēt tikai ciparus ar komatu! (Maksimums 2 cipari aiz komata)";
			$_SESSION['employee'] = $_POST;
			header("Location: edit_employee?id=$emp_id");
			exit();
		}
		if($hour_rate <= 0)
		{
			$_SESSION['rates'] = "Stundas likme drīkst saturēt tikai ciparus ar komatu! (Maksimums 2 cipari aiz komata)";
			$_SESSION['employee'] = $_POST;
			header("Location: edit_employee?id=$emp_id");
			exit();
		}
	}
	else
	{
		$shift = NULL;
		$capacity_rate = NULL;
		$hour_rate = NULL;
	}

	//Check if employee all positions are set
	foreach($positions as $position)
	{
		if(empty($position))
		{
			$_SESSION['position'] = "Lūdzu izvēlieties darbinieka amatu/s!";
			$_SESSION['employee'] = $_POST;
			header("Location: edit_employee?id=$emp_id");
			exit();
		}
		else if(!Position::Exists($position)) //Checks if position with this id exists
		{
			$_SESSION['error'] = "Radās kļūda, lūdzu mēģiniet vēlreiz!";
			$_SESSION['employee'] = $_POST;
			header("Location: edit_employee?id=$emp_id");
			exit();
		}
	}

	//Object
	$employee = new Employee();
	$employee->name = $name;
	$employee->last_name = $last_name;
	$employee->shift = $shift;
	$employee->capacity_rate = $capacity_rate;
	$employee->hour_rate = $hour_rate;
	$employee->working_from = $date_from;
	$employee->working_to = $date_to;
	$employee->id = $emp_id;
	$employee->Update();
	echo $emp_id.'<br>';
	echo $name.'<br>';
	echo $last_name.'<br>';
	echo $shift.'<br>';
	echo $capacity_rate.'<br>';
	echo $hour_rate.'<br>';
	echo $date_from.'<br>';
	echo $date_to.'<br>';

	die();


	$employee_position = new EmployeePosition();
	$employee_position->DeleteAllUserPositions($emp_id);
	foreach($positions as $position)
	{
		$employee_position->employee_id = $emp_id;
		$employee_position->position_id = $position;
		$employee_position->Save();
	}


	$_SESSION['success'] = "Darbinieka dati atjaunoti!";
	header("Location: show_employee");
	exit();

?>