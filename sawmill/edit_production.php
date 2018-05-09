<?php

	include_once "../header.php";
	include_once "../includes/manager.class.php";
	include_once "../includes/sawmill_production.class.php";
	include_once "../includes/working_times.class.php";
	include_once "../includes/times.class.php";

	if(!isset($_SESSION['id']) && !isset($_SESSION['role']))	//Check if user is logged in
	{
		header("Location: 404");
		exit();
	}
	
	if((($_SESSION['role'] != "a") && ($_SESSION['role'] != "p") && ($_SESSION['role'] != "l")) || ($_SESSION['active'] != 1))	//Check if user have permission
	{
		header("Location: 404");
		exit();
	}

	if(!isset($_GET['id']))		//Check if ID is set
	{
		header("Location: 404");
		exit();
	}

	//Check if production with ID exists in database
	$sawmill_production_id = $_GET['id'];
	if(!SawmillProduction::ExistsProductionWithID($sawmill_production_id))
	{
		header("Location: 404");
		exit();
	}

	//Extract Session data
	if(isset($_SESSION['edit_sawmill_prod']))
	{
		extract($_SESSION['edit_sawmill_prod']);
	}

	//Returns all sawmill_productions data
	$production = SawmillProduction::GetSawmillProductionData($sawmill_production_id);

