<?php
session_start();
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>Pierakstu sistēma</title>
	<link rel="stylesheet" type="text/css" href="public/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="public/css/mix.css">
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
						<li class="nav-item">
							<a class="nav-link" href="">Test1</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="">Test2</a>
						</li>
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