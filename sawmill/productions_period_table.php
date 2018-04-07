<?php
	include_once "../includes/manager.class.php";

	$date_string = isset($_POST['date_string']) ? $_POST['date_string'] : date('Y-m');
	$productions = Manager::GetSawmillProductionsByDate($date_string);
	$employees = Manager::GetSawmillEmployeesByDate($date_string);
	$total = Manager::GetAllSawmillProductionSummByDate($date_string);
?>
	<div class="card-body">
		<h4 class="card-title text-center">Zāģētavas produkcijas</h4>
		<table class="table table-bordered table-hover">
			<thead class="thead-default table-active">
				<tr>
					<th rowspan="2">Datums</th>
					<th rowspan="2">Laiks</th>
					<th rowspan="2">Pavadzīmes Nr.</th>
					<th rowspan="2">Maiņa</th>
					<th colspan="2">Apaļkoki</th>
					<th colspan="2">Zāģmateriāli</th>
					<th rowspan="2">Ef.</th>
					<th rowspan="2">Remonts</th>
					<th rowspan="2">Piezīmes</th>
					<th rowspan="2">Labot</th>
				</tr>
				<tr>
					<th>Skaits (gab)</th>
					<th>Tilpums (m<sup>3</sup>)</th>
					<th>Skaits (gab)</th>
					<th>Tilpums (m<sup>3</sup>)</th>
				</tr>
			</thead>
			<tbody>
	<?php
		foreach($productions as $production)
		{
	?>
				<tr>
					<td><?=$production['date']?></td>
					<td>
						<?=$production['time_from']?> - <?=$production['time_to']?>
					</td>
					<td><?=$production['invoice']?></td>
					<td><?=$production['shift']?></td>
					<td><?=$production['beam_count']?></td>
					<td><?=$production['beam_capacity']?></td>
					<td><?=$production['lumber_count']?></td>
					<td><?=$production['lumber_capacity']?></td>
					<td><?=$production['percentage']?></td>
					<td>
						<ul class="list-space">
						<?php
							$maintenances = Manager::ProductionMaintenances($production['id']);
							foreach($maintenances as $maintenance)
							{
								echo '<li>';
								echo $maintenance['time'];
								echo " <b>-</b> ";
								echo $maintenance['note'];
								echo '</li>';
							}
						?>
						</ul>
					</td>
					<td><?=$production['note']?></td>
					<td>
						<a href="edit_production?id=<?=$production['id']?>" class="btn btn-info">
							Labot
						</a>
					</td>
				</tr>
	<?php
		}
	?>
				<tr class="table-info">
					<td colspan="4" class="text-right"><strong> Kopā: </strong></td>
					<td><?=$total['beam_count']?></td>
					<td><?=$total['beam_capacity']?></td>
					<td><?=$total['lumber_count']?></td>
					<td><?=$total['lumber_capacity']?></td>
					<td>
						<?php
							if(!isset($total['lumber_capacity']))
							{
								echo "";
							}
							else
							{
								echo round(($total['lumber_capacity']/$total['beam_capacity'])*100, 1);
							}
						?>
					</td>
					<td colspan="2">
						<?php
							if(!isset($total['maintenance']))
							{
								echo "";
							}
							else
							{
								echo $total['maintenance']." min / ";
								echo round($total['maintenance']/60, 2)." h";
							}
						?>
					</td>
					<td></td>
				</tr>
			</tbody>
		</table>
	</div>

