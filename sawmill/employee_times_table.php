<?php
	include_once "../includes/manager.class.php";

	if(isset($_POST['shift_id']))
	{
		$shift = $_POST['shift_id'];

		if(!empty($shift))
		{
			$this_date = date('Y-m'); //This year and month
			$employees = Manager::GetEmployeesByShift($shift, $this_date);
?>
			<table class="table table-bordered table-hover">
				<thead class="thead-default table-active">
					<tr>
						<th>Nr. p. k</th>
						<th>Vārds</th>
						<th>Uzvārds</th>
						<th>Nostrādātas stundas</th>
						<!-- <th>Cits iemesls</th> -->
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
						<!-- <td> -->
							<!-- <input class="form-control working_hours_class" type="number" min="1" max="24" step="1" name="working_hours[]"> -->
						<!-- </td> -->
						<td>
							<select class="custom-select nonworking_hours_class" name="nonworking[]">
								<option selected value="" style="font-weight:bold;">Izvēlēties nostrādātās stundas vai citu iemeslu</option>
								<option value="1">1 Stunda</option>
								<option value="2">2 Stunda</option>
								<option value="3">3 Stunda</option>
								<option value="4">4 Stunda</option>
								<option value="5">5 Stundas</option>
								<option value="6">6 Stundas</option>
								<option value="7">7 Stundas</option>
								<option value="8" selected>8 Stundas</option>
								<option value="9">Atvaļinājums</option>
								<option value="10">Slimības lapa</option>
								<option value="11">Neapmeklējums</option>
							</select>
						</td>
					</tr>
<?php
				}
		}
?>
				</tbody>
			</table>
<?php
	}
	else
	{
		header("Location: 404");
		exit();
	}
?>
				