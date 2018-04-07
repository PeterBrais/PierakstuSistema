<?php
	include_once "../header.php";
	include_once "../includes/employee.class.php";
	include_once "../includes/manager.class.php";
	include_once "../includes/validate.class.php";

	if(!isset($_SESSION['id']) && !isset($_SESSION['role']))	//View data only allowed when user is logged in
	{
		header("Location: /");
		exit();
	}

	//Check if user have permission
	if(($_SESSION['role'] != "p") && ($_SESSION['role'] != "a") && ($_SESSION['active'] != 1))	
	{
		header("Location: /");
		exit();
	}

	if(!isset($_GET['id']) || !isset($_GET['period']))		//Check if user ID and month (period) is set
	{
		header("Location: show_sawmill_production");
		exit();
	}

	//Check if User with ID exists in database
	$user_id = $_GET['id'];
	if(!Employee::ExistsEmployeeWithID($user_id))
	{
		header("Location: show_sawmill_production");
		exit();
	}

	//Check if period is correct
	$period = $_GET['period'];
	if(!Validate::IsValidPeriod($period))
	{
		header("Location: show_sawmill_production");
		exit();
	}

	//Returns employees data
	$employee = Employee::GetEmployeesData($user_id);
?>
	
	<!-- Show report of employee -->
	<div class="container">
		<div class="row cont-space">
			<div class="col-md-12">
				<div id="message">
					<?php include "../message.php"; ?>
				</div>
				<div class="card">
					<div class="card-body row">
						<div class="offset-md-1 col-md-6">
							<p class="font-weight-bold">SIA "Rīgas Meži" <br> Struktūrvienība: kokzāģētava "Norupe"</p>
						</div>
						<div class="col-md-5">
							<p class="font-weight-normal">Apstiprinu: <br> Kokzāģētavas vadītājs (Andris Bērziņš)</p>
						</div>
						<div class="offset-md-1 col-md-11">
							<p class="font-weight-bold">
								DARBU NODOŠANAS - PIEŅEMŠANAS AKTS Nr. DAL
								<span class="text-danger">.....</span> /420-17
								<span class="font-weight-normal ml-2">
									(Rīkojums Nr. SRM-14-782-rp 03.09.2014.)
								</span>
							</p>
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
								<span class="text-danger">.....</span>
								<br>
								<span class="font-italic">(personas kods)</span>	
							</p>
						</div>
						<div class="col-md-7">
							<p class="font-weight-normal">
								Darbā ierašanās dienas: 
								<?php
									$worked = Manager::GetEmployeeProductionsDaysWorked($period, $employee['id']);
									echo $worked['working'];
									echo " no ";
									echo $worked['all_productions'];
								?>
							</p>
							Darbā neierašanās reizes:
							<ul>
							<?php
								$nonworked_days = Manager::GetEmployeeProductionsDaysNonWorked($period, $employee['id']);
								echo "<li>Atvaļinājums: ";
								foreach($nonworked_days as $nonworked_day)
								{
									if(isset($nonworked_day['vacation']))
									{
										echo $nonworked_day['date'].", ";
									}
								}
								echo "</li><li>Slimības lapa: ";
								foreach($nonworked_days as $nonworked_day)
								{
									if(isset($nonworked_day['sick_leave']))
									{
										echo $nonworked_day['date'].", ";
									}
								}
								echo "</li><li>Neapmeklējums: ";
								foreach($nonworked_days as $nonworked_day)
								{
									if(isset($nonworked_day['nonattendace']))
									{
										echo $nonworked_day['date'].", ";
									}
								}
								echo "</li>";
							?>
							</ul>
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
										<td>Saražotie zāģmateriāli</td>
										<td>m<sup>3</sup></td>
										<td>
										<?php
											$capacity = Manager::GetEmployeeProductionsCapacity($period, $employee['id']);
											echo $capacity['capacity'];
										?>
										</td>
										<td>
											<?=$employee['capacity_rate']?>
										</td>
										<td>
										<?php
											$total_m3 = round(($capacity['capacity'] * $employee['capacity_rate']), 2);
											echo $total_m3;
										?>
										</td>
									</tr>
									<tr>
										<th>2</th>
										<td>Remontdarbi</td>
										<td>h</td>
										<td>
										<?php
											$maintenance = Manager::GetEmployeeProductionsMaintenances($period, $employee['id']);
											if(!isset($maintenance['maintenance']))
											{
												echo "0 min";
											}
											else
											{
												echo $maintenance['maintenance']." min / ";
												echo round($maintenance['maintenance']/60, 3)." h";
											}
										?>
										</td>
										<td>
											<?=$employee['hour_rate']?>
										</td>
										<td>
										<?php
											$total_h = round((round($maintenance['maintenance']/60, 3) * $employee['hour_rate']), 2);
											echo $total_h;
										?>
										</td>
									</tr>
									<tr>
										<td colspan="4" class="text-right">
											Ražošanas efektivitātes likme:
										</td>
										<td>
											<!-- <input type="number" min="0" step="1" name="efficiency_rate"> % -->
											<div class="input-group">
												<input type="number" min="0" step="1" name="efficiency_rate" class="form-control" id="efficiency_rate">
												<div class="input-group-append">
													<span class="input-group-text">%</span>
												</div>
											</div>
										</td>
										<td id="summ">
										<?php
											echo $total_m3+$total_h;
										?>
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
								$positions = Manager::EmployeePositions($employee['id']);
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
								Kokzāģētavas vadītāja vietnieks: Kalvis Ķiesneris
							</p>
						</div>

					</div>
				</div>
			</div>
		</div>
	</div>

<!-- <script src="../public/js/edit_employee.js"></script> -->
<script>
$(document).ready(function(){

	var summ = Number($('#summ').html());

	$('#efficiency_rate').on('input', function(){
		var efficiency = Number($('#efficiency_rate').val())

		var total = ((summ/100)*efficiency)+summ;
		if(isNaN(total))
		{
			total = "0.00";
			$('#total_summ').html(total);
		}
		else
		{
			total = total.toFixed(2);
			$('#total_summ').html(total);
		}
	});
	
});
</script>

<?php
	include_once "../footer.php";
?>