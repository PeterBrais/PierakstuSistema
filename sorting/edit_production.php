<?php

	include_once "../header.php";
	include_once "../includes/manager.class.php";
	include_once "../includes/sorting_production.class.php";
	include_once "../includes/working_times.class.php";
	include_once "../includes/times.class.php";


	if(!isset($_SESSION['id']) && !isset($_SESSION['role']))	//Check if user is logged in
	{
		header("Location: 404");
		exit();
	}

	if((($_SESSION['role'] != "a") && ($_SESSION['role'] != "p") && ($_SESSION['role'] != "l")) || ($_SESSION['active'] != 1))	//Check if user have permission to edit data
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
	$sorting_production_id = $_GET['id'];
	if(!SortingProduction::ExistsNonReservedProductionWithID($sorting_production_id))
	{
		header("Location: 404");
		exit();
	}

	//Extract Session data
	if(isset($_SESSION['edit_sorting_prod']))
	{
		extract($_SESSION['edit_sorting_prod']);
	}

	//Returns all sorting_productions data
	$production = SortingProduction::GetSortingProductionData($sorting_production_id);

	//This year and month
	$this_date = date('Y-m');
?>

	<!-- Update Sorting Production data -->
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
							<a href="delete_production?id=<?=$production['id']?>" class="btn btn-danger float-right">
								Dzēst produkciju!
							</a>
						</h4>

						<form id="edit_sorting_production_form" action="update_production" method="POST">

							<input type="hidden" name="sorting_production_id" value="<?=$production['id']?>">

							<div class="form-group row">
								<label class="col-md-2 offset-md-1 col-form-label">
									Datums
									<span class="text-danger" title="Šis lauks ir obligāts">
										&#10033;
									</span>
								</label>
								<div class="col-md-5">
									<input class="form-control" type="text" name="date" aria-describedby="dateArea" placeholder="2000/01/01" value="<?php echo isset($_SESSION['edit_sorting_prod']) ? $date : $production['date']; ?>">
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
											<input class="form-control" type="time" name="time_from" aria-describedby="timeFromArea" value="<?php echo isset($_SESSION['edit_sorting_prod']) ? $time_from : $production['time_from']; ?>">
										</div>
										<div class="col-md-6">
											<input class="form-control" type="time" name="time_to" aria-describedby="timeFromArea" value="<?php echo isset($_SESSION['edit_sorting_prod']) ? $time_to : $production['time_to']; ?>">
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
									<input class="form-control" type="text" name="invoice" aria-describedby="invoiceArea" value="<?php echo isset($_SESSION['edit_sorting_prod']) ? $invoice : $production['invoice']; ?>">
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
											<input class="form-control" type="number" min="0" name="thick" aria-describedby="timeFromArea" placeholder="Biezums" id="thickeness" value="<?php echo isset($_SESSION['edit_sorting_prod']) ? $thick : $production['thickness']; ?>">
										</div>
										<div class="col-md-4">
											<input class="form-control" type="number" min="0" name="width" aria-describedby="timeFromArea" placeholder="Platums" id="width" value="<?php echo isset($_SESSION['edit_sorting_prod']) ? $width : $production['width']; ?>">
										</div>
										<div class="col-md-4">
											<input class="form-control" type="number" min="0" name="length" aria-describedby="timeFromArea" placeholder="Garums" id="length" value="<?php echo isset($_SESSION['edit_sorting_prod']) ? $length : $production['length']; ?>">
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
									<input class="form-control" type="number" min="0" name="sawn_count" aria-describedby="sawnCountArea" id="sawn_count" placeholder="Kopējais skaits" value="<?php echo isset($_SESSION['edit_sorting_prod']) ? $sawn_count : $production['count']; ?>">
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
									Brāķu skaits
								</label>
								<div class="col-md-5">
									<input class="form-control" type="number" min="0" name="defect_count" aria-describedby="sawnCountArea" id="defect_count" placeholder="Brāķu skaits" value="<?php echo isset($_SESSION['edit_sorting_prod']) ? $defect_count : $production['defect_count']; ?>">
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
							<?php
								$all_sorted_productions = SortingProduction::GetAllSortedProductionData($production['id']);

								$i = 0;
								foreach($all_sorted_productions as $all_sorted_production)
								{
									if($i == 0)
									{
										$i++;
							?>
										<div class="form-group row">
											<label class="col-md-2 offset-md-1 col-form-label">
												Veids
												<span class="text-danger" title="Šis lauks ir obligāts">
													&#10033;
												</span>
											</label>
											<div class="col-md-5">
												<select class="custom-select sorting_prod_type" name="sorted_types[]">
													<option value="" <?php echo ((isset($_SESSION['edit_sorting_prod']) && $sorted_types[0] == "0") || ($all_sorted_production['type'] == "0")) ? 'selected' : ''; ?> >Izvēlieties šķirošanas veidu</option>
													<option value="1" <?php echo ((isset($_SESSION['edit_sorting_prod']) && $sorted_types[0] == "1") || ($all_sorted_production['type'] == "S")) ? 'selected' : ''; ?> >Šķirots</option>
													<option value="2" <?php echo ((isset($_SESSION['edit_sorting_prod']) && $sorted_types[0] == "2") || ($all_sorted_production['type'] == "G")) ? 'selected' : ''; ?> >Garināts</option>
													<option value="3" <?php echo ((isset($_SESSION['edit_sorting_prod']) && $sorted_types[0] == "3") || ($all_sorted_production['type'] == "W")) ? 'selected' : ''; ?> >Mērcēts</option>
												</select>
											</div>
											<div class="col-md-4">
												<?php
													if(isset($_SESSION['sorted_types']))
													{
												?>
													<div class="alert alert-danger alert-size" role="alert">
														<?=$_SESSION['sorted_types']?>
													</div>
												<?php
														unset($_SESSION['sorted_types']);
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
												<input class="form-control sorted_counts" type="number" min="0" name="sorted_count[]" aria-describedby="sawnCountArea" placeholder="Kopējais skaits" value="<?php echo isset($_SESSION['edit_sorting_prod']) ? $sorted_count[0] : $all_sorted_production['count']; ?>">
												<small id="sawnCountArea" class="form-text text-muted">
													* Satur tikai ciparus, kopējo (gab) skaitu *
												</small>
											</div>
											<div class="col-md-4">
												<?php
													if(isset($_SESSION['sorted_count']))
													{
												?>
													<div class="alert alert-danger alert-size" role="alert">
														<?=$_SESSION['sorted_count']?>
													</div>
												<?php
														unset($_SESSION['sorted_count']);
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
														<input class="form-control sorted_thicknesses" type="number" min="0" name="sorted_thick[]" aria-describedby="timeFromArea" placeholder="Biezums" value="<?php echo isset($_SESSION['edit_sorting_prod']) ? $sorted_thick[0]: $all_sorted_production['thickness']; ?>">
													</div>
													<div class="col-md-4">
														<input class="form-control sorted_widths" type="number" min="0" name="sorted_width[]" aria-describedby="timeFromArea" placeholder="Platums" value="<?php echo isset($_SESSION['edit_sorting_prod']) ? $sorted_width[0]: $all_sorted_production['width']; ?>">
													</div>
													<div class="col-md-4">
														<input class="form-control sorted_lengths" type="number" min="0" name="sorted_length[]" aria-describedby="timeFromArea" placeholder="Garums" value="<?php echo isset($_SESSION['edit_sorting_prod']) ? $sorted_length[0]: $all_sorted_production['length']; ?>">
													</div>
												</div>
												<small id="timeFromArea" class="form-text text-muted">
													* Satur tikai ciparus *
												</small>
											</div>
											<div class="col-md-4">
												<?php
													if(isset($_SESSION['sorted_sizes']))
													{
												?>
													<div class="alert alert-danger alert-size" role="alert">
														<?=$_SESSION['sorted_sizes']?>
													</div>
												<?php
														unset($_SESSION['sorted_sizes']);
													}
												?>
											</div>
										</div>
										<div class="form-group row">
											<label class="col-md-2 offset-md-1 control-label">
												Tilpums
											</label>
											<div class="col-md-5">
												<p class="form-control-static sorted_capacities"> m<sup>3</sup></p>
											</div>
										</div>
										<div class="form-group row">
											<label class="col-md-2 offset-md-1 control-label">
												Tilpums / gab
											</label>
											<div class="col-md-5">
												<p class="form-control-static sorted_capacities_pieces"> m<sup>3</sup></p>
											</div>
										</div>
										<h5 class="text-center" style="<?php echo ((isset($_SESSION['edit_sorting_prod']) && $sorted_types[0] == "3") || (($all_sorted_production['type']) == "W")) ? 'display: none;' : ''; ?>" >Šķirotavas darbinieki</h5>
										<?php
											$employees = Manager::GetSortingEmployees($this_date);
										?>
											<table class="table table-bordered table-hover" style="<?php echo ((isset($_SESSION['edit_sorting_prod']) && $sorted_types[0] == "3") || (($all_sorted_production['type']) == "W")) ? 'display: none;' : ''; ?>" >
												<thead class="thead-default table-active">
													<tr>
														<th>Nr. p. k</th>
														<th>Vārds</th>
														<th>Uzvārds</th>
														<th>Nostrādātas stundas</th>
														<th>Cits iemesls</th>
													</tr>
												</thead>
												<tbody>
										<?php
												$k = 1;
												foreach($employees as $employee)
												{
													$worked_hours = WorkingTimes::GetWorkersWorkingTime($employee['id'], $production['date'], $all_sorted_production['id'], $production['datetime']);
													$nonworked = Times::GetWorkersNonWorkingTime($employee['id'], $production['date'], $all_sorted_production['id'], $production['datetime']);
										?>
													<tr>
														<input type="hidden" name="id[]" value="<?=$employee['id']?>">
														<th><?=$k++?></th>
														<td><?=$employee['name']?></td>
														<td><?=$employee['last_name']?></td>
														<td>
															<input class="form-control working_hours_class" type="number" min="1" max="24" step="1" name="working_hours[]" value="<?=$worked_hours['working_hours']?>">
														</td>
														<td>
															<select class="custom-select nonworking_hours_class" name="nonworking[]">
														<?php
															if($nonworked['vacation'] == 'A')
															{	
																echo '<option value="" style="font-weight:bold;">Izvēlēties citu iemeslu</option>';
																echo '<option value="1" selected>Atvaļinājums</option>';
																echo '<option value="2">Slimības lapa</option>';
																echo '<option value="3">Neapmeklējums</option>';
															}
															else if($nonworked['sick_leave'] == 'S')
															{
																echo '<option value="" style="font-weight:bold;">Izvēlēties citu iemeslu</option>';
																echo '<option value="1">Atvaļinājums</option>';
																echo '<option value="2" selected>Slimības lapa</option>';
																echo '<option value="3">Neapmeklējums</option>';
															}
															else if($nonworked['nonattendace'] == 'N')
															{
																echo '<option value="" style="font-weight:bold;">Izvēlēties citu iemeslu</option>';
																echo '<option value="1">Atvaļinājums</option>';
																echo '<option value="2">Slimības lapa</option>';
																echo '<option value="3" selected>Neapmeklējums</option>';
															}
															else
															{
																echo '<option value="" selected style="font-weight:bold;">Izvēlēties citu iemeslu</option>';
																echo '<option value="1">Atvaļinājums</option>';
																echo '<option value="2">Slimības lapa</option>';
																echo '<option value="3">Neapmeklējums</option>';
															}
														?>
															</select>
														</td>
													</tr>
										<?php
												}

										?>
												</tbody>
											</table>
							<?php
									}
									else
									{
							?>
										<div class="border-between"></div>
										<h5 class="text-center">Sašķirotā produkcija</h5>
										<div class="form-group row">
											<label class="col-md-2 offset-md-1 col-form-label">
												Veids
												<span class="text-danger" title="Šis lauks ir obligāts">
													&#10033;
												</span>
											</label>
											<div class="col-md-5">
												<select class="custom-select sorting_prod_type" name="sorted_types[]">
													<option value="" <?php echo ((isset($_SESSION['edit_sorting_prod']) && $sorted_types[0] == "0") || ($all_sorted_production['type'] == "0")) ? 'selected' : ''; ?> >Izvēlieties šķirošanas veidu</option>
													<option value="1" <?php echo ((isset($_SESSION['edit_sorting_prod']) && $sorted_types[0] == "1") || ($all_sorted_production['type'] == "S")) ? 'selected' : ''; ?> >Šķirots</option>
													<option value="2" <?php echo ((isset($_SESSION['edit_sorting_prod']) && $sorted_types[0] == "2") || ($all_sorted_production['type'] == "G")) ? 'selected' : ''; ?> >Garināts</option>
													<option value="3" <?php echo ((isset($_SESSION['edit_sorting_prod']) && $sorted_types[0] == "3") || ($all_sorted_production['type'] == "W")) ? 'selected' : ''; ?> >Mērcēts</option>
												</select>
											</div>
											<div class="col-md-4">
												<?php
													if(isset($_SESSION['sorted_types']))
													{
												?>
													<div class="alert alert-danger alert-size" role="alert">
														<?=$_SESSION['sorted_types']?>
													</div>
												<?php
														unset($_SESSION['sorted_types']);
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
												<input class="form-control sorted_counts" type="number" min="0" name="sorted_count[]" aria-describedby="sawnCountArea" placeholder="Kopējais skaits" value="<?php echo isset($_SESSION['edit_sorting_prod']) ? $sorted_count[0] : $all_sorted_production['count']; ?>">
												<small id="sawnCountArea" class="form-text text-muted">
													* Satur tikai ciparus, kopējo (gab) skaitu *
												</small>
											</div>
											<div class="col-md-4">
												<?php
													if(isset($_SESSION['sorted_count']))
													{
												?>
													<div class="alert alert-danger alert-size" role="alert">
														<?=$_SESSION['sorted_count']?>
													</div>
												<?php
														unset($_SESSION['sorted_count']);
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
														<input class="form-control sorted_thicknesses" type="number" min="0" name="sorted_thick[]" aria-describedby="timeFromArea" placeholder="Biezums" value="<?php echo isset($_SESSION['edit_sorting_prod']) ? $sorted_thick[0]: $all_sorted_production['thickness']; ?>">
													</div>
													<div class="col-md-4">
														<input class="form-control sorted_widths" type="number" min="0" name="sorted_width[]" aria-describedby="timeFromArea" placeholder="Platums" value="<?php echo isset($_SESSION['edit_sorting_prod']) ? $sorted_width[0]: $all_sorted_production['width']; ?>">
													</div>
													<div class="col-md-4">
														<input class="form-control sorted_lengths" type="number" min="0" name="sorted_length[]" aria-describedby="timeFromArea" placeholder="Garums" value="<?php echo isset($_SESSION['edit_sorting_prod']) ? $sorted_length[0]: $all_sorted_production['length']; ?>">
													</div>
												</div>
												<small id="timeFromArea" class="form-text text-muted">
													* Satur tikai ciparus *
												</small>
											</div>
											<div class="col-md-4">
												<?php
													if(isset($_SESSION['sorted_sizes']))
													{
												?>
													<div class="alert alert-danger alert-size" role="alert">
														<?=$_SESSION['sorted_sizes']?>
													</div>
												<?php
														unset($_SESSION['sorted_sizes']);
													}
												?>
											</div>
										</div>
										<div class="form-group row">
											<label class="col-md-2 offset-md-1 control-label">
												Tilpums
											</label>
											<div class="col-md-5">
												<p class="form-control-static sorted_capacities"> m<sup>3</sup></p>
											</div>
										</div>
										<div class="form-group row">
											<label class="col-md-2 offset-md-1 control-label">
												Tilpums / gab
											</label>
											<div class="col-md-5">
												<p class="form-control-static sorted_capacities_pieces"> m<sup>3</sup></p>
											</div>
											<div class="col-md-4">
												<button type="button" class="btn btn-danger remove mb-2">Noņemt</button>
											</div>
										</div>
										<h5 class="text-center" style="<?php echo (($all_sorted_production['type']) == "W") ? 'display: none;' : ''; ?>" >Šķirotavas darbinieki</h5>
										<?php
											$employees = Manager::GetSortingEmployees($this_date);
										?>
											<table class="table table-bordered table-hover" style="<?php echo (($all_sorted_production['type']) == "W") ? 'display: none;' : ''; ?>" >
												<thead class="thead-default table-active">
													<tr>
														<th>Nr. p. k</th>
														<th>Vārds</th>
														<th>Uzvārds</th>
														<th>Nostrādātas stundas</th>
														<th>Cits iemesls</th>
													</tr>
												</thead>
												<tbody>
										<?php
												$k = 1;
												foreach($employees as $employee)
												{
													$worked_hours = WorkingTimes::GetWorkersWorkingTime($employee['id'], $production['date'], $all_sorted_production['id'], $production['datetime']);
													$nonworked = Times::GetWorkersNonWorkingTime($employee['id'], $production['date'], $all_sorted_production['id'], $production['datetime']);
										?>
													<tr>
														<input type="hidden" name="id[]" value="<?=$employee['id']?>">
														<th><?=$k++?></th>
														<td><?=$employee['name']?></td>
														<td><?=$employee['last_name']?></td>
														<td>
															<input class="form-control working_hours_class" type="number" min="1" max="24" step="1" name="working_hours[]" value="<?=$worked_hours['working_hours']?>">
														</td>
														<td>
															<select class="custom-select nonworking_hours_class" name="nonworking[]">
														<?php
															if($nonworked['vacation'] == 'A')
															{	
																echo '<option value="" style="font-weight:bold;">Izvēlēties citu iemeslu</option>';
																echo '<option value="1" selected>Atvaļinājums</option>';
																echo '<option value="2">Slimības lapa</option>';
																echo '<option value="3">Neapmeklējums</option>';
															}
															else if($nonworked['sick_leave'] == 'S')
															{
																echo '<option value="" style="font-weight:bold;">Izvēlēties citu iemeslu</option>';
																echo '<option value="1">Atvaļinājums</option>';
																echo '<option value="2" selected>Slimības lapa</option>';
																echo '<option value="3">Neapmeklējums</option>';
															}
															else if($nonworked['nonattendace'] == 'N')
															{
																echo '<option value="" style="font-weight:bold;">Izvēlēties citu iemeslu</option>';
																echo '<option value="1">Atvaļinājums</option>';
																echo '<option value="2">Slimības lapa</option>';
																echo '<option value="3" selected>Neapmeklējums</option>';
															}
															else
															{
																echo '<option value="" selected style="font-weight:bold;">Izvēlēties citu iemeslu</option>';
																echo '<option value="1">Atvaļinājums</option>';
																echo '<option value="2">Slimības lapa</option>';
																echo '<option value="3">Neapmeklējums</option>';
															}
														?>
															</select>
														</td>
													</tr>
										<?php
												}
										?>
												</tbody>
											</table>
							<?php
									}
								}
							?>

							</div>
							<div class="form-group row">
								<div class="offset-md-3 col-md-4">
									<button type="button" name="add" id="add" class="btn btn-success">
										Pievienot vēl sašķiroto produkciju
									</button>
								</div>
							</div>
							<hr>

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

<script src="../public/js/edit_sorting_production.js"></script>
<script src="../public/js/edit_sorting_production_form.js"></script>

<?php
	unset($_SESSION['edit_sorting_prod']);
	include_once "../footer.php";
?>