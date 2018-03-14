<?php
	include_once "../includes/manager.class.php";

	$periods = Manager::AllSortingPeriods();
?>

	<select class="custom-select" id="period_select">

<?php
	foreach($periods as $period)
	{
		echo '<option value="'.$period['date'].'">'.$period['month_year'].'</option>';
	}
?>
	</select>