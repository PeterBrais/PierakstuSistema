<?php
	include_once "../includes/sawmill_production.class.php";

	//Check if invoice already exists in database
	if(isset($_REQUEST['invoice']) && isset($_REQUEST['id']))
	{
		$invoice = $_REQUEST['invoice'];
		$id = $_REQUEST['id'];

		if(SawmillProduction::CurrentInvoiceExists($invoice, $id))
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