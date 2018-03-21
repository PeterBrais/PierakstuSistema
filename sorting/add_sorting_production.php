<?php
	include_once "../header.php";

	if(!isset($_SESSION['id']) && !isset($_SESSION['role']))	//Adding new sorting production possible if user is logged in
	{
		header("Location: /");
		exit();
	}
	if(($_SESSION['role'] != "a") && ($_SESSION['role'] != "p"))	//Check if user have permission
	{
		header("Location: /");
		exit();
	}
	if(isset($_SESSION['sorting_prod']))
	{
		extract($_SESSION['sorting_prod']);
	}
?>

<!-- Add sorting production -->
	<div class="container">
		<div class="row cont-space">
			<div class="col-md-12">
				<div id="message">
					<?php include "../message.php"; ?>
				</div>
				<div class="card">
					<div class="card-body">
						<h4 class="card-title text-center">Pievienot jaunu šķirošanas produkciju</h4>

						<form id="sorting_form" action="new_sorting_production" method="POST">
							<div class="form-group row">
								<label class="col-md-2 offset-md-1 col-form-label">
									Datums
									<span class="text-danger" title="Šis lauks ir obligāts">
										&#10033;
									</span>
								</label>
								<div class="col-md-5">
									<input class="form-control" type="text" name="date" aria-describedby="dateArea" placeholder="2000/01/01" value="<?php echo isset($_SESSION['sorting_prod']) ? $date : ''; ?>">
									<small id="dateArea" class="form-text text-muted">
										* Satur tikai datumu, piemēram, formātā: GGGG-MM-DD *
									</small>
								</div>
								<div class="col-md-4">
									<?php
										if(isset($_SESSION['date']))
										{
									?>
										<div class="alert alert-danger alert-size" role="alert">
											<?=$_SESSION['date']?>
										</div>
									<?php
											unset($_SESSION['date']);
										}
									?>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-md-2 offset-md-1 col-form-label">
									Laiks
									<span class="text-danger" title="Šie lauki ir obligāti">
										&#10033;
									</span>
								</label>
								<div class="col-md-5">
									<div class="row">
										<div class="col-md-6">
											<input class="form-control" type="time" name="time_from" aria-describedby="timeFromArea" value="<?php echo isset($_SESSION['sorting_prod']) ? $time_from : ''; ?>">
										</div>
										<div class="col-md-6">
											<input class="form-control" type="time" name="time_to" aria-describedby="timeFromArea" value="<?php echo isset($_SESSION['sorting_prod']) ? $time_to : ''; ?>">
										</div>
									</div>
									<small id="timeFromArea" class="form-text text-muted">
										* Satur tikai laikus, piemēram, formātā: 00:00 *
									</small>
								</div>
								<div class="col-md-4">
									<?php
										if(isset($_SESSION['time']))
										{
									?>
										<div class="alert alert-danger alert-size" role="alert">
											<?=$_SESSION['time']?>
										</div>
									<?php
											unset($_SESSION['time']);
										}
									?>
								</div>
							</div>							
							<div class="form-group row">
								<label class="col-md-2 offset-md-1 col-form-label">
									Pavadzīmes Nr.
									<span class="text-danger" title="Šis lauks ir obligāts">
										&#10033;
									</span>
								</label>
								<div class="col-md-5">
									<input class="form-control" type="text" name="invoice" aria-describedby="invoiceArea" value="<?php echo isset($_SESSION['sorting_prod']) ? $invoice : ''; ?>">
									<small id="invoiceArea" class="form-text text-muted">
										* Satur tikai ciparus *
									</small>
								</div>
								<div class="col-md-4">
									<?php
										if(isset($_SESSION['invoice']))
										{
									?>
										<div class="alert alert-danger alert-size" role="alert">
											<?=$_SESSION['invoice']?>
										</div>
									<?php
											unset($_SESSION['invoice']);
										}
									?>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-md-2 offset-md-1 col-form-label">
									Izmēri
									<span class="text-danger" title="Šie lauki ir obligāti">
										&#10033;
									</span>
								</label>
								<div class="col-md-5">
									<div class="row">
										<div class="col-md-4">
											<input class="form-control" type="number" min="0" name="thick" aria-describedby="timeFromArea" placeholder="Biezums" id="thickeness" value="<?php echo isset($_SESSION['sorting_prod']) ? $thick : ''; ?>">
										</div>
										<div class="col-md-4">
											<input class="form-control" type="number" min="0" name="width" aria-describedby="timeFromArea" placeholder="Platums" id="width" value="<?php echo isset($_SESSION['sorting_prod']) ? $width : ''; ?>">
										</div>
										<div class="col-md-4">
											<input class="form-control" type="number" min="0" name="length" aria-describedby="timeFromArea" placeholder="Garums" id="length" value="<?php echo isset($_SESSION['sorting_prod']) ? $length : ''; ?>">
										</div>
									</div>
									<small id="timeFromArea" class="form-text text-muted">
										* Satur tikai ciparus *
									</small>
								</div>
								<div class="col-md-4">
									<?php
										if(isset($_SESSION['sizes']))
										{
									?>
										<div class="alert alert-danger alert-size" role="alert">
											<?=$_SESSION['sizes']?>
										</div>
									<?php
											unset($_SESSION['sizes']);
										}
									?>
								</div>
							</div>	
							<div class="form-group row">
								<label class="col-md-2 offset-md-1 col-form-label">
									Skaits
									<span class="text-danger" title="Šis lauks ir obligāts">
										&#10033;
									</span>
								</label>
								<div class="col-md-5">
									<input class="form-control" type="number" min="0" name="sawn_count" aria-describedby="sawnCountArea" id="sawn_count" placeholder="Kopējais skaits" value="<?php echo isset($_SESSION['sorting_prod']) ? $sawn_count : ''; ?>">
									<small id="sawnCountArea" class="form-text text-muted">
										* Satur tikai ciparus, kopējo (gab) skaitu *
									</small>
								</div>
								<div class="col-md-4">
									<?php
										if(isset($_SESSION['sawn_count']))
										{
									?>
										<div class="alert alert-danger alert-size" role="alert">
											<?=$_SESSION['sawn_count']?>
										</div>
									<?php
											unset($_SESSION['sawn_count']);
										}
									?>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-md-2 offset-md-1 control-label">
									Tilpums
								</label>
								<div class="col-md-5">
									<p class="form-control-static" id="sawn_capacity"> m<sup>3</sup></p>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-md-2 offset-md-1 control-label">
									Defektu skaits
								</label>
								<div class="col-md-5">
									<input class="form-control" type="number" min="0" name="defect_count" aria-describedby="sawnCountArea" id="defect_count" placeholder="Defektu skaits" value="<?php echo isset($_SESSION['sorting_prod']) ? $defect_count : ''; ?>">
								</div>
								<div class="col-md-4">
									<?php
										if(isset($_SESSION['defect_count']))
										{
									?>
										<div class="alert alert-danger alert-size" role="alert">
											<?=$_SESSION['defect_count']?>
										</div>
									<?php
											unset($_SESSION['defect_count']);
										}
									?>
								</div>
							</div>
							<hr>

							<h5 class="text-center">Sašķirotā produkcija</h5>
							<div id="sorted_select">
								<?php include_once "sorted_production_inputs.php"; ?>
							</div>
							<div class="form-group row">
								<div class="offset-md-3 col-md-4">
									<button type="button" name="add" id="add" class="btn btn-success">Pievienot sašķiroto produkciju</button>
								</div>
							</div>
							<hr>

							<h5 class="text-center">Šķirotavas darbinieki</h5>
							<?php include_once "sorting_employees_table.php"; ?>
							
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

<script src="../public/js/add_sorting_production.js"></script>
<script src="../public/js/sorting_form.js"></script>

<?php
	unset($_SESSION['sorting_prod']);
	include_once "../footer.php";
?>