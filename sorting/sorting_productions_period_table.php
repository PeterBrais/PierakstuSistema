<?php
	include_once "../includes/manager.class.php";

	$date_string = isset($_POST['date_string']) ? $_POST['date_string'] : date('Y-m');
	$invoices = Manager::GetSortingProductionsByInvoice($date_string);
	$employees = Manager::GetSortingEmployeesByDate($date_string);
	$total = Manager::GetAllSortingProductionSummByDate($date_string);
?>
	<div class="card-body">
		<h4 class="card-title text-center">Šķirotavas produkcijas</h4>
		<table class="table table-bordered">
			<thead class="thead-default table-active">
				<tr>
					<th>Pavadzīmes Nr.</th>
					<th>Datums</th>
					<th>Laiks</th>
					<th>
						Izmērs <abbr title="Biezums x Platums x Garums">(BxPxG)</abbr>
					</th>
					<th>
						<abbr title="Kopējais Skaits - Brāķi = Skaits">Skaits</abbr>
					</th>
					<th>Tilpums (m<sup>3</sup>)</th>
					<th>Garināts / Šķirots</th>
					<th>Skaits</th>
					<th>
						Izmērs <abbr title="Biezums x Platums x Garums">(BxPxG)</abbr>
					</th>
					<th>Tilpums (m<sup>3</sup>)</th>
					<th>m<sup>3</sup>/gab</th>
					<th>Darba veicēji</th>
					<th>Labot</th>
				</tr>
			</thead>
			<tbody>
	<?php
		foreach($invoices as $invoice)
		{
			$productions = Manager::GetSortingProductions($invoice['invoice']);
			foreach($productions as $production)
			{
				$rows = $production['total_sorted'];
	?>
			<tr>
				<td rowspan="<?=$rows?>"><?=$invoice['invoice']?></td>
				<td rowspan="<?=$rows?>"><?=$production['date']?></td>
				<td rowspan="<?=$rows?>">
					<?=$production['time_from']?> - <?=$production['time_to']?>
				</td>
				<td rowspan="<?=$rows?>">
					<?=$production['thickness']?> x <?=$production['width']?> x <?=$production['length']?>
				</td>
				<td rowspan="<?=$rows?>"><?=$production['count']?> - 
					<?php
						if(!isset($production['defect_count']))
						{
							echo "0";
						}
						else
						{
							echo $production['defect_count'];
						}
						$total_count = $production['count'] - $production['defect_count'];
						echo " = ".$total_count;
					?>			
				</td>
				<td rowspan="<?=$rows?>"><?=$production['capacity']?></td>
	<?php
		$sorted_productions = Manager::GetSortedProductionsByID($production['id']);
		$k = 0;
		foreach($sorted_productions as $sorted_production)
		{
			if($k == 0)
			{
	?>
				<td><?=$sorted_production['type']?></td>
				<td><?=$sorted_production['count']?></td>
				<td>
					<?=$production['thickness']?> x <?=$production['width']?> x <?=$production['length']?>
				</td>
				<td><?=$sorted_production['capacity']?></td>
				<td><?=$sorted_production['capacity_piece']?></td>
				<td>
					<ol class="list-space">
						<?php
							$workers = Manager::GetAllSortingProductionWorkers($production['id']);
							foreach($workers as $worker)
							{
								echo '<li>';
								echo $worker['name'];
								echo " ";
								echo $worker['last_name'];
								echo '</li>';
							}
						?>
					</ol>
				</td>
				<td rowspan="<?=$rows?>">
					<a href="edit_production?id=<?=$production['id']?>" class="btn btn-info">
						Labot
					</a>
				</td>
			</tr>
	<?php
			} 
			else
			{
	?>
			<tr>
				<td><?=$sorted_production['type']?></td>
				<td><?=$sorted_production['count']?></td>
				<td>
					<?=$production['thickness']?> x <?=$production['width']?> x <?=$production['length']?>
				</td>
				<td><?=$sorted_production['capacity']?></td>
				<td><?=$sorted_production['capacity_piece']?></td>
				<td>
					<ol class="list-space">
						<?php
							$workers = Manager::GetAllSortingProductionWorkers($production['id']);
							foreach($workers as $worker)
							{
								echo '<li>';
								echo $worker['name'];
								echo " ";
								echo $worker['last_name'];
								echo '</li>';
							}
						?>
					</ol>
				</td>
			</tr>
	<?php
			}
			$k++;
		}
			}
		?>
			<tr class="table-light">
				<td colspan="13"></td>
			</tr>
	<?php
		}
	?>
				<tr class="table-info">
					<td colspan="4" class="text-right"><strong> Kopā: </strong></td>
					<td>
						<?php
							echo $total['count'];
							echo " - ";
							if(!isset($total['defect_count']))
							{
								echo "0";
							}
							else
							{
								echo $total['defect_count'];
							}

							$total_count = $total['count'] - $total['defect_count'];
							echo " = ".$total_count;
						?>	
					</td>
					<td><?=$total['capacity']?></td>
					<td></td>
					<td><?=$total['sorted_count']?></td>
					<td></td>
					<td><?=$total['sorted_capacity']?></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
			</tbody>
		</table>
	</div>

<div class="card-body">
	<h4 class="card-title text-center">Šķirotavas Darbinieki</h4>
	<table class="table table-bordered table-hover">
		<thead class="thead-default table-active">
			<tr>
				<th rowspan="2">Nr.p.k</th>
				<th rowspan="2">V. Uzvārds</th>
				<th rowspan="2">Amats</th>
				<th colspan="4">Darba aprēķins</th>
				<th colspan="2">Stundas</th>
				<th rowspan="2">...</th>
			</tr>
			<tr>
				<th>Līdz 0,0089 m3</th>
				<th>No 0,009 līdz 0,0160 m3/gab</th>
				<th>No 0,0161 m3/gab</th>
				<th>Kopā</th>
				<th>Stundas</th>
				<th>Dienas</th>
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
		</tbody>
	</table>
</div>