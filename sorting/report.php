<?php
	include_once "../header.php";
	include_once "../includes/employee.class.php";
	include_once "../includes/manager.class.php";
	include_once "../includes/validate.class.php";

	if(!isset($_SESSION['id']) && !isset($_SESSION['role']))	//View data only allowed when user is logged in
	{
		header("Location: 404");
		exit();
	}

	if((($_SESSION['role'] != "a") && ($_SESSION['role'] != "p")) || ($_SESSION['active'] != 1))	//Check if user have permission
	{
		header("Location: 404");
		exit();
	}

	if(!isset($_GET['id']) || !isset($_GET['period']) || !isset($_GET['s']))		//Check if user ID and month (period) is set
	{
		header("Location: 404");
		exit();
	}

	//Check if User with ID exists in database
	$user_id = $_GET['id'];
	if(!Employee::ExistsEmployeeWithID($user_id))
	{
		header("Location: 404");
		exit();
	}

	//Check if period is correct
	$period = $_GET['period'];
	if(!Validate::IsValidPeriod($period))
	{
		header("Location: 404");
		exit();
	}
	$serial_number = $_GET['s'];

	//Returns employees data
	$employee = Employee::GetEmployeesData($user_id);

	//Get month number from period
	$period_year_number = date('y', strtotime($period));

	//Returns employees positions
	$positions = Manager::EmployeePositions($employee['id']);

	//Returns employees sorted productions all capacities
	$sorted_capacity = Manager::GetSortingEmployeeProductionsSortedCapacity($period, $employee['id']);

	//Returns employees hours and overtime hours for lengthening capacity
	$stretch_hours = Manager::GetSortingEmployeeProductionsStretchedHoursWorked($period, $employee['id']);
