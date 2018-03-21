<?php

	include_once "header.php";

?>
	<!-- Login -->
	<div class="container">
		<div class="row cont-space">
			<div class="col-md-12">
				<!-- Shows error message -->
				<?php include "message.php"; ?>	

				<div class="card">
					<div class="card-body">
						<?php
							if(!isset($_SESSION['id']))
							{
						?>
								<h4 class="card-title text-center">Autorizācija</h4>
								<form action="login" method="POST">
									<div class="form-group row">
										<label class="col-md-2 offset-md-1 col-form-label">Lietotājvārds</label>
										<div class="col-md-5">
											<input class="form-control" type="text" name="usr" placeholder="Lietotājvārds" value="<?php echo isset($_SESSION['username_login']) ? $_SESSION['username_login'] : ''; unset($_SESSION['username_login']); ?>">
										</div>
									</div>
									<div class="form-group row">
										<label class="col-md-2 offset-md-1 col-form-label">Parole</label>
										<div class="col-md-5">
											<input class="form-control" type="password" name="pwd" placeholder="********">
										</div>
									</div>
									<div class="form-group row">
										<div class="col-md-3 offset-md-3">
											<button class="btn btn-success" type="submit" name="submit">Pieslēgties</button>
										</div>
									</div>
								</form>

						<?php
							}
							else
							{
								echo "Jūs esat pieteicies!";
								//echo $_SESSION['role'];
							}
						?>
					</div>
				</div>
			</div>
		</div>
	</div>

<?php
	include_once "footer.php";
?>