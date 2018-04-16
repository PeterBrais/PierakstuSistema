<?php
	include_once "../includes/office.class.php";

	$this_month = date('Y-m');
	$next_month = date('Y-m', strtotime('next month'));
	$prev_month_1 = date('Y-m', strtotime('-1 month'));
	$prev_month_2 = date('Y-m', strtotime('-2 month'));
	$prev_month_3 = date('Y-m', strtotime('-3 month'));

	$periods = Office::AllOfficePeriods($prev_month_3);

?>

	<select class="custom-select" id="period_select" onchange="location = this.value;">

<?php

	if(isset($_GET['p']))
	{
		if($_GET['p'] == $next_month)
		{
			echo '<option value="office_time_table?p='.$next_month.'" selected>'.date('F Y', strtotime($next_month)).'</option>';
			echo '<option value="office_time_table?p='.$this_month.'">'.date('F Y', strtotime($this_month)).'</option>';
			echo '<option value="office_time_table?p='.$prev_month_1.'">'.date('F Y', strtotime($prev_month_1)).'</option>';
			echo '<option value="office_time_table?p='.$prev_month_2.'">'.date('F Y', strtotime($prev_month_2)).'</option>';
			echo '<option value="office_time_table?p='.$prev_month_3.'">'.date('F Y', strtotime($prev_month_3)).'</option>';

			foreach($periods as $period)
			{
				echo '<option value="office_time_table?p='.$period['date'].'">'.$period['month_year'].'</option>';
			}
		}
		else if($_GET['p'] == $this_month)
		{
			echo '<option value="office_time_table?p='.$next_month.'">'.date('F Y', strtotime($next_month)).'</option>';
			echo '<option value="office_time_table?p='.$this_month.'" selected>'.date('F Y', strtotime($this_month)).'</option>';
			echo '<option value="office_time_table?p='.$prev_month_1.'">'.date('F Y', strtotime($prev_month_1)).'</option>';
			echo '<option value="office_time_table?p='.$prev_month_2.'">'.date('F Y', strtotime($prev_month_2)).'</option>';
			echo '<option value="office_time_table?p='.$prev_month_3.'">'.date('F Y', strtotime($prev_month_3)).'</option>';

			foreach($periods as $period)
			{
				echo '<option value="office_time_table?p='.$period['date'].'">'.$period['month_year'].'</option>';
			}
		}
		else if($_GET['p'] == $prev_month_1)
		{
			echo '<option value="office_time_table?p='.$next_month.'">'.date('F Y', strtotime($next_month)).'</option>';
			echo '<option value="office_time_table?p='.$this_month.'">'.date('F Y', strtotime($this_month)).'</option>';
			echo '<option value="office_time_table?p='.$prev_month_1.'" selected>'.date('F Y', strtotime($prev_month_1)).'</option>';
			echo '<option value="office_time_table?p='.$prev_month_2.'">'.date('F Y', strtotime($prev_month_2)).'</option>';
			echo '<option value="office_time_table?p='.$prev_month_3.'">'.date('F Y', strtotime($prev_month_3)).'</option>';

			foreach($periods as $period)
			{
				echo '<option value="office_time_table?p='.$period['date'].'">'.$period['month_year'].'</option>';
			}
		}
		else if($_GET['p'] == $prev_month_2)
		{
			echo '<option value="office_time_table?p='.$next_month.'">'.date('F Y', strtotime($next_month)).'</option>';
			echo '<option value="office_time_table?p='.$this_month.'">'.date('F Y', strtotime($this_month)).'</option>';
			echo '<option value="office_time_table?p='.$prev_month_1.'">'.date('F Y', strtotime($prev_month_1)).'</option>';
			echo '<option value="office_time_table?p='.$prev_month_2.'" selected>'.date('F Y', strtotime($prev_month_2)).'</option>';
			echo '<option value="office_time_table?p='.$prev_month_3.'">'.date('F Y', strtotime($prev_month_3)).'</option>';

			foreach($periods as $period)
			{
				echo '<option value="office_time_table?p='.$period['date'].'">'.$period['month_year'].'</option>';
			}
		}
		else if($_GET['p'] == $prev_month_3)
		{
			echo '<option value="office_time_table?p='.$next_month.'">'.date('F Y', strtotime($next_month)).'</option>';
			echo '<option value="office_time_table?p='.$this_month.'">'.date('F Y', strtotime($this_month)).'</option>';
			echo '<option value="office_time_table?p='.$prev_month_1.'">'.date('F Y', strtotime($prev_month_1)).'</option>';
			echo '<option value="office_time_table?p='.$prev_month_2.'">'.date('F Y', strtotime($prev_month_2)).'</option>';
			echo '<option value="office_time_table?p='.$prev_month_3.'" selected>'.date('F Y', strtotime($prev_month_3)).'</option>';

			foreach($periods as $period)
			{
				echo '<option value="office_time_table?p='.$period['date'].'">'.$period['month_year'].'</option>';
			}
		}
		else
		{
			echo '<option value="office_time_table?p='.$next_month.'">'.date('F Y', strtotime($next_month)).'</option>';
			echo '<option value="office_time_table?p='.$this_month.'">'.date('F Y', strtotime($this_month)).'</option>';
			echo '<option value="office_time_table?p='.$prev_month_1.'">'.date('F Y', strtotime($prev_month_1)).'</option>';
			echo '<option value="office_time_table?p='.$prev_month_2.'">'.date('F Y', strtotime($prev_month_2)).'</option>';
			echo '<option value="office_time_table?p='.$prev_month_3.'">'.date('F Y', strtotime($prev_month_3)).'</option>';

			foreach($periods as $period)
			{
				if($_GET['p'] == $period['date'])
				{
					echo '<option value="office_time_table?p='.$period['date'].'" selected>'.$period['month_year'].'</option>';
				}
				else
				{
					echo '<option value="office_time_table?p='.$period['date'].'">'.$period['month_year'].'</option>';
				}
			}
		}
	}
	else
	{
		echo '<option value="office_time_table?p='.$next_month.'">'.date('F Y', strtotime($next_month)).'</option>';
		echo '<option value="office_time_table?p='.$this_month.'" selected>'.date('F Y', strtotime($this_month)).'</option>';
		echo '<option value="office_time_table?p='.$prev_month_1.'">'.date('F Y', strtotime($prev_month_1)).'</option>';
		echo '<option value="office_time_table?p='.$prev_month_2.'">'.date('F Y', strtotime($prev_month_2)).'</option>';
		echo '<option value="office_time_table?p='.$prev_month_3.'">'.date('F Y', strtotime($prev_month_3)).'</option>';

		foreach($periods as $period)
		{
			echo '<option value="office_time_table?p='.$period['date'].'">'.$period['month_year'].'</option>';
		}

	}
?>
	</select>