?>
	
	<!-- Show report of employee -->
	<div class="container">
		<div class="row cont-space">
			<div class="col-md-12">
				<div id="message">
					<?php include "../message.php"; ?>
				</div>
				<div class="card">
					<div class="card-header">
						<button class="float-right" onclick="printPage()">Printēt!</button>
					</div>
					<div class="card-body row">
						<div class="offset-md-1 col-md-6">
							<p class="font-weight-bold">SIA "Rīgas Meži" <br> Struktūrvienība: kokzāģētava "Norupe"</p>
						</div>
						<div class="col-md-5">
							<p class="font-weight-normal">Apstiprinu: <br> Kokzāģētavas vadītājs (Vārds Uzvārds)</p>
						</div>
						<div class="offset-md-1 col-md-6">
							<p class="font-weight-bold">
								DARBU NODOŠANAS - PIEŅEMŠANAS AKTS Nr. DNA
								<?=$serial_number?> /420-<?=$period_year_number?>
							</p>
						</div>
						<div class="col-md-5">
							<p class="font-weight-normal">
								Rīkojums SRM-16-1277-rp- no 28.09.2016.
							</p>								
						</div>
						<div class="offset-md-1 col-md-11">
							<p class="font-weight-bold">
								<u>
								<?php
									$period_month = date('F', strtotime($period));
									$period_year = date('Y', strtotime($period));

									echo $period_year.". gada ";
									if($period_month == "January")
									{
										echo "janvārī";
									}
									else if($period_month == "February")
									{
										echo "februārī";
									}
									else if($period_month == "March")
									{
										echo "martā";
									}
									else if($period_month == "April")
									{
										echo "aprīlī";
									}
									else if($period_month == "May")
									{
										echo "maijā";
									}
									else if($period_month == "June")
									{
										echo "jūnijā";
									}
									else if($period_month == "July")
									{
										echo "jūlijā";
									}
									else if($period_month == "August")
									{
										echo "augustā";
									}
									else if($period_month == "September")
									{
										echo "septembrī";
									}
									else if($period_month == "October")
									{
										echo "oktobrī";
									}
									else if($period_month == "November")
									{
										echo "novembrī";
									}
									else if($period_month == "December")
									{
										echo "decembrī";
									}
								?>
								</u>
							</p>
						</div>
						<div class="offset-md-1 col-md-4">
							<p class="font-weight-normal">
								<span class="h6 text-uppercase">
									<?=$employee['name']?> <?=$employee['last_name']?>
								</span>
								<br>
								<span class="font-italic">(darbinieka vārds, uzvārds)</span>
								<br>
								<?=$employee['person_id']?>
								<br>
								<span class="font-italic">(personas kods)</span>	
							</p>
						</div>
						<div class="col-md-7">
							<p class="font-weight-normal">
								Darbā ierašanās dienas: 
								<?php
									$worked = Manager::GetSortingEmployeeProductionsDaysWorked($period, $employee['id']);
									echo $worked['working'];
								?>
							</p>
						</div>
						<div class="col-md-12">
							<table class="table table-bordered table-hover">
								<thead class="thead-default table-active">
									<tr>
										<th>Nr.p.k</th>
										<th>Darba apraksts</th>
										<th>Mērvienība</th>
										<th>Apjoms</th>
										<th>Izcenojums par vienību EUR</th>
										<th>Summa, EUR</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<th>1</th>
										<td>Šķirošana no 0,0161 m3/gab. līdz &#8734;</td>
										<td>m<sup>3</sup></td>
										<td><?=$sorted_capacity['cap_one']?></td>
										<td>1,35</td>
										<td>
										<?php
											echo round(($sorted_capacity['cap_one']*1.35), 2);
										?>
										</td>
									</tr>
									<tr>
										<th>2</th>
										<td>Šķirošana no 0,009 m3/gab. līdz 0,0160 m3/gab.</td>
										<td>m<sup>3</sup></td>
										<td><?=$sorted_capacity['cap_two']?></td>
										<td>1,60</td>
										<td>
										<?php
											echo round(($sorted_capacity['cap_two']*1.60), 2);
										?>
										</td>
									</tr>
									<tr>
										<th>3</th>
										<td>Šķirošana līdz 0,0089 m3/gab.</td>
										<td>m<sup>3</sup></td>
										<td><?=$sorted_capacity['cap_three']?></td>
										<td>2,45</td>
										<td>
										<?php
											echo round(($sorted_capacity['cap_three']*2.45), 2);
										?>
										</td>
									</tr>
									<tr>
										<th>4</th>
										<td>Garināšana</td>
										<td>h</td>
										<td>
										<?php 
											if(!empty($stretch_hours['working_hours']))
											{
												echo $stretch_hours['working_hours'];
											}
										?>
										</td>
										<td>3,90</td>
										<td id="lengthening_hours">
										<?php 
											if(!empty($stretch_hours['working_hours']))
											{
												echo round(($stretch_hours['working_hours']*3.9), 2);
											}
										?>
										</td>
									</tr>
									<tr>
										<td colspan="3" class="text-right">Kopā m<sup>3</sup>:</td>
										<td><?=$sorted_capacity['total_cap']?></td>
										<td></td>
										<td id="summ">
										<?php
											$total_sum = (round(($sorted_capacity['cap_one']*1.35), 2) + round(($sorted_capacity['cap_two']*1.60), 2) + round(($sorted_capacity['cap_three']*2.45), 2));
											echo $total_sum;
										?>
										</td>
									</tr>
									<tr>
										<td colspan="4" class="text-right">
											Ražošanas efektivitātes likme:
										</td>
										<td>
											<div class="input-group">
												<input type="number" min="0" step="1" name="efficiency_rate" class="form-control" id="efficiency_rate" value="0">
												<div class="input-group-append">
													<span class="input-group-text">%</span>
												</div>
											</div>
										</td>
										<td id="percentage_summ">
										</td>
									</tr>
									<tr>
										<td colspan="5" class="text-right">
											<b><u>Kopā:</u></b>
										</td>
										<td id="total_summ">
											
										</td>
									</tr>
								</tbody>
							</table>
						</div>
						<div class="col-md-6 pt-5 px-5">
							<p>
								Darbu nodeva <input type="text" class="signature"><br><br>
							<?php
								$resultstr = array();
								
								foreach($positions as $position)
								{
									$resultstr[] = " ".$position['name'];
								}
								echo implode(",",$resultstr);	//Adding comma after many positions
								echo ': '.$employee['name'].' '.$employee['last_name']; 
							?>
							</p>
						</div>
						<div class="col-md-6 pt-5 px-5">
							<p>
								Darbu pieņēma <input type="text" class="signature"><br><br>
								Šķirotavas vadītājs: Vārds Uzvārds
							</p>
						</div>

					</div>
				</div>
			</div>
		</div>
	</div>

<script src="../public/js/sorting_employee_report.js"></script>

<?php
	include_once "../footer.php";
?>