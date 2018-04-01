<?php
	include_once "../includes/beam_size.class.php";

	//Check if position already exists in database
	if(isset($_REQUEST['name']) && isset($_REQUEST['id']))
	{
		$size = $_REQUEST['name'];
		$id = $_REQUEST['id'];

		if(BeamSize::CurrentBeamSizeExists($size, $id))
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