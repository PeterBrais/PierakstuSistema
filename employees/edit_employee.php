<?php
	include_once "../header.php";
	include_once "../includes/employee.class.php";
	include_once "../includes/manager.class.php";

	if(!isset($_SESSION['id']) && !isset($_SESSION['role']))	//Editing employee data possible if user is logged in
	{
		header("Location: /");
		exit();
	}
	if(($_SESSION['role'] != "p") && ($_SESSION['role'] != "a"))	//Check if user have permission
	{
		header("Location: /");
		exit();
	}
	if(!isset($_GET['id']))		//Check if ID is set
	{
		header("Location: show_employee");
		exit();
	}

	//Check if User with ID exists in database
	$user_id = $_GET['id'];
	if(!Employee::ExistsEmployeeWithID($user_id))
	{
		header("Location: show_employee");
		exit();
	}

	//Extract Session data
	if(isset($_SESSION['employee']))
	{
		extract($_SESSION['employee']);
	}

	//Returns aemployees data
	$employee = Employee::GetEmployeesData($user_id);
?>

	<!-- Update Employee data -->
	<div class="container">
		<div class="row cont-space">
			<div class="col-md-12">
				<div id="message">
					<?php include "../message.php"; ?>
				</div>
				<div class="card">
					<div class="card-body">
						<h4 class="card-title text-center">
							Labot: <u>'<?=$employee['name']?> <?=$employee['last_name']?>'</u>. Strādā: 
							<u id="employee_place_dsd">
							<?php 
								if($employee['place'] == "Zagetava"){
									echo "'Zāģētava'";
								}
								else if($employee['place'] == "Skirotava")
								{
									echo "'Šķirotava'";
								}
								else if($employee['place'] == "Birojs")
								{
									echo "'Birojs'";
								}
							?>
							</u>
						</h4>

						<form id="edit_employee_form" action="update_employee" method="POST">

							<input type="hidden" name="employee_id" value="<?=$employee['id']?>">

							<div class="form-group row">
								<label class="col-md-2 offset-md-1 col-form-label">
									Vārds
									<span class="text-danger" title="Šis lauks ir obligāts">
										&#10033;
									</span>
								</label>
								<div class="col-md-5">
									<input class="form-control" type="text" name="name" aria-describedby="nameArea" value="<?php echo isset($_SESSION['employee']) ? $name : $employee['name']; ?>">
									<small id="nameArea" class="form-text text-muted">
										* Satur tikai latīņu burtus, garumā no 3 līdz 50 rakstzīmēm *
									</small>
								</div>
								<div class="col-md-4">
									<?php
										if(isset($_SESSION['name']))
										{
									?>
										<div class="alert alert-danger alert-size" role="alert">
											<?=$_SESSION['name']?>
										</div>
									<?php
											unset($_SESSION['name']);
										}
									?>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-md-2 offset-md-1 col-form-label">
									Uzvārds
									<span class="text-danger" title="Šis lauks ir obligāts">
										&#10033;
									</span>
								</label>
								<div class="col-md-5">
									<input class="form-control" type="text" name="last_name" aria-describedby="lastNameArea" value="<?php echo isset($_SESSION['employee']) ? $last_name : $employee['last_name']; ?>">
									<small id="lastNameArea" class="form-text text-muted">
										* Satur tikai latīņu burtus, garumā no 3 līdz 50 rakstzīmēm *
									</small>
								</div>
								<div class="col-md-4">
									<?php
										if(isset($_SESSION['last_name']))
										{
									?>
										<div class="alert alert-danger alert-size" role="alert">
											<?=$_SESSION['last_name']?>
										</div>
									<?php
											unset($_SESSION['last_name']);
										}
									?>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-md-2 offset-md-1 col-form-label">
									Personas Kods
									<span class="text-danger" title="Šis lauks ir obligāts">
										&#10033;
									</span>
								</label>
								<div class="col-md-5">
									<input class="form-control" type="text" name="person_no" aria-describedby="personNoArea" value="<?php echo isset($_SESSION['employee']) ? $person_no : $employee['person_id']; ?>">
									<small id="personNoArea" class="form-text text-muted">
										* Sastāv no 6 cipariem, defises un 5 cipariem *
									</small>
								</div>
								<div class="col-md-4">
									<?php
										if(isset($_SESSION['person_no']))
										{
									?>
										<div class="alert alert-danger alert-size" role="alert">
											<?=$_SESSION['person_no']?>
										</div>
									<?php
											unset($_SESSION['person_no']);
										}
									?>
								</div>
							</div>
						<?php
							if($employee['place'] == "Zagetava")
							{
						?>
							<div class="form-group row">
								<label class="col-md-2 offset-md-1 col-form-label">
									Maiņa
									<span class="text-danger" title="Šis lauks ir obligāts">
										&#10033;
									</span>
								</label>
								<div class="col-md-5">
									<select class="custom-select" name="shift">
										<option value="1" <?php echo ((isset($_SESSION['employee']) && $shift == "1") || ($employee['shift'] == "1")) ? 'selected' : ''; ?> >1</option>
										<option value="2" <?php echo ((isset($_SESSION['employee']) && $shift == "2") || ($employee['shift'] == "2")) ? 'selected' : ''; ?> >2</option>
									</select>
								</div>
								<div class="col-md-4">
								</div>
							</div>
							<div class="form-group row">
								<label class="col-md-2 offset-md-1 col-form-label">
									Likmes
									<span class="text-danger" title="Šis lauks ir obligāts">
										&#10033;
									</span>
								</label>
								<div class="col-md-5">
									<div class="row">
										<div class="col-md-6">
											<input class="form-control" type="number" min="0" step="0.01" name="capacity_rate" aria-describedby="ratesArea" placeholder="m&sup3 likme" value="<?php echo isset($_SESSION['employee']) ? $capacity_rate : $employee['capacity_rate']; ?>">
										</div>
										<div class="col-md-6">
											<input class="form-control" type="number" min="0" step="0.01" name="hour_rate" aria-describedby="ratesArea" placeholder="Stundas likme" value="<?php echo isset($_SESSION['employee']) ? $hour_rate : $employee['hour_rate']; ?>">
										</div>
									</div>
									<small id="ratesArea" class="form-text text-muted">
										* Satur tikai ciparus, likmi par m<sup>3</sup> un stundu *
									</small>
								</div>
								<div class="col-md-4">
									<?php
										if(isset($_SESSION['rates']))
										{
									?>
										<div class="alert alert-danger alert-size" role="alert">
											<?=$_SESSION['rates']?>
										</div>
									<?php
											unset($_SESSION['rates']);
										}
									?>
								</div>
							</div>
						<?php
							}
						?>
							<div class="form-group row">
								<label class="col-md-2 offset-md-1 col-form-label">
									Strādā no
									<span class="text-danger" title="Šis lauks ir obligāts">
										&#10033;
									</span>
								</label>
								<div class="col-md-5">
									<input class="form-control" type="text" name="date_from" aria-describedby="dateArea" placeholder="2000/01/01" value="<?php echo isset($_SESSION['employee']) ? $date_from : $employee['working_from']; ?>">
									<small id="dateArea" class="form-text text-muted">
										* Satur tikai datumu, piemēram, formātā: GGGG-MM-DD *
									</small>
								</div>
								<div class="col-md-4">
									<?php
										if(isset($_SESSION['date_from']))
										{
									?>
										<div class="alert alert-danger alert-size" role="alert">
											<?=$_SESSION['date_from']?>
										</div>
									<?php
											unset($_SESSION['date_from']);
										}
									?>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-md-2 offset-md-1 col-form-label">
									Strādā līdz
								</label>
								<div class="col-md-5">
									<input class="form-control" type="text" name="date_to" aria-describedby="dateArea" placeholder="2000/01/01" value="<?php echo isset($_SESSION['employee']) ? $date_to : $employee['working_to']; ?>">
									<small id="dateArea" class="form-text text-muted">
										* Satur tikai datumu, piemēram, formātā: GGGG-MM-DD *
									</small>
								</div>
								<div class="col-md-4">
									<?php
										if(isset($_SESSION['date_to']))
										{
									?>
										<div class="alert alert-danger alert-size" role="alert">
											<?=$_SESSION['date_to']?>
										</div>
									<?php
											unset($_SESSION['date_to']);
										}
									?>
								</div>
							</div>
							<div class="form-group row" id="position_selects">
						<?php
							$all_positions = Employee::GetAllPositions($employee['id']);
							$i = 0;
							foreach($all_positions as $all_position)
							{	
								if($i == 0)
								{
									$i++;
						?>
								<label class="col-md-2 offset-md-1 col-form-label">
									Amats
									<span class="text-danger" title="Šis lauks ir obligāts">
										&#10033;
									</span>
								</label>
								<div class="col-md-5">

								<?php
									$positions = Manager::Positions();
								?>
									<select class="custom-select mb-2" name="positions[]">
										<option value="">Izvēlieties amatu</option>
								<?php
									foreach($positions as $position)
									{
										if($all_position['id'] == $position['id'])
										{
											echo '<option value="'.$position['id'].'" selected >'.$position['name'].'</option>';
										}
										else
										{
											echo '<option value="'.$position['id'].'"  >'.$position['name'].'</option>';
										}
									}
								?>
									</select>

								</div>
								<div class="col-md-1">
									<button type="button" name="add" id="add" class="btn btn-success">+</button>
								</div>
								<div class="col-md-3">
									<?php
										if(isset($_SESSION['position']))
										{
									?>
										<div class="alert alert-danger alert-size" role="alert">
											<?=$_SESSION['position']?>
										</div>
									<?php
											unset($_SESSION['position']);
										}
									?>
								</div>
						<?php
								}
								else
								{
						?>
								<div class="offset-md-3 col-md-5 position-select">

								<?php
									$positions = Manager::Positions();
								?>
									<select class="custom-select mb-2" name="positions[]">
										<option value="">Izvēlieties amatu</option>
								<?php
									foreach($positions as $position)
									{
										if($all_position['id'] == $position['id'])
										{
											echo '<option value="'.$position['id'].'" selected >'.$position['name'].'</option>';
										}
										else
										{
											echo '<option value="'.$position['id'].'"  >'.$position['name'].'</option>';
										}
									}
								?>
									</select>
								</div>
								<div class="col-md-1">
									<button type="button" class="btn btn-danger remove">X</button>
								</div>
								<div class="col-md-3">
									<?php
										if(isset($_SESSION['position']))
										{
									?>
										<div class="alert alert-danger alert-size" role="alert">
											<?=$_SESSION['position']?>
										</div>
									<?php
											unset($_SESSION['position']);
										}
									?>
								</div>
						<?php
								}
							}
						?>
							</div>
						<?php
							if(($employee['place'] == "Zagetava") || $employee['place'] == "Skirotava")
							{
						?>
							<div class="form-group row">
								<label class="col-md-2 offset-md-1 col-form-label">
									Darbu nodošanas - Pieņemšanas akta Nr.
								</label>
								<div class="col-md-5">
									<input id="act_number_id" class="form-control" type="text" name="act_no" aria-describedby="actNoArea" value="<?php echo isset($_SESSION['employee']) ? $act_no : $employee['act_number']; ?>">
									<small id="actNoArea" class="form-text text-muted">
										* Aktuāls tikai zāģētavas un šķirotavas darbiniekiem *
									</small>
								</div>
								<div class="col-md-4">
									<?php
										if(isset($_SESSION['act_no']))
										{
									?>
										<div class="alert alert-danger alert-size" role="alert">
											<?=$_SESSION['act_no']?>
										</div>
									<?php
											unset($_SESSION['act_no']);
										}
									?>
								</div>
							</div>
						<?php
							}
						?>
							<div class="form-group row">
								<div class="col-md-3 offset-md-3">
									<button class="btn btn-info" type="submit" name="submit">Labot</button>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>

<script src="../public/js/edit_employee.js"></script>
<script src="../public/js/edit_employee_form.js"></script>

<?php
	unset($_SESSION['employee']);
	include_once "../footer.php";
?>