?>

	<!-- Update Sawmill Production data -->
	<div class="container">
		<div class="row cont-space">
			<div class="col-md-12">
				<div id="message">
					<?php include "../message.php"; ?>
				</div>
				<div class="card">
					<div class="card-body">
						<h4 class="card-title text-center">
							Labot produckiju ar Pavadzīmes Nr: <u>'<?=$production['invoice']?>'</u>. Datums: <?=$production['date']?>
							<a href="delete_production?id=<?=$production['id']?>&invoice=<?=$production['invoice']?>" class="btn btn-danger float-right">
								Dzēst produkciju!
							</a>
						</h4>

						<form id="edit_sawmill_production_form" action="update_production" method="POST">

							<input type="hidden" name="sawmill_production_id" value="<?=$production['id']?>">
							<input type="hidden" name="sawmill_production_invoice" value="<?=$production['invoice']?>">

							<div class="form-group row">
								<label class="col-md-2 offset-md-1 col-form-label">
									Datums
									<span class="text-danger" title="Šis lauks ir obligāts">
										&#10033;
									</span>
								</label>
								<div class="col-md-5">
									<input class="form-control datepicker" type="text" name="date" aria-describedby="dateArea" placeholder="2000/01/01" value="<?php echo isset($_SESSION['edit_sawmill_prod']) ? $date : $production['date']; ?>">
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
											<input class="form-control" type="time" name="time_from" aria-describedby="timeFromArea" value="<?php echo isset($_SESSION['edit_sawmill_prod']) ? $time_from : $production['time_from']; ?>">
										</div>
										<div class="col-md-6">
											<input class="form-control" type="time" name="time_to" aria-describedby="timeFromArea" value="<?php echo isset($_SESSION['edit_sawmill_prod']) ? $time_to : $production['time_to']; ?>">
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
									<input class="form-control" type="text" name="invoice" aria-describedby="invoiceArea" value="<?php echo isset($_SESSION['edit_sawmill_prod']) ? $invoice : $production['invoice']; ?>">
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
									Apaļkoku skaits
									<span class="text-danger" title="Šis lauks ir obligāts">
										&#10033;
									</span>
								</label>
								<div class="col-md-5">
									<input class="form-control" type="text" name="beam_count" aria-describedby="beamCountArea" id="beam_count_input" value="<?php echo isset($_SESSION['edit_sawmill_prod']) ? $beam_count : $production['beam_count']; ?>">
									<small id="beamCountArea" class="form-text text-muted">
										* Satur tikai ciparus, kopējo (gab) skaitu *
									</small>
								</div>
								<div class="col-md-4">
									<?php
										if(isset($_SESSION['beam_count']))
										{
									?>
										<div class="alert alert-danger alert-size" role="alert">
											<?=$_SESSION['beam_count']?>
										</div>
									<?php
											unset($_SESSION['beam_count']);
										}
									?>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-md-2 offset-md-1 col-form-label">
									Kubatūras izmērs
									<span class="text-danger" title="Šis lauks ir obligāts">
										&#10033;
									</span>
								</label>
								<div class="col-md-5">

								<?php
									$sizes = Manager::BeamSizes();
								?>
									<select class="custom-select" name="sizes" id="beam_size_select">
										<option value="" style="font-weight:bold;">Izvēlieties kubatūras izmēru</option>
								<?php
									foreach($sizes as $size)
									{
										if($size['id'] == $production['beam_size_id'])
										{
											echo '<option value="'.$size['id'].'" selected>'.$size['size'].'</option>';
										}
										else
										{
											echo '<option value="'.$size['id'].'">'.$size['size'].'</option>';
										}
									}
								?>
									</select>

								</div>
								<div class="col-md-4">
									<?php
										if(isset($_SESSION['beam_size']))
										{
									?>
										<div class="alert alert-danger alert-size" role="alert">
											<?=$_SESSION['beam_size']?>
										</div>
									<?php
											unset($_SESSION['beam_size']);
										}
									?>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-md-2 offset-md-1 control-label">
									Apaļkoku tilpums
								</label>
								<div class="col-md-5">
									<p class="form-control-static" id="beam_capacity"> m<sup>3</sup></p>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-md-2 offset-md-1 col-form-label">
									Zāģmatariālu skaits
									<span class="text-danger" title="Šis lauks ir obligāts">
										&#10033;
									</span>
								</label>
								<div class="col-md-5">
									<input class="form-control" type="text" name="lumber_count" aria-describedby="lumberCountArea" value="<?php echo isset($_SESSION['edit_sawmill_prod']) ? $lumber_count : $production['lumber_count']; ?>">
									<small id="lumberCountArea" class="form-text text-muted">
										* Satur tikai ciparus, kopējo (gab) skaitu *
									</small>
								</div>
								<div class="col-md-4">
									<?php
										if(isset($_SESSION['lumber_count']))
										{
									?>
										<div class="alert alert-danger alert-size" role="alert">
											<?=$_SESSION['lumber_count']?>
										</div>
									<?php
											unset($_SESSION['lumber_count']);
										}
									?>
								</div>	
							</div>
							<div class="form-group row">
								<label class="col-md-2 offset-md-1 col-form-label">
									Zāģmatariālu tilpums
									<span class="text-danger" title="Šis lauks ir obligāts">
										&#10033;
									</span>
								</label>
								<div class="col-md-5">
									<input class="form-control" type="number" min="0" step="0.001" name="lumber_capacity" aria-describedby="lumberCapacityArea" placeholder="0,000" value="<?php echo isset($_SESSION['edit_sawmill_prod']) ? $lumber_capacity : $production['lumber_capacity']; ?>"> 
									<small id="lumberCapacityArea" class="form-text text-muted">
										* Satur tikai ciparus, kopējo tilpumu m<sup>3</sup>. (Maksimums 3 cipari aiz komata) *
									</small>
								</div>
								<div class="col-md-4">
									<?php
										if(isset($_SESSION['lumber_capacity']))
										{
									?>
										<div class="alert alert-danger alert-size" role="alert">
											<?=$_SESSION['lumber_capacity']?>
										</div>
									<?php
											unset($_SESSION['lumber_capacity']);
										}
									?>
								</div>	
							</div>
							<div class="form-group row has-success">
								<label class="col-md-2 offset-md-1 col-form-label">
									Citas piezīmes
								</label>
								<div class="col-md-5">
									<textarea class="form-control rounded-0" name="note" rows="3" aria-describedby="noteArea"><?php echo isset($_SESSION['edit_sawmill_prod']) ? $note : $production['note']; ?></textarea>
								</div>
								<div class="col-md-4">
									<?php
										if(isset($_SESSION['note']))
										{
									?>
										<div class="alert alert-danger alert-size" role="alert">
											<?=$_SESSION['note']?>
										</div>
									<?php
											unset($_SESSION['note']);
										}
									?>
								</div>	
							</div>
							<div class="form-group row" id="maintenance_select">
							<?php
								$all_maintenances = SawmillProduction::GetAllMaintenances($production['id']);
								if($production['count'] != 0)
								{
									$i = 0;
									foreach($all_maintenances as $all_maintenance)
									{	
										if($i == 0)
										{
											$i++;
							?>
											<label class="col-md-2 offset-md-1 col-form-label">
												Remontlaiks
											</label>
											<div class="col-md-1">
												<input class="form-control maintenance_times" type="text" name="maintenance_times[]" aria-describedby="lumberCapacityArea" placeholder="Laiks" value="<?php echo isset($_SESSION['edit_sawmill_prod']) ? $maintenance_times[0] : $all_maintenance['time']; ?>">
											</div>
											<div class="col-md-4">
												<input class="form-control" type="text" name="maintenance_notes[]" aria-describedby="lumberCapacityArea" placeholder="Piezīme" value="<?php echo isset($_SESSION['edit_sawmill_prod']) ? $maintenance_notes[0] : $all_maintenance['note']; ?>">
											</div>
											<div class="col-md-1">
												<button type="button" name="add" id="add" class="btn btn-success">+</button>
											</div>
											<div class="col-md-3">
												<?php
													if(isset($_SESSION['maintenance']))
													{
												?>
													<div class="alert alert-danger alert-size" role="alert">
														<?=$_SESSION['maintenance']?>
													</div>
												<?php
														unset($_SESSION['maintenance']);
													}
												?>
											</div>
									<?php
										}
										else
										{
									?>
											<div class="offset-md-3 col-md-1">
												<input class="form-control mt-2 maintenance_times_class" type="text" name="maintenance_times[]" placeholder="Laiks" value="<?=$all_maintenance['time']?>">
											</div>
											<div class="col-md-4">
												<input class="form-control mt-2 maintenance_notes_class" type="text" name="maintenance_notes[]" placeholder="Piezīme" value="<?=$all_maintenance['note']?>">
											</div>
											<div class="col-md-1">
												<button type="button" class="btn btn-danger remove mt-2">X</button>
											</div>
											<div class="col-md-3">
											</div>
							<?php
										}
									}
								}
								else
								{
							?>
									<label class="col-md-2 offset-md-1 col-form-label">
										Remontlaiks
									</label>
									<div class="col-md-1">
										<input class="form-control maintenance_times" type="text" name="maintenance_times[]" aria-describedby="lumberCapacityArea" placeholder="Laiks" value="<?php echo isset($_SESSION['edit_sawmill_prod']) ? $maintenance_times[0] : ''; ?>">
									</div>
									<div class="col-md-4">
										<input class="form-control" type="text" name="maintenance_notes[]" aria-describedby="lumberCapacityArea" placeholder="Piezīme" value="<?php echo isset($_SESSION['edit_sawmill_prod']) ? $maintenance_notes[0] : ''; ?>">
									</div>
									<div class="col-md-1">
										<button type="button" name="add" id="add" class="btn btn-success">+</button>
									</div>
									<div class="col-md-3">
										<?php
											if(isset($_SESSION['maintenance']))
											{
										?>
											<div class="alert alert-danger alert-size" role="alert">
												<?=$_SESSION['maintenance']?>
											</div>
										<?php
												unset($_SESSION['maintenance']);
											}
										?>
									</div>
							<?php
								}
							?>
							</div>
							<div class="form-group row" id="shift_select">
								<label class="col-md-2 offset-md-1 col-form-label">
									Maiņa
									<span class="text-danger" title="Šis lauks ir obligāts">
										&#10033;
									</span>
								</label>
								<div class="col-md-5">
								<?php
									$shifts = Manager::AllShifts();
									$workers_shift = SawmillProduction::GetSawmillProductionWorkersShift($production['id']);
								?>
									<select class="custom-select" name="shifts" id="employees_shift">
										<option value="" style="font-weight:bold;">Izvēlieties maiņu</option>
								<?php
									foreach($shifts as $shift)
									{
										if($shift['shift'] == $workers_shift['shift'])
										{
											echo '<option value="'.$shift['shift'].'" selected>'.$shift['shift'].'</option>';
										}
										else
										{
											echo '<option value="'.$shift['shift'].'">'.$shift['shift'].'</option>';
										}
									}
								?>
									</select>

								</div>
								<div class="col-md-4" id="employee_table_error">
									<?php
										if(isset($_SESSION['shift']))
										{
									?>
										<div class="alert alert-danger alert-size" role="alert">
											<?=$_SESSION['shift']?>
										</div>
									<?php
											unset($_SESSION['shift']);
										}
									?>
								</div>
								<div class="col-md-12 mt-3" id="table_show">
								<?php
									$this_date = date('Y-m'); //This year and month
									$employees = Manager::GetEmployeesByShift($workers_shift['shift'], $this_date);
								?>
									<table class="table table-bordered table-hover">
										<thead class="thead-default table-active">
											<tr>
												<th>Nr. p. k</th>
												<th>Vārds</th>
												<th>Uzvārds</th>
												<th>Nostrādātas stundas</th>
											</tr>
										</thead>
										<tbody>
								<?php
										$i = 1;
										foreach($employees as $employee)
										{
											$worked_hours = WorkingTimes::GetWorkersWorkingTime($employee['id'], $production['date'], $production['invoice'], $production['datetime']);
											$nonworked = Times::GetWorkersNonWorkingTime($employee['id'], $production['date'], $production['invoice'], $production['datetime']);
								?>
											<tr>
												<input type="hidden" name="id[]" value="<?=$employee['id']?>">
												<th><?=$i++?></th>
												<td><?=$employee['name']?></td>
												<td><?=$employee['last_name']?></td>
												<td>
													<select class="custom-select working_class" name="working[]">
														<option selected value="" style="font-weight:bold;">Izvēlēties nostrādātās stundas vai citu iemeslu</option>
														<option value="1" <?php echo ($worked_hours['working_hours'] == "1") ? 'selected' : ''; ?> >1 Stunda</option>
														<option value="2" <?php echo ($worked_hours['working_hours'] == "2") ? 'selected' : ''; ?> >2 Stundas</option>
														<option value="3" <?php echo ($worked_hours['working_hours'] == "3") ? 'selected' : ''; ?> >3 Stundas</option>
														<option value="4" <?php echo ($worked_hours['working_hours'] == "4") ? 'selected' : ''; ?> >4 Stundas</option>
														<option value="5" <?php echo ($worked_hours['working_hours'] == "5") ? 'selected' : ''; ?> >5 Stundas</option>
														<option value="6" <?php echo ($worked_hours['working_hours'] == "6") ? 'selected' : ''; ?> >6 Stundas</option>
														<option value="7" <?php echo ($worked_hours['working_hours'] == "7") ? 'selected' : ''; ?> >7 Stundas</option>
														<option value="8" <?php echo ($worked_hours['working_hours'] == "8") ? 'selected' : ''; ?> >8 Stundas</option>
														<option value="9" <?php echo ($nonworked['vacation'] == 'A') ? 'selected' : ''; ?> >Atvaļinājums</option>
														<option value="10" <?php echo ($nonworked['sick_leave'] == 'S') ? 'selected' : ''; ?> >Slimības lapa</option>
														<option value="11" <?php echo ($nonworked['nonattendace'] == 'N') ? 'selected' : ''; ?> >Neapmeklējums</option>
													</select>
												</td>
											</tr>
								<?php
										}
								?>
										</tbody>
									</table>
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

<script src="../public/js/edit_sawmill_production.js"></script>
<script src="../public/js/edit_sawmill_production_form.js"></script>
<script src="../public/js/dates.js"></script>

<?php
	unset($_SESSION['edit_sawmill_prod']);
	include_once "../footer.php";
?>