<?php
	include_once "../header.php";

	if(!isset($_SESSION['id']) && !isset($_SESSION['role']))	//Adding new position possible if user is logged in
	{
		header("Location: /");
		exit();
	}
	if(($_SESSION['role'] != "p") && ($_SESSION['role'] != "a"))	//Check if user have permission
	{
		header("Location: /");
		exit();
	}
	if(isset($_SESSION['position']))
	{
		extract($_SESSION['position']);
	}
?>
	<!-- Add position -->
	<div class="container">
		<div class="row cont-space">
			<div class="col-md-12">	
				<div id="message">
					<?php include "../message.php"; ?>
				</div>	
				<div class="card">
					<div class="card-body">
						<h4 class="card-title text-center">Pievienot jaunu amatu</h4>
						<form id="position_form" action="new_position" method="POST">
							<div class="form-group row">
								<label class="col-md-2 offset-md-1 col-form-label">
									Amats
									<span class="text-danger" title="Šis lauks ir obligāts">
										&#10033;
									</span>
								</label>
								<div class="col-md-5">
									<input class="form-control" type="text" name="name" aria-describedby="positionArea" value="<?php echo isset($_SESSION['position']) ? $name : ''; ?>" id="positionArea">
									<small id="positionArea" class="form-text text-muted">
										* Satur tikai latīņu burtus, ciparus un speciālos simbolus *
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
	
<script src="../public/js/position_form.js"></script>

<?php
	unset($_SESSION['position']);
	include_once "../footer.php";
?>