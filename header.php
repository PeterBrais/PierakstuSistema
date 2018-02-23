<?php
session_start();
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Kokzāģētava "Norupe"</title>
	<link rel="shortcut icon" href="public/images/favicon.ico">
	<link rel="stylesheet" type="text/css" href="public/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="public/css/mix.css">
	
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="public/js/bootstrap.min.js"></script>

      
</head>
<body>
	<nav class="navbar navbar-expand-md nav-bg">
		<div class="container">
				<a class="navbar-brand" href="http://www.rigasmezi.lv/">
					<img src="public/images/Rigas-mezi.png" height="60" alt="Rīgas Meži">
				</a>

					<!--
					<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
	    				<span class="navbar-toggler-icon"></span>
	  				</button>
					-->
					
				<div class="navbar-collapse" id="navbarText">
					<ul class="navbar-nav mr-auto">
						<?php
							if(isset($_SESSION['id']))
							{
						?>
								<li class="nav-item">
									<a class="nav-link" href="/">Sākums</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" href="signup">Jauns lietotājs</a>
								</li>

								<div class="dropdown nav-item">
									<a class="nav-link dropdown-toggle" href="" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
										Darbinieki
									</a>

									<div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
										<a class="dropdown-item" href="show_employee">Visi darbinieki</a>
										<a class="dropdown-item" href="add_employee">Jauns darbinieks</a>
										<a class="dropdown-item" href="add_position">Jauns amats</a>
									</div>
								</div>
						<?php
							}
						?>
					</ul>

					<ul class="navbar-nav ml-auto">
						<?php
							if(isset($_SESSION['id']))
							{
						?>
							<div class="user-name">
								<?=$_SESSION['username']?>
							</div>
							<a href="logout" class="btn btn-warning" name="logout">Iziet</a>
						<?php
							}
						?>
					</ul>


				</div>
		</div>
	</nav>

	<?php
		include "constants.php";
	?>