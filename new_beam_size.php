<?php
	
	session_start();

	if(isset($_POST['size']))
	{
		$size = htmlspecialchars($_POST['size']);
		//$size = floatval($size); //Trims zeros at back

		//Error handlers
		if(empty($size))
		{
			$_SESSION['new_beam'] = "Lūdzu aizpildiet Izmērs lauku!";
			header("Location: ../add_beam_size");
			exit();
		}

		//Check if number matches complexity
		if(!preg_match("/^\d{1,12}+.\d{1,3}$/", $size))
		{
			$_SESSION['new_beam'] = "Izmērs var saturēt tikai skaitļus ar punktu, maksimums 3 cipari aiz punkta!";
			header("Location: ../add_beam_size");
			exit();
		}

		//Check if number already exists in database
		include_once "includes/beam_size.class.php";
		if(BeamSize::ExistsSize($size))
		{
			$_SESSION['success'] = "Izmērs jau eksistē, jums nav nepieciešams to ievadīt vēlreiz!";
			header("Location: ../add_beam_size");
			exit();
		}

		//Object
		$beam = new BeamSize();
		$beam->size = $size;
		$beam->Save();

		$_SESSION['success'] = "Izmērs pievienots!";
		header("Location: ../add_beam_size");
		exit();
	}
	else
	{
		header("Location: /");
		exit();
	}