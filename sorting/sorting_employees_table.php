<?php
	include_once "../includes/manager.class.php";

	$this_date = date('Y-m'); //This year and month
	$employees = Manager::GetSortingEmployees($this_date);
?>
	<table class="table table-bordered table-hover">
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
		$i = 1;
		foreach($employees as $employee)
		{
?>
			<tr>
				<input type="hidden" name="id[]" value="<?=$employee['id']?>">
				<th><?=$i++?></th>
				<td><?=$employee['name']?></td>
				<td><?=$employee['last_name']?></td>
				<td>
					<input class="form-control" type="number" min="1" max="24" step="1" name="working_hours[]">
				</td>
				<td>
					<select class="custom-select" name="nonworking[]">
						<option selected value="0" style="font-weight:bold;">Izvēlēties citu iemeslu</option>
						<option value="1">Atvaļinājums</option>
						<option value="2">Slimības lapa</option>
						<option value="3">Neapmeklējums</option>
					</select>
				</td>
			</tr>
<?php
		}

?>
		</tbody>
	</table>