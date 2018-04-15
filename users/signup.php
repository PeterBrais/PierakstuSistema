<?php

	include_once "../header.php";

	if(!isset($_SESSION['id']) && !isset($_SESSION['role']))	//Adding new user possible if user is logged in
	{
		header("Location: 404");
		exit();
	}

	if(($_SESSION['role'] != "a") || ($_SESSION['active'] != 1))	//Check if user is administrator
	{
		header("Location: 404");
		exit();
	}

	if(isset($_SESSION['register']))
	{
		extract($_SESSION['register']);
	}

?>

	<!-- Register -->
	<div class="container">
		<div class="row cont-space">
			<div class="col-md-12">
				<div id="message">
					<?php include "../message.php"; ?>
				</div>
				<div class="card">
					<div class="card-body">
						<h4 class="card-title text-center">Reģistrēt jaunu lietotāju</h4>
						<form id="signup_form" action="register" method="POST">
							<div class="form-group row">
								<label class="col-md-2 offset-md-1 col-form-label">
									Lietotājvārds
									<span class="text-danger" title="Šis lauks ir obligāts">
										&#10033;
									</span>
								</label>
								<div class="col-md-5">
									<input class="form-control" type="text" name="usr" aria-describedby="userArea" value="<?php echo isset($_SESSION['register']) ? $usr : ''; ?>">
									<small id="userArea" class="form-text text-muted">
										* Satur tikai latīņu burtus un ciparus *
									</small>
								</div>
								<div class="col-md-4">
									<?php
										if(isset($_SESSION['usr_name']))
										{
									?>
										<div class="alert alert-danger alert-size" role="alert">
											<?=$_SESSION['usr_name']?>
										</div>
									<?php
											unset($_SESSION['usr_name']);
										}
									?>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-md-2 offset-md-1 col-form-label">
									Parole
									<span class="text-danger" title="Šis lauks ir obligāts">
										&#10033;
									</span>
								</label>
								<div class="col-md-5">
									<input class="form-control" type="password" name="pwd" placeholder="********" aria-describedby="pwdArea" id="pwd_area">
									<small id="pwdArea" class="form-text text-muted">
										* Satur vismaz no vienu mazo un lielo latīņu burtu, ciparu un speciālo simbolu *
									</small>
								</div>
								<div class="col-md-4">
									<?php
										if(isset($_SESSION['pwd']))
										{
									?>
										<div class="alert alert-danger alert-size" role="alert">
											<?=$_SESSION['pwd']?>
										</div>
									<?php
											unset($_SESSION['pwd']);
										}
									?>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-md-2 offset-md-1 col-form-label">
									Parole atkārtoti
									<span class="text-danger" title="Šis lauks ir obligāts">
										&#10033;
									</span>
								</label>
								<div class="col-md-5">
									<input class="form-control" type="password" name="pwd2" placeholder="********" aria-describedby="pwdArea2">
									<small id="pwdArea2" class="form-text text-muted">
										* Ievadītā parole atkārtoti *
									</small>
								</div>
								<div class="col-md-4">
									<?php
										if(isset($_SESSION['pwd2']))
										{
									?>
										<div class="alert alert-danger alert-size" role="alert">
											<?=$_SESSION['pwd2']?>
										</div>
									<?php
											unset($_SESSION['pwd2']);
										}
									?>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-md-2 offset-md-1 col-form-label">
									Loma
									<span class="text-danger" title="Šis lauks ir obligāts">
										&#10033;
									</span>
								</label>
								<div class="col-md-5">
									<select class="custom-select" name="role">
										<option value="0" <?php echo (isset($_SESSION['register']) && $role == "0") ? 'selected' : ''; ?> >Izvēlieties lietotāja lomu</option>
										<option value="1" <?php echo (isset($_SESSION['register']) && $role == "1") ? 'selected' : ''; ?> >Darbinieks</option>
										<option value="2" <?php echo (isset($_SESSION['register']) && $role == "2") ? 'selected' : ''; ?> >Pārvaldnieks</option>
										<option value="3" <?php echo (isset($_SESSION['register']) && $role == "3") ? 'selected' : ''; ?> >Administrators</option>
									</select>
								</div>
								<div class="col-md-4">
									<?php
										if(isset($_SESSION['usr_role']))
										{
									?>
										<div class="alert alert-danger alert-size" role="alert">
											<?=$_SESSION['usr_role']?>
										</div>
									<?php
											unset($_SESSION['usr_role']);
										}
									?>
								</div>
							</div>
							<div class="form-group row">
								<div class="col-md-3 offset-md-3">
									<button class="btn btn-info" type="submit" name="submit">Reģistrēt</button>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>

<script src="../public/js/signup_form.js"></script> 

<?php
	unset($_SESSION['register']);
	include_once "../footer.php";
?>