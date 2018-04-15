<?php
	include_once "../header.php";
	include_once "../includes/manager.class.php";
	include_once "../includes/beam_size.class.php";

	//Editing position data possible if user is logged in
	if(!isset($_SESSION['id']) && !isset($_SESSION['role']))
	{
		header("Location: 404");
		exit();
	}

	//Check if user have permission
	if(($_SESSION['role'] != "p") && ($_SESSION['role'] != "a") && ($_SESSION['active'] != 1))	
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

	//Check if beam_size with ID exists in database
	$beam_size_id = $_GET['id'];
	if(!BeamSize::ExistsId($beam_size_id))
	{
		header("Location: 404");
		exit();
	}

	//Extract Session data
	if(isset($_SESSION['edit_beam_size']))
	{
		extract($_SESSION['edit_beam_size']);
	}

	//Returns all positions data
	$size = Manager::GetBeamSizeData($beam_size_id);
?>

	<!-- Update Beam size data -->
	<div class="container">
		<div class="row cont-space">
			<div class="col-md-12">
				<div id="message">
					<?php include "../message.php"; ?>
				</div>
				<div class="card">
					<div class="card-body">
						<h4 class="card-title text-center">
							Labot kubatūras izmēru: <u>'<?=$size['size']?>'</u>
						</h4>

						<form id="edit_beam_size_form" action="update_beam_size" method="POST">

							<input type="hidden" name="size_id" value="<?=$size['id']?>">

							<div class="form-group row">
								<label class="col-md-2 offset-md-1 col-form-label">
									Izmērs
									<span class="text-danger" title="Šis lauks ir obligāts">
										&#10033;
									</span>
								</label>
								<div class="col-md-5">
									<input class="form-control" type="text" name="name" aria-describedby="beamSizeArea" value="<?php echo isset($_SESSION['edit_beam_size']) ? $name : $size['size']; ?>" id="beamSizeArea">
									<small id="beamSizeArea" class="form-text text-muted">
										* Satur tikai ciparus. (Maksimums 3 cipari aiz komata) *
									</small>
								</div>
								<div class="col-md-4">
									<?php
										if(isset($_SESSION['update_beam_size']))
										{
									?>
										<div class="alert alert-danger alert-size" role="alert">
											<?=$_SESSION['update_beam_size']?>
										</div>
									<?php
											unset($_SESSION['update_beam_size']);
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

<script src="../public/js/edit_beam_size_form.js"></script>

<?php
	unset($_SESSION['edit_beam_size']);
	include_once "../footer.php";
?>