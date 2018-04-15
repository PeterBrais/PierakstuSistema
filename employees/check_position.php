<?php
	include_once "../includes/position.class.php";

	//Checks if position already exists in database
	if(isset($_REQUEST['name']))
	{
		$name = $_REQUEST['name'];

		if(Position::ExistsName($name))
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
		header("Location: 404");
		exit();
	}

?>