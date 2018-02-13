<?php
	include_once "header.php";

	if(!isset($_SESSION['id']))
	{
		header("Location: /");
		exit();
	}
?>

	<!-- Register -->
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<?php include "message.php"; ?>
				<div class="card">
					<div class="card-body">
						<h4 class="card-title text-center">Reģistrēt jaunu lietotāju</h4>
						<form action="register" method="POST">
							<div class="form-group row">
								<label class="col-md-2 col-form-label">Lietotājvārds</label>
								<div class="col-md-5">
									<input class="form-control" type="text" name="usr">
								</div>
							</div>
							<div class="form-group row">
								<label class="col-md-2 col-form-label">Parole</label>
								<div class="col-md-5">
									<input class="form-control" type="password" name="pwd" placeholder="********">
								</div>
							</div>
							<div class="form-group row">
								<label class="col-md-2 col-form-label">Parole atkārtoti</label>
								<div class="col-md-5">
									<input class="form-control" type="password" name="pwd2" placeholder="********">
								</div>
							</div>
							<div class="form-group row">
								<div class="col-md-3 offset-md-2">
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