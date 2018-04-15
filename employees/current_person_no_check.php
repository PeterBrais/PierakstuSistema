<?php
	include_once "../includes/employee.class.php";

	//Check if person id already exists in database
	if(isset($_REQUEST['person_no']) && isset($_REQUEST['id']))
	{
		$person_no = $_REQUEST['person_no'];
		$id = $_REQUEST['id'];

		if(Employee::CurrentPersonNoExists($person_no, $id))
		{
			echo "false";
		}
		else
		{
			echo "true";
		}
	}
	else
	{
		header("Location: 404");
		exit();
	}

?>