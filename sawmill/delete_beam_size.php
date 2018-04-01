<?php

	session_start();

/****************** Includes ******************/
	include_once "../includes/beam_size.class.php";
/****************** Includes ******************/

	if(!isset($_SESSION['id']) && !isset($_SESSION['role']))	//Check if user is logged in
	{
		header("Location: /");
		exit();
	}
	if(($_SESSION['role'] != "p") && ($_SESSION['role'] != "a") && ($_SESSION['active'] != 1))	//Check if user have permission to delete data
	{
		header("Location: /");
		exit();
	}

	//Check if ID is set
	if(!isset($_GET['id']))
	{
		header("Location: show_beam_sizes");
		exit();
	}

	//Check if beam_size with ID exists in database
	$beam_size_id = $_GET['id'];
	if(!BeamSize::ExistsId($beam_size_id))
	{
		header("Location: show_beam_sizes");
		exit();
	}

	//Check if beam_size with ID is not used, than allow to delete it
	if(BeamSize::IsSizeUsed($beam_size_id))
	{
		header("Location: show_beam_sizes");
		exit();
	}


	//Object
	$beam = new BeamSize();
	$beam->id = $beam_size_id;
	$beam->Delete();


	$_SESSION['success'] = "Kubatūras izmērs izdzēsts!";
	header("Location: show_beam_sizes");
	exit();

?>