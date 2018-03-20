<?php
	include_once "../includes/beam_size.class.php";

	//Check if username already exists in database
	if(isset($_REQUEST['size']))
	{
		$size = $_REQUEST['size'];
		$size = str_replace(',', '.', $size);

		if(BeamSize::ExistsSize($size))
		{
			echo "false";
		}
		else
		{
			echo "true";
		}
	}

?>