<?php
	include_once "../header.php";
	include_once "../includes/manager.class.php";
	include_once "../includes/position.class.php";

	//Editing position data possible if user is logged in
	if(!isset($_SESSION['id']) && !isset($_SESSION['role']))
	{
		header("Location: 404");
		exit();
	}

	//Check if user have permission
	if((($_SESSION['role'] != "a") && ($_SESSION['role'] != "p")) || ($_SESSION['active'] != 1))
	{
		header("Location: 404");
		exit();
	}

	//Check if ID is set
	if(!isset($_GET['id']))
	{
		header("Location: 404");
		exit();
	}

	//Check if Position with ID exists in database
	$position_id = $_GET['id'];
	if(!Position::Exists($position_id))
	{
		header("Location: 404");
		exit();
	}

	//Extract Session data
	if(isset($_SESSION['edit_position']))
	{
		extract($_SESSION['edit_position']);
	}

	//Returns all positions data
	$position = Manager::GetPositionData($position_id);
?>

	<!-- Update Position data -->
	<div class="container">
		<div class="row cont-space">
			<div class="col-md-12">
				<div id="message">
					<?php include "../message.php"; ?>
				</div>
				<div class="card">
					<div class="card-body">
						<h4 class="card-title text-center">
							Labot amatu: <u>'<?=$position['name']?>'</u>
						</h4>

						<form id="edit_position_form" action="update_position" method="POST">

							<input type="hidden" name="position_id" value="<?=$position['id']?>">

							<div class="form-group row">
								<label class="col-md-2 offset-md-1 col-form-label">
									Amats
									<span class="text-danger" title="Šis lauks ir obligāts">
										&#10033;
									</span>
								</label>
								<div class="col-md-5">
									<input class="form-control" type="text" name="name" aria-describedby="positionArea" value="<?php echo isset($_SESSION['edit_position']) ? $name : $position['name']; ?>" id="positionArea">
									<small id="positionArea" class="form-text text-muted">
										* Satur tikai latīņu burtus, ciparus un speciālos simbolus *
									</small>
								</div>
								<div class="col-md-4">
									<?php
										if(isset($_SESSION['update_position']))
										{
									?>
										<div class="alert alert-danger alert-size" role="alert">
											<?=$_SESSION['update_position']?>
										</div>
									<?php
											unset($_SESSION['update_position']);
										}
									?>
								</div>
							</div>
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

<script src="../public/js/edit_position_form.js"></script>

<?php
	unset($_SESSION['edit_position']);
	include_once "../footer.php";
?>