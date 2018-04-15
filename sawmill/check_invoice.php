<?php
	include_once "../includes/sawmill_production.class.php";

	//Checks if invoice already exists in database
	if(isset($_REQUEST['invoice']))
	{
		$invoice = $_REQUEST['invoice'];

		if(SawmillProduction::ExistsInvoice($invoice))
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