<div class="card-body">
	<h4 class="card-title text-center">Zāģētavas Darbinieki</h4>
	<table class="table table-bordered table-hover">
		<thead class="thead-default table-active">
			<tr>
				<th rowspan="2">Nr.p.k</th>
				<th rowspan="2">V. Uzvārds</th>
				<th rowspan="2">Amats</th>
				<th rowspan="2">Maiņa</th>
				<th colspan="4">Darba aprēķins</th>
				<th rowspan="2">Atskaite</th>
			</tr>
			<tr>
				<th>Dienas</th>
				<th>Nestrādātās dienas</th>
				<th>m<sup>3</sup></th>
				<th>h</th>
			</tr>
		</thead>
		<tbody>
	<?php
		$i = 1;
		foreach($employees as $employee)
		{
			if($employee['shift'] == "1")
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
					<td><?=$employee['shift']?></td>
					<td>
					<?php
						$worked = Manager::GetEmployeeProductionsDaysWorked($date_string, $employee['id']);
						echo $worked['all_productions'];
						echo " / ";
						echo $worked['working'];
					?>
					</td>
					<td>
					<?php
						echo "<small>";
						$nonworked_days = Manager::GetEmployeeProductionsDaysNonWorked($date_string, $employee['id']);
						echo "Atvaļinājums: ";
						foreach($nonworked_days as $nonworked_day)
						{
							if(isset($nonworked_day['vacation']))
							{
								echo $nonworked_day['date'].", ";
							}
						}
						echo '<br>';
						echo "Slimība: ";
						foreach($nonworked_days as $nonworked_day)
						{
							if(isset($nonworked_day['sick_leave']))
							{
								echo $nonworked_day['date'].", ";
							}
						}
						echo '<br>';
						echo "Neapmeklējums: ";
						foreach($nonworked_days as $nonworked_day)
						{
							if(isset($nonworked_day['nonattendace']))
							{
								echo $nonworked_day['date'].", ";
							}
						}
						echo "</small>";
					?>
					</td>
					<td>
					<?php
						$capacity = Manager::GetEmployeeProductionsCapacity($date_string, $employee['id']);
						echo $capacity['capacity'];
					?>
					</td>
					<td>
					<?php
						$maintenance = Manager::GetEmployeeProductionsMaintenances($date_string, $employee['id']);
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
						<a href="report?id=<?=$employee['id']?>&period=<?=$date_string?>" class="btn btn-success">
							Skatīt
						</a>
					</td>
				</tr>
	<?php
			}
			else if($employee['shift'] == "2")
			{
	?>
				<tr class="table-success">
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
					<td><?=$employee['shift']?></td>
					<td>
					<?php
						$worked = Manager::GetEmployeeProductionsDaysWorked($date_string, $employee['id']);
						echo $worked['all_productions'];
						echo " / ";
						echo $worked['working'];
					?>
					</td>
					<td>
					<?php
						echo "<small>";
						$nonworked_days = Manager::GetEmployeeProductionsDaysNonWorked($date_string, $employee['id']);
						echo "Atvaļinājums: ";
						foreach($nonworked_days as $nonworked_day)
						{
							if(isset($nonworked_day['vacation']))
							{
								echo $nonworked_day['date'].", ";
							}
						}
						echo '<br>';
						echo "Slimība: ";
						foreach($nonworked_days as $nonworked_day)
						{
							if(isset($nonworked_day['sick_leave']))
							{
								echo $nonworked_day['date'].", ";
							}
						}
						echo '<br>';
						echo "Neapmeklējums: ";
						foreach($nonworked_days as $nonworked_day)
						{
							if(isset($nonworked_day['nonattendace']))
							{
								echo $nonworked_day['date'].", ";
							}
						}
						echo "</small>";
					?>
					</td>
					<td>
					<?php
						$capacity = Manager::GetEmployeeProductionsCapacity($date_string, $employee['id']);
						echo $capacity['capacity'];
					?>
					</td>
					<td>
					<?php
						$maintenance = Manager::GetEmployeeProductionsMaintenances($date_string, $employee['id']);
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
						<a href="report?id=<?=$employee['id']?>&period=<?=$date_string?>" class="btn btn-success">
							Skatīt
						</a>
					</td>
				</tr>
	<?php
			}
		}
	?>
		</tbody>
	</table>
</div>