<?php
	include_once "../includes/user.class.php";

	//Check if username already exists in database
	if(isset($_REQUEST['usr']))
	{
		$username = $_REQUEST['usr'];

		if(User::Exists($username))
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