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
					<select class="custom-select working_class" name="working[]">
						<option selected value="" style="font-weight:bold;">Izvēlēties nostrādātās stundas vai citu iemeslu</option>
						<option value="1">1 Stunda</option>
						<option value="2">2 Stundas</option>
						<option value="3">3 Stundas</option>
						<option value="4">4 Stundas</option>
						<option value="5">5 Stundas</option>
						<option value="6">6 Stundas</option>
						<option value="7">7 Stundas</option>
						<option value="8">8 Stundas</option>
						<option value="9">Atvaļinājums</option>
						<option value="10">Slimības lapa</option>
						<option value="11">Neapmeklējums</option>
					</select>
				</td>
			</tr>
<?php
		}

?>
		</tbody>
	</table>