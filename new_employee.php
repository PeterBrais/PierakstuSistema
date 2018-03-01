<?php

	session_start();

/****************** Includes ******************/
	include_once "includes/position.class.php";
	include_once "includes/validate.class.php";
	include "includes/employee.class.php";
	include "includes/employee_position.class.php";
/****************** Includes ******************/

	$inputs = ['name', 'last_name', 'place', 'positions'];

	foreach($inputs as $input)
	{
		if(!isset($_POST[$input]))
		{
			header("Location: /");
			exit();
		}
	}

	//Sets Variables
	$name = htmlspecialchars($_POST['name']);
	$last_name = htmlspecialchars($_POST['last_name']);
	$place = htmlspecialchars($_POST['place']);
	$positions = $_POST['positions'];

	//Error handlers
	//Check if fields are empty
	if(empty($name) || empty($last_name) || empty($place))
	{
		$_SESSION['error'] = "Lūdzu aizpildiet visus obligātos laukus!";
		header("Location: ../add_employee");
		exit();
	}

	//Checks working place
	if($place == "1")
	{
		$place = "Birojs";
		$shift = NULL;
	}
	elseif($place == "2")
	{
		if(isset($_POST['shift']))
		{
			$shift = $_POST['shift'];

			if(empty($shift))
			{
				$_SESSION['error'] = "Lūdzu aizpildiet visus obligātos laukus!";
				header("Location: ../add_employee");
				exit();
			}

			if($shift != "1" && $shift != "2")
			{
				$_SESSION['error'] = "Lūdzu mēģiniet vēlreiz!";
				header("Location: ../add_employee");
				exit();
			}
		}
		else
		{
			header("Location: /");
			exit();
		}

		$place = "Zagetava";
	}
	elseif($place == "3")
	{
		$place = "Skirotava";
		$shift = NULL;
	}
	else
	{
		$_SESSION['place'] = "Lūdzu izvēlieties darba vietu!";
		header("Location: ../add_employee");
		exit();
	}

	//Check if employee all positions are set
	foreach($positions as $position)
	{
		if(empty($position))
		{
			$_SESSION['position'] = "Lūdzu izvēlieties darbinieka amatu/s!";
			header("Location: ../add_employee");
			exit();
		}
		else if(!Position::Exists($position)) //Checks if position with this id exists
		{
			$_SESSION['error'] = "Radās kļūda, lūdzu mēģiniet vēlreiz!";
			header("Location: ../add_employee");
			exit();
		}
	}

	//Checks names length
	if(!Validate::IsValidNameLength($name))
	{
		$_SESSION['name'] = "Vārds jābūt garumā no 3 simboliem līdz 50 simboliem!";
		header("Location: ../add_employee");
		exit();
	}

	//Check if name is correct
	if(!Validate::IsValidName($name))
	{
		$_SESSION['name'] = "Vārds drīkst saturēt tikai latīņu burtus!";
		header("Location: ../add_employee");
		exit();
	}

	//Checks last name length
	if(!Validate::IsValidNameLength($last_name))
	{
		$_SESSION['last_name'] = "Uzvārds jābūt garumā no 3 simboliem līdz 50 simboliem!";
		header("Location: ../add_employee");
		exit();
	}

	//Check if last name is correct
	if(!Validate::IsValidName($last_name))
	{
		$_SESSION['last_name'] = "Uzvārds drīkst saturēt tikai latīņu burtus!";
		header("Location: ../add_employee");
		exit();
	}

	//Object
	$employee = new Employee();
	$employee->name = $name;
	$employee->last_name = $last_name;
	$employee->place = $place;
	$employee->shift = $shift;
	$employee->Save();

	//Sub-entity 
	$employee_position = new EmployeePosition();
	foreach($positions as $position)
	{
		$employee_position->employee_id = $employee->id;
		$employee_position->position_id = $position;
		$employee_position->Save();
	}

	$_SESSION['success'] = "Darbinieks pievienots!";
	header("Location: ../add_employee");
	exit();

?>