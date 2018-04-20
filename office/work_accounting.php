<?php
	include_once "../header.php";
	include_once "../includes/validate.class.php";
	include_once "../includes/employee.class.php";
	include_once "../includes/office.class.php";

	if(!isset($_SESSION['id']) && !isset($_SESSION['role']))	//Adding new beam size possible if user is logged in
	{
		header("Location: 404");
		exit();
	}

	if((($_SESSION['role'] != "a") && ($_SESSION['role'] != "p")) || ($_SESSION['active'] != 1))	//Check if user have permission
	{
		header("Location: 404");
		exit();
	}

	//Check if ID and period is set
	if(!isset($_GET['id']) || !isset($_GET['period']))
	{
		header("Location: 404");
		exit();
	}

	//Check if User with ID exists in database
	$user_id = $_GET['id'];
	if(!Office::ExistsBureauEmployeeWithID($user_id))
	{
		header("Location: 404");
		exit();
	}

	//Checks if year and month is correct
	$period = $_GET['period'];
	if(!Validate::IsValidPeriod($period))
	{
		header("Location: 404");
		exit();
	}

	if(isset($_SESSION['employee_times']))
	{
		extract($_SESSION['employee_times']);
	}

	//Returns employees data
	$employee = Employee::GetEmployeesData($user_id);

	$period_month = date('F', strtotime($period));
	$period_year = date('Y', strtotime($period));
	$month_index = date('n', strtotime($period));


	//CALENDAR OF MONTH
	//Array of week days
	$daysOfWeek = array('P','O','T','C','P','S','Sv');

	//Get first day of month timestamp
	$firstDayOfMonth = mktime(0, 0, 0, $month_index, 1, $period_year);

	//Number of days in month
	$numberDays = date('t', $firstDayOfMonth);

	//Retrieve information about first day of the month
	$dateComponents = getdate($firstDayOfMonth);

	//Index value of the first day
	$dayOfWeek = $dateComponents['wday'];
	if($dayOfWeek == 0)
	{
		$dayOfWeek = 6;
	}
	else
	{
		$dayOfWeek = $dayOfWeek - 1;
	}

	//Current day starts with 1
	$currentDay = 1;

	//Month index with leading zero
	$month = str_pad($month_index, 2, "0", STR_PAD_LEFT);

	//Returns all days worked statistic and nonworked statistic
	$days_worked = Office::BureauEmployeeWorkingStatistic($user_id, $period);
	$days_nonworked = Office::BureauEmployeeNonWorkingStatistic($user_id, $period);

	$weekday_count = Office::Weekdays($month_index, $period_year);
