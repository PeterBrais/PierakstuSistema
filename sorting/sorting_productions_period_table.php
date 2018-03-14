<?php
	include_once "../includes/manager.class.php";

	$date_string = isset($_POST['date_string']) ? $_POST['date_string'] : date('Y-m');
	$productions = Manager::GetProductionsByDate($date_string);
	$employees = Manager:: GetSortingEmployeesByDate($date_string);
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
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
	<?php
		}
	?>
				<tr class="table-info">
					<td colspan="4" class="text-right"><strong> Kopā: </strong></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td colspan="2"></td>
				</tr>
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
					<td></td>
					<td></td>
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