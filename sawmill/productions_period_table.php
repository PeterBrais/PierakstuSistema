<?php
	include_once "../includes/manager.class.php";

	$date_string = isset($_POST['date_string']) ? $_POST['date_string'] : date('Y-m');
	$productions = Manager::GetProductionsByDate($date_string);
	$employees = Manager::GetProductionEmployeesByDate($date_string);
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
				</tr>
	<?php
		}
	?>
			</tbody>
		</table>
	</div>

<div class="card-body">
	<h4 class="card-title text-center">Darbinieki</h4>
	<table class="table table-bordered table-hover">
		<thead class="thead-default table-active">
			<tr>
				<th rowspan="2">Nr.p.k</th>
				<th rowspan="2">V. Uzvārds</th>
				<th rowspan="2">Amats</th>
				<th rowspan="2">Maiņa</th>
				<th colspan="3">Darba aprēķins</th>
				<th rowspan="2">...</th>
			</tr>
			<tr>
				<th>Dienas</th>
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
					<td></td>
					<td></td>
					<td></td>
					<td></td>
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
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
	<?php
			}
		}
	?>
		</tbody>
	</table>
</div>