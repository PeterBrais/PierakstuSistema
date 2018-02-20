<?php

	session_start();

	include "constants.php";
	//$_SESSION['success'] = ADD_EMPLOYEE;

	if(isset($_POST['name']) && isset($_POST['last']) && isset($_POST['timefrom']) && isset($_POST['timeto']) && isset($_POST['place']) && isset($_POST['positions']))
	{
		$name = htmlspecialchars($_POST['name']);
		$last = htmlspecialchars($_POST['last']);
		$workingfrom = htmlspecialchars($_POST['timefrom']);
		$workingto = htmlspecialchars($_POST['timeto']);
		$place = htmlspecialchars($_POST['place']);
		$positions = $_POST['positions'];

		// foreach ($positions as $position) 
		// {
		// 	echo $position.'<br>';
		// }

		//Error handlers
		//Check if fields are set
		if(empty($name) || empty($last) || empty($workingfrom) || empty($workingto) || empty($place))
		{
			$_SESSION['error'] = "Lūdzu aizpildiet visus laukus!";
			header("Location: ../add_employee");
			exit();
		}

		if($place == "1")
		{
			$place = "Birojs";
		}
		elseif($place == "2")
		{
			$place = "Zagetava";
		}
		elseif($place == "3")
		{
			$place = "Skirotava";
		}
		else
		{
			$_SESSION['error'] = "Lūdzu izvēlieties darba vietu!";
			header("Location: ../add_employee");
			exit();
		}

		//Check if employee all positions are set
		foreach($positions as $position)
		{
			if(empty($position))
			{
				$_SESSION['error'] = "Lūdzu ievadiet darbinieka amatu/s!";
				header("Location: ../add_employee");
				exit();
			}
		}

		//Check if name is correct
		if(!preg_match("/^[a-zA-Z\s]*$/", $name))
		{
			$_SESSION['name'] = "Lietotājvārds var sastāvēt tikai no burtiem!";
			header("Location: ../add_employee");
			exit();
		}

		

	}
	else
	{
		header("Location: /");
		exit();
	}


// $connect = mysqli_connect("localhost", "root", "", "pieraksts");

// $number = count($_POST["name"]);
// if($number > 1)
// {
// 	for($i; $i < $number; $i++)
// 	{
// 		if(trim($_POST["name"][$i]) != '')
// 		{
// 			$sql = "INSERT INTO positions VALUES ('".mysql_real_escape_string($connect, $_POST["name"][$i])."')";
// 			mysqli_query($connect, $sql);
// 		}
// 	}
// 	echo "Data inserted";
// }
// else
// {
// 	echo "Please Enter name";
// }

?>