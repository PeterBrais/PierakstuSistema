<?php
	include_once "includes/manager.class.php";

	$shifts = Manager::AllShifts();
?>

	<select class="custom-select" name="shifts" id="employees_shift">
		<option selected value="0">Izvēlieties maiņu</option>

<?php
	foreach($shifts as $shift)
	{
		echo '<option value="'.$shift['shift'].'">'.$shift['shift'].'</option>';
	}
?>

	</select>