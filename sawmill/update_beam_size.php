<?php

	session_start();

/****************** Includes ******************/
	include_once "../includes/beam_size.class.php";
	include_once "../includes/validate.class.php";
/****************** Includes ******************/

	//Check if beam size exists in database
	if(!BeamSize::ExistsId($_POST['size_id']))
	{
		header("Location: 404");
		exit();
	}

	$inputs = ['name', 'size_id'];

	foreach($inputs as $input)
	{
		if(!isset($_POST[$input]))
		{
			header("Location: /");
			exit();
		}
	}

	//Sets variables
	$size = htmlspecialchars($_POST['name']);
	$size_id = htmlspecialchars($_POST['size_id']);

	//Error handlers
	//Check if size and id is set
	if(empty($size) || empty($size_id))
	{
		$_SESSION['update_beam_size'] = "Lūdzu aizpildiet Izmērs lauku!";
		$_SESSION['edit_beam_size'] = $_POST;
		header("Location: edit_beam_size?id=$size_id");
		exit();
	}

	//If user typed number with comma, it changes it to dot
	$size = str_replace(',', '.', $size);

	//Check if number matches complexity
	if(!Validate::IsValidFloatNumber($size))
	{
		$_SESSION['update_beam_size'] = "Izmērs drīkst saturēt tikai ciparus ar komatu! (Maksimums 3 cipari aiz komata)";
		$_SESSION['edit_beam_size'] = $_POST;
		header("Location: edit_beam_size?id=$size_id");
		exit();
	}
	if($size <= 0)
	{
		$_SESSION['update_beam_size'] = "Izmērs drīkst saturēt tikai ciparus ar komatu! (Maksimums 3 cipari aiz komata)";
		$_SESSION['edit_beam_size'] = $_POST;
		header("Location: edit_beam_size?id=$size_id");
		exit();
	}

	//Check if number already exists in database
	if(BeamSize::CurrentBeamSizeExists($size, $size_id))
	{
		$_SESSION['warning'] = "Izmērs jau eksistē, jums nav nepieciešams to ievadīt vēlreiz!";
		$_SESSION['edit_beam_size'] = $_POST;
		header("Location: edit_beam_size?id=$size_id");
		exit();
	}

	//Object
	$beam = new BeamSize();
	$beam->size = $size;
	$beam->id = $size_id;
	$beam->Update();

	$_SESSION['success'] = "Izmērs atjaunots veiksmīgi!";
	header("Location: show_beam_sizes");
	exit();

?>