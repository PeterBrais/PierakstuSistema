<?php
	include_once "../includes/manager.class.php";

	$periods = Manager::AllSortingPeriods();
?>

	<select class="custom-select" id="period_select" onchange="location = this.value;">

<?php
	foreach($periods as $period)
	{	
		if(isset($_GET['p']))
		{	
			if($_GET['p'] == $period['date'])
			{
				echo '<option value="show_sorting_production?p='.$period['date'].'" selected>'.$period['month_year'].'</option>';
			}
			else
			{
				echo '<option value="show_sorting_production?p='.$period['date'].'">'.$period['month_year'].'</option>';
			}
		}
		else
		{
			echo '<option value="show_sorting_production?p='.$period['date'].'">'.$period['month_year'].'</option>';
		}
	}
?>
	</select>