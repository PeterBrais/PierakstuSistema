<?php

	session_start();

	//include "constants.php";

	//$_SESSION['success'] = ADD_EMPLOYEE;

	if(isset($_POST['name']) && isset($_POST['last_name']) && isset($_POST['place']) && isset($_POST['positions']))
	{
		$name = htmlspecialchars($_POST['name']);
		$last_name = htmlspecialchars($_POST['last_name']);
		// $working_from = htmlspecialchars($_POST['time_from']);
		// $working_to = htmlspecialchars($_POST['time_to']);
		$place = htmlspecialchars($_POST['place']);
		$positions = $_POST['positions'];

		//Error handlers
		//Check if fields are empty
		if(empty($name) || empty($last_name) || empty($place))
		{
			$_SESSION['error'] = "Lūdzu aizpildiet visus laukus!";
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
					$_SESSION['error'] = "Lūdzu aizpildiet visus laukus!";
					header("Location: ../add_employee");
					exit();
				}

				if($shift != "1" && $shift != "2")
				{
					$_SESSION['error'] = "Lūdzu mēģiniet vēlreiz!";
					header("Location: ../add_employee");
					exit();
				}
				// elseif($shift == "2")
				// {

				// }
				// else
				// {
				// 	$_SESSION['error'] = "Lūdzu mēģiniet vēlreiz!";
				// 	header("Location: ../add_employee");
				// 	exit();
				// }
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
		include "includes/position.class.php";
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
		if(mb_strlen($name) < 3 || mb_strlen($name) > 50)
		{
			$_SESSION['name'] = "Vārds jāsatur simbolu skaits robežās no 3 līdz 50!";
			header("Location: ../add_employee");
			exit();
		}

		//Check if name is correct
		if(!preg_match("/^\p{L}[\p{L}\s-]+$/u", $name))
		{
			$_SESSION['name'] = "Vārds var sastāvēt tikai no latīņu burtiem!";
			header("Location: ../add_employee");
			exit();
		}

		//Checks last name length
		if(mb_strlen($last_name) < 3 || mb_strlen($last_name) > 50)
		{
			$_SESSION['last_name'] = "Uzvārds jāsatur simbolu skaits robežās no 3 līdz 50!";
			header("Location: ../add_employee");
			exit();
		}

		//Check if last name is correct
		if(!preg_match("/^\p{L}[\p{L}\s-]+$/u", $last_name))
		{
			$_SESSION['last_name'] = "Uzvārds var sastāvēt tikai no latīņu burtiem!";
			header("Location: ../add_employee");
			exit();
		}

		// //Check if working times are correct
		// if(!preg_match("/^([0-9]|0[0-9]|1[0-9]|2[0-3]):[0-5][0-9]$/", $working_from))
		// {
		// 	$_SESSION['time_from'] = "Jāsastāv no laika formāta hh:mm!";
		// 	header("Location: ../add_employee");
		// 	exit();
		// }
		// if(!preg_match("/^([0-9]|0[0-9]|1[0-9]|2[0-3]):[0-5][0-9]$/", $working_to))
		// {
		// 	$_SESSION['time_to'] = "Jāsastāv no laika formāta hh:mm!";
		// 	header("Location: ../add_employee");
		// 	exit();
		// }

			
		include "includes/employee.class.php";

		$employee = new Employee();

		$employee->name = $name;
		$employee->last_name = $last_name;
		$employee->place = $place;
		$employee->shift = $shift;
		$employee->Save();

		include "includes/employee_position.class.php";

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

	}
	else
	{
		header("Location: /");
		exit();
	}

?>