?>

	<!-- Update employees work times accounting -->
	<div class="container">
		<div class="row cont-space">
			<div class="col-md-12">
				<div id="message">
					<?php include "../message.php"; ?>
				</div>
				<div class="card">
					<div class="card-body">
						<h4 class="card-title text-center">
							Darba uzskaite: <u>'<?=$employee['name']?> <?=$employee['last_name']?>'</u>. 
							<?php
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
								echo ".";
							?>
						</h4>
						<div class="col-md-12">
							<div class="row">
								<div class="col-md-6 alert alert-success mb-3" role="alert">
									<h4 class="alert-heading">Apzīmējumi:</h4>
									<ul>
										<li>Stundas: <b>1</b>, <b>2</b>, <b>3</b>, <b>4</b>, <b>5</b>, <b>6</b>, <b>7</b>, <b>8</b></li>
										<li>Atvaļinājums: <b>A</b></li>
										<li>Neierašanās / Kavējums: <b>N</b></li>
										<li>Slimības lapa: <b>S</b></li>
										<li>Cits iemesls: <b>C</b></li>
									</ul>
									<hr>
									<ul>
										<li>Virsstundas: <b>1</b>, <b>2</b>, <b>3</b>, <b>4</b>, <b>5</b>, <b>6</b>, <b>7</b>, <b>8</b></li>
									</ul>
								</div>
								<div class="col-md-6">
									<h4>Statistika:</h4>
									<ul>
										<li>Darba dienas: <?=$weekday_count?> / Stundas kopā:  <?=$weekday_count*8?></li>
										<hr>
										<li>Darbā ierašanās dienas: <?=$days_worked['working_days']?></li>
										<li>Nostrādātās stundas: <?=$days_worked['working_hours']?></li>
										<li>Darbā ierašanās dienas: <?=$days_worked['overtime_hours']?></li>
										<li>Stundas kopā: <?=$days_worked['working_hours']+$days_worked['overtime_hours']?></li>
										<hr>
										<li>Darbā neierašanās dienas: <?=$days_nonworked['nonworking_days']?></li>
										<li>Atvaļinājums (dienas): <?=$days_nonworked['vacation']?></li>
										<li>Slimības lapa (dienas): <?=$days_nonworked['sick_leave']?></li>
										<li>Kavējums (dienas): <?=$days_nonworked['nonattendance']?></li>
										<li>Cits iemesls (dienas): <?=$days_nonworked['pregnancy']?></li>
									</ul>
								</div>
							</div>
						</div>
						<form id="work_accounting_form" action="new_work_accounting" method="POST">
							<input type="hidden" name="employee_id" value="<?=$employee['id']?>">
							<input type="hidden" name="period" value="<?=$period?>">
							<div class="form-group row">
								<div class="col-md-12">
									<table class='table table-bordered'>
										<thead class='thead-default table-active'>
											<tr>
										<?php
											foreach($daysOfWeek as $day)
											{
												echo "<th>$day</th>"; //Calendar header
											}
										?>
											</tr>
										</thead>
										<tbody>
											<tr>
										<?php
											//Colspan to the starting month day (7 columns)
											if($dayOfWeek > 0)
											{ 
												echo "<td colspan='$dayOfWeek' class='table-light'></td>"; 
											}

											//Goes thru all month days
											while($currentDay <= $numberDays)
											{
												//New week starts in new table row
												if($dayOfWeek == 7)
												{
													$dayOfWeek = 0;
													echo "</tr><tr>";
												}

												$currentDayRel = str_pad($currentDay, 2, "0", STR_PAD_LEFT);
												$currentDayIndex = $currentDay - 1;

												//Date index
												$date = "$period_year-$month-$currentDayRel";
												$working_hours_date = "";
												$overtime_hours_date = "";
												

												if(isset($_SESSION['employee_times']))
												{
													$working_hours_date = $working_hours[$currentDayIndex];
													$overtime_hours_date = $overtime_hours[$currentDayIndex];
												}
												else
												{
													$nonworkig_times = Office::BureauEmployeeNonWorkingTimes($user_id, $date);
													$working_times = Office::BureauEmployeeWorkingTimes($user_id, $date);
													if(isset($nonworkig_times))
													{
														$working_hours_date = $nonworkig_times['nonworking'];
														$overtime_hours_date = "";
													}
													else if(isset($working_times))
													{
														$working_hours_date = $working_times['working_hours'];
														$overtime_hours_date = $working_times['overtime_hours'];
													}
													else
													{
														$working_hours_date = "";
														$overtime_hours_date = "";
													}
												}
										?>
												<td>
													
													<div class="btn-group">
														<button type="button" class="btn btn-secondary"><?=$currentDay?></button>
													</div>
													<div class="input-group mt-1">
														<div class="input-group-prepend">
															<span class="input-group-text">Stundas</span>
														</div>
														<input type="text" class="form-control" name="working_hours[<?=$currentDayIndex?>]" id="<?=$date?>" value="<?=$working_hours_date?>">
													</div>
													<div class="input-group mt-1">
														<div class="input-group-prepend">
															<span class="input-group-text">
																<abbr title="Virsstundas">Virsst.</abbr>
															</span>
														</div>
														<input type="number" min="1" max="8" step="1" class="form-control" name="overtime_hours[<?=$currentDayIndex?>]" id="<?=$date?>" value="<?=$overtime_hours_date?>">
													</div>
												</td>
										<?php
												//Increases variables
												$currentDay++;
												$dayOfWeek++;
											}

											//Colspan to the ending month day
											if($dayOfWeek != 7)
											{ 
												$remainingDays = 7 - $dayOfWeek;
												echo "<td colspan='$remainingDays' class='table-light'></td>"; 
											}
										?>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
							<div class="form-group row">
								<div class="col-md-2 ml-auto">
									<button class="btn btn-info" type="submit" name="submit">Saglabāt</button>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>

<script src="../public/js/work_accounting_form.js"></script>

<?php
	unset($_SESSION['employee_times']);
	include_once "../footer.php";
?>