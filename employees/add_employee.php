<?php
	include_once "../header.php";

	if(!isset($_SESSION['id']) && !isset($_SESSION['role']))	//Adding new employee possible if user is logged in
	{
		header("Location: /");
		exit();
	}
	if(($_SESSION['role'] != "p") && ($_SESSION['role'] != "a"))	//Check if user have permission
	{
		header("Location: /");
		exit();
	}
	if(isset($_SESSION['employee']))
	{
		extract($_SESSION['employee']);
	}
?>
	<!-- Add employee -->
	<div class="container">
		<div class="row cont-space">
			<div class="col-md-12">
				<div id="message">
					<?php include "../message.php"; ?>
				</div>
				<div class="card">
					<div class="card-body">
						<h4 class="card-title text-center">Pievienot jaunu darbinieku</h4>
						<form id="employee_form" action="new_employee" method="POST">
							<div class="form-group row">
								<label class="col-md-2 offset-md-1 col-form-label">
									Vārds
									<span class="text-danger" title="Šis lauks ir obligāts">
										&#10033;
									</span>
								</label>
								<div class="col-md-5">
									<input class="form-control" type="text" name="name" aria-describedby="nameArea" value="<?php echo isset($_SESSION['employee']) ? $name : ''; ?>">
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
									<input class="form-control" type="text" name="last_name" aria-describedby="lastNameArea" value="<?php echo isset($_SESSION['employee']) ? $last_name : ''; ?>">
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
									<input class="form-control" type="text" name="person_no" aria-describedby="personNoArea" value="<?php echo isset($_SESSION['employee']) ? $person_no : ''; ?>">
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
							<div class="form-group row" id="workplace_input">
								<label class="col-md-2 offset-md-1 col-form-label">
									Darbavieta
									<span class="text-danger" title="Šis lauks ir obligāts">
										&#10033;
									</span>
								</label>
								<div class="col-md-5">
									<select class="custom-select" name="place" id="place_selects">
										<option value="0" <?php echo (isset($_SESSION['employee']) && $place == "0") ? 'selected' : ''; ?> >Izvēlieties darba vietu</option>
										<option value="1" <?php echo (isset($_SESSION['employee']) && $place == "1") ? 'selected' : ''; ?> >Birojs</option>
										<option value="2" <?php echo (isset($_SESSION['employee']) && $place == "2") ? 'selected' : ''; ?> >Zāģētava</option>
										<option value="3" <?php echo (isset($_SESSION['employee']) && $place == "3") ? 'selected' : ''; ?> >Šķirotava</option>
									</select>
								</div>
								<div class="col-md-4">
									<?php
										if(isset($_SESSION['place']))
										{
									?>
										<div class="alert alert-danger alert-size" role="alert">
											<?=$_SESSION['place']?>
										</div>
									<?php
											unset($_SESSION['place']);
										}
									?>
								</div>
							</div>
							<div class="form-group row" id="position_selects">
								<label class="col-md-2 offset-md-1 col-form-label">
									Amats
									<span class="text-danger" title="Šis lauks ir obligāts">
										&#10033;
									</span>
								</label>
								<div class="col-md-5">
									<?php include "position_select.php"; ?>
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
							</div>
							<div class="form-group row">
								<div class="col-md-3 offset-md-3">
									<button class="btn btn-info" type="submit" name="submit">Pievienot</button>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>

<!-- Scripts -->
<script src="../public/js/add_employee.js"></script> 
<script src="../public/js/employee_form.js"></script>

<?php
	unset($_SESSION['employee']);
	include_once "../footer.php";
?>