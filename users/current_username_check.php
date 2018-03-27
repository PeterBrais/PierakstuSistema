<?php
	include_once "../includes/user.class.php";

	//Check if username already exists in database
	if(isset($_REQUEST['usr']) && isset($_REQUEST['id']))
	{
		$username = $_REQUEST['usr'];
		$id = $_REQUEST['id'];

		if(User::CurrentUserUsernameExists($username, $id))
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