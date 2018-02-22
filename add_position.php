<?php
	include_once "header.php";

	if(!isset($_SESSION['id']) && ($_SESSION['role'] == "p") )	//Adding new position possible if user role is Manager
	{
		header("Location: /");
		exit();
	}
?>
	<!-- Add position -->
	<div class="container">
		<div class="row cont-space">
			<div class="col-md-12">	
				<div id="message">
					<?php include "message.php"; ?>
				</div>	
				<div class="card">
					<div class="card-body">
						<h4 class="card-title text-center">Pievienot jaunu amatu</h4>
						<form action="new_position" method="POST">
							<div class="form-group row">
								<label class="col-md-2 offset-md-1 col-form-label">Amats</label>
								<div class="col-md-5">
									<input class="form-control" type="text" name="position" aria-describedby="positionArea">
									<small id="positionArea" class="form-text text-muted">
										* Satur tikai lielos un mazos latīņu burtus, ciparus un speciālos simbolus *
									</small>
									<small id="positionArea" class="form-text text-muted">
										* Jābūt garumā no 3 līdz 40 rakstzīmēm *
									</small>
								</div>
								<div class="col-md-4">
									<?php
										if(isset($_SESSION['new_position']))
										{
									?>
										<div class="alert alert-danger alert-size" role="alert">
											<?=$_SESSION['new_position']?>
										</div>
									<?php
											unset($_SESSION['new_position']);
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
	
<?php
	include_once "footer.php";
?>