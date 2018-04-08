<?php
	include_once "../includes/employee.class.php";

	//Checks if position already exists in database
	if(isset($_REQUEST['person_no']))
	{
		$person_no = $_REQUEST['person_no'];

		if(Employee::ExistsPersonNo($person_no))
		{
			echo 'false';
		}
		else
		{
			echo 'true';
		}
	}
	else
	{
		header("Location: /");
		exit();
	}

?>