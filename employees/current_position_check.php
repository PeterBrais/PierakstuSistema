<?php
	include_once "../includes/position.class.php";

	//Check if position already exists in database
	if(isset($_REQUEST['name']) && isset($_REQUEST['id']))
	{
		$position = $_REQUEST['name'];
		$id = $_REQUEST['id'];

		if(Position::CurrentPositionExists($position, $id))
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
		header("Location: /");
		exit();
	}

?>