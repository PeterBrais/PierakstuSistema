<?php
	include_once "header.php";

	if(!isset($_SESSION['id']))	//Registering new user is allowed only when user is logged in
	{
		header("Location: /");
		exit();
	}
?>

	<!-- Register -->
	<div class="container">
		<div class="row cont-space">
			<div class="col-md-12">
				<?php include "message.php"; ?>
				<div class="card">
					<div class="card-body">
						<h4 class="card-title text-center">Reģistrēt jaunu lietotāju</h4>
						<form action="register" method="POST">
							<div class="form-group row">
								<label class="col-md-2 offset-md-1 col-form-label">Lietotājvārds</label>
								<div class="col-md-5">
									<input class="form-control" type="text" name="usr" aria-describedby="userArea">
								<small id="userArea" class="form-text text-muted">
									* Satur tikai lielos un mazos latīņu burtus un ciparus *
								</small>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-md-2 offset-md-1 col-form-label">Parole</label>
								<div class="col-md-5">
									<input class="form-control" type="password" name="pwd" placeholder="********" aria-describedby="pwdArea">
									<small id="pwdArea" class="form-text text-muted">
										* Jābūt garumā no 8 līdz 64 rakstzīmēm *
									</small>
									<small id="pwdArea" class="form-text text-muted">
										* Jāsastāv vismaz no viena mazā un lielā latīņu burta, cipara un speciālā simbola *
									</small>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-md-2 offset-md-1 col-form-label">Parole atkārtoti</label>
								<div class="col-md-5">
									<input class="form-control" type="password" name="pwd2" placeholder="********" aria-describedby="pwdArea2">
									<small id="pwdArea2" class="form-text text-muted">
										* Ievadītā parole atkārtoti *
									</small>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-md-2 offset-md-1 col-form-label">Loma</label>
								<div class="col-md-5">
									<select class="custom-select" name="role">
										<option>Izvēlieties lietotāja lomu</option>
										<option value="2">Pārvaldnieks</option>
										<option value="3">Administrators</option>
									</select>
								</div>
							</div>	
							<div class="form-group row">
								<div class="col-md-3 offset-md-3">
									<button class="btn btn-success" type="submit" name="submit">Reģistrēt</button>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>

<?php
	include_once "footer.php";
?>