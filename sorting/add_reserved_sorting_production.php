<?php
	include_once "../header.php";

	if(!isset($_SESSION['id']) && !isset($_SESSION['role']))	//Check if user is logged in
	{
		header("Location: 404");
		exit();
	}

	if((($_SESSION['role'] != "a") && ($_SESSION['role'] != "p") && ($_SESSION['role'] != "l")) || ($_SESSION['active'] != 1))	//Check if user have permission add new reserved sorting production
	{
		header("Location: 404");
		exit();
	}

	if(isset($_SESSION['reserved_sorting_prod']))
	{
		extract($_SESSION['reserved_sorting_prod']);
	}

	$today_date =  date('Y-m-d');
?>

	<!-- Add reserved sorting production -->
	<div class="container">
		<div class="row cont-space">
			<div class="col-md-12">
				<div id="message">
					<?php include "../message.php"; ?>
				</div>
				<div class="card">
					<div class="card-body">
						<h4 class="card-title text-center">Pievienot jaunu rezervēto šķirošanas produkciju</h4>

						<form id="reserved_sorting_form" action="new_reserved_sorting_production" method="POST">
							<div class="form-group row">
								<label class="col-md-2 offset-md-1 col-form-label">
									Datums
									<span class="text-danger" title="Šis lauks ir obligāts">
										&#10033;
									</span>
								</label>
								<div class="col-md-5">
									<input class="form-control datepicker" type="text" name="date" aria-describedby="dateArea" placeholder="2000/01/01" value="<?php echo isset($_SESSION['reserved_sorting_prod']) ? $date : $today_date; ?>">
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
											<input class="form-control timepicker" type="text" name="time_from" aria-describedby="timeFromArea" value="<?php echo isset($_SESSION['reserved_sorting_prod']) ? $time_from : ''; ?>">
										</div>
										<div class="col-md-6">
											<input class="form-control timepicker" type="text" name="time_to" aria-describedby="timeFromArea" value="<?php echo isset($_SESSION['reserved_sorting_prod']) ? $time_to : ''; ?>">
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
									<input class="form-control" type="text" name="invoice" aria-describedby="invoiceArea" value="<?php echo isset($_SESSION['reserved_sorting_prod']) ? $invoice : ''; ?>">
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
											<input class="form-control" type="number" min="0" name="thick" aria-describedby="timeFromArea" placeholder="Biezums" id="thickeness" value="<?php echo isset($_SESSION['reserved_sorting_prod']) ? $thick : ''; ?>">
										</div>
										<div class="col-md-4">
											<input class="form-control" type="number" min="0" name="width" aria-describedby="timeFromArea" placeholder="Platums" id="width" value="<?php echo isset($_SESSION['reserved_sorting_prod']) ? $width : ''; ?>">
										</div>
										<div class="col-md-4">
											<input class="form-control" type="number" min="0" name="length" aria-describedby="timeFromArea" placeholder="Garums" id="length" value="<?php echo isset($_SESSION['reserved_sorting_prod']) ? $length : ''; ?>">
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
									<input class="form-control" type="number" min="0" name="sawn_count" aria-describedby="sawnCountArea" id="sawn_count" placeholder="Kopējais skaits" value="<?php echo isset($_SESSION['reserved_sorting_prod']) ? $sawn_count : ''; ?>">
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

<script src="../public/js/add_reserved_sorting_production.js"></script>
<script src="../public/js/reserved_sorting_form.js"></script>
<script src="../public/js/dates.js"></script>

<?php
	unset($_SESSION['reserved_sorting_prod']);
	include_once "../footer.php";
?>