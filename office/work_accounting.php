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
						<form id="" action="" method="POST">
							<div class="form-group row">
								<div class="col-md-12">
								<?php
									echo "<table class='table table-bordered'>";
									echo "<thead class='thead-default table-active'><tr>";

									//Calendar header
									foreach($daysOfWeek as $day)
									{
										echo "<th>$day</th>";
									}

									echo "</tr></thead><tr>";

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
										// echo "<td id='$date'>$currentDay<input class='form-control form-control' type='text' name='working_hours[$currentDayIndex]'></td>";
										echo "<td><div class='input-group'>
												<div class='input-group-prepend'>
													<span class='input-group-text'>$currentDay</span>
												</div>
												<input type='text' class='form-control' name='working_hours[$currentDayIndex]' id='$date'>
											</div></td>";
	

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

									echo "</tr></table>";
								?>				
								</div>
							</div>
							<div class="form-group row">
								<div class="col-md-3 offset-md-3">
									<button class="btn btn-info" type="submit" name="submit">Saglabāt</button>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>

<?php
	unset($_SESSION['employee_times']);
	include_once "../footer.php";
?>