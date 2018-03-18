<?php
	include_once "../includes/position.class.php";

	$name = $_REQUEST['name'];

	//Checks if position already exists in database
	if(Position::ExistsName($name))
	{
		echo 'false';
	}
	else
	{
		echo 'true';
	}

?>