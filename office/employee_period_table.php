<?php
	include_once "../includes/office.class.php";
	include_once "../includes/validate.class.php";
	include_once "../includes/manager.class.php";

	$date_string = isset($_GET['p']) ? $_GET['p'] : date('Y-m');

	//Checks if year and month is correct
	if(!Validate::IsValidPeriod($date_string))
	{
		header("Location: 404");
		exit();
	}
	
	//Returns all bureau employee data
	$employees = Office::GetOfficeEmployeesByDate($date_string);

?>

<div class="card-body">
	<h4 class="card-title text-center">Darba Laika Uzskaites Tabele</h4>
	<table class="table table-bordered table-hover">
		<thead class="thead-default table-active">
			<tr>
				<th rowspan="2">Nr.p.k</th>
				<th rowspan="2">V. Uzvārds</th>
				<th rowspan="2">Amats</th>
				<th colspan="3">Darba aprēķins</th>
				<th rowspan="2">Laika Uzskaite</th>
			</tr>
			<tr>
				<th><abbr title="Darbā ierašanās dienas">Dienas</abbr></th>
				<th><abbr title="Nostrādātās stundas">Stundas</abbr></th>
				<th><abbr title="Nostrādātās virsstundas">Virsstundas</abbr></th>
			</tr>
		</thead>
		<tbody>
	<?php
		$i = 1;
		foreach($employees as $employee)
		{
	?>
			<tr>
				<th><?=$i++?></th>
				<td><?=$employee['name']?> <?=$employee['last_name']?></td>
				<td>
				<?php
					$positions = Manager::EmployeePositions($employee['id']);
					foreach($positions as $position)
					{
						echo $position['name'].'<br>';
					}
				?>
				</td>
				<td>
				<?php
					//Returns all days worked, hours and overtime hours
					$days_worked = Office::BureauEmployeeWorkingStatistic($employee['id'], $date_string);
					echo $days_worked['working_days'];
				?>
				</td>
				<td>
				<?php
					echo $days_worked['working_hours'];
				?>
				</td>
				<td>
				<?php
					echo $days_worked['overtime_hours'];
				?>
				</td>
				<td>
					<a href="work_accounting?id=<?=$employee['id']?>&period=<?=$date_string?>" class="btn btn-success">
						Skatīt
					</a>
				</td>
			</tr>
	<?php
		}
	?>
		</tbody>
	</table>
</div>