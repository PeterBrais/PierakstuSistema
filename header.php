<?php
session_start();
ob_start(); //Output buffering
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Kokzāģētava "Norupe"</title>
	<link rel="shortcut icon" href="/public/images/favicon.ico">
	<link rel="stylesheet" type="text/css" href="/public/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="/public/css/mix.css">
	
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="/public/js/bootstrap.min.js"></script>
	<script src="/public/js/jquery.validate.js"></script>
	<script src="/public/js/additional-methods.js"></script>
</head>
<body>
	<nav class="navbar navbar-expand-md nav-bg">
		<div class="container">
			<a class="navbar-brand" href="http://www.rigasmezi.lv/">
				<img src="/public/images/Rigas-mezi.png" height="60" alt="Rīgas Meži">
			</a>
					
			<div class="navbar-collapse" id="navbarText">
				<ul class="navbar-nav mr-auto">
					<?php
						if(isset($_SESSION['id']) && isset($_SESSION['role']) && (($_SESSION['role'] == "p") || ($_SESSION['role'] == "a") || ($_SESSION['role'] == "l")) && ($_SESSION['active'] == 1))
						{
					?>
							<li class="nav-item">
								<a class="nav-link" href="/">Sākums</a>
							</li>

						<?php 
							if(($_SESSION['role'] == "a") && ($_SESSION['active'] == 1))
							{
						?>
							<div class="dropdown nav-item">
								<a class="nav-link dropdown-toggle" href="" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
									Lietotāji
								</a>
								<div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
									<a class="dropdown-item" href="/users/show_users">Visi lietotāji</a>
									<a class="dropdown-item" href="/users/signup">Jauns lietotājs</a>
								</div>
							</div>
						<?php
							}

							if((($_SESSION['role'] == "p") || ($_SESSION['role'] == "a")) && ($_SESSION['active'] == 1))
							{
						?>
							<div class="dropdown nav-item">
								<a class="nav-link dropdown-toggle" href="" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
									Darbinieki
								</a>
								<div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
									<a class="dropdown-item" href="/employees/show_employee">Visi darbinieki</a>
									<a class="dropdown-item" href="/employees/add_employee">Jauns darbinieks</a>
									<a class="dropdown-item" href="/employees/show_positions">Visi amati</a>
									<a class="dropdown-item" href="/employees/add_position">Jauns amats</a>
								</div>
							</div>
						<?php
							}
						?>

							<div class="dropdown nav-item">
								<a class="nav-link dropdown-toggle" href="" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
									Zāģētava
								</a>
								<div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
									<a class="dropdown-item" href="/sawmill/show_sawmill_production">
										Visas produkcijas
									</a>
									<a class="dropdown-item" href="/sawmill/add_sawmill_production">
										Jauna produkcija
									</a>
							<?php
								if((($_SESSION['role'] == "p") || ($_SESSION['role'] == "a")) && ($_SESSION['active'] == 1))
								{
							?>
									<a class="dropdown-item" href="/sawmill/show_beam_sizes">Visi tilpumi</a>
									<a class="dropdown-item" href="/sawmill/add_beam_size">Jauns tilpums</a>
							<?php
								}
							?>
								</div>
							</div>

							<div class="dropdown nav-item">
								<a class="nav-link dropdown-toggle" href="" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
									Šķirotava
								</a>
									<div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
									<a class="dropdown-item" href="/sorting/show_sorting_production">
										Visas produkcijas
									</a>
									<a class="dropdown-item" href="/sorting/add_sorting_production">
										Jauna produkcija
									</a>
									<a class="dropdown-item" href="/sorting/add_reserved_sorting_production">
										Jauna rezervētā produkcija
									</a>
								</div>
							</div>

						<?php
							if((($_SESSION['role'] == "p") || ($_SESSION['role'] == "a")) && ($_SESSION['active'] == 1))
							{
						?>
							<li class="nav-item">
								<a class="nav-link" href="/office/office_time_table">Birojs</a>
							</li>
					<?php
							}
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
						<a href="/logout" class="btn btn-warning" name="logout">Iziet</a>
				<?php
					}
				?>
				</ul>
			</div>
		</div>
	</nav>