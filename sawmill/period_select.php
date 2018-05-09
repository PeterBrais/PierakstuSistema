<?php
	include_once "../includes/manager.class.php";

	$periods = Manager::AllSawmillPeriods();
?>

	<select class="custom-select" id="period_select" onchange="location = this.value;">

<?php
	foreach($periods as $period)
	{
		if(isset($_GET['p']))
		{	
			if($_GET['p'] == $period['date'])
			{
				echo '<option value="show_sawmill_production?p='.$period['date'].'" selected>'.$period['month_year'].'</option>';
			}
			else
			{
				echo '<option value="show_sawmill_production?p='.$period['date'].'">'.$period['month_year'].'</option>';
			}
		}
		else
		{
			echo '<option value="show_sawmill_production?p='.$period['date'].'">'.$period['month_year'].'</option>';
		}
	}
?>
	</select>

<script> </script>
	<script type="text/javascript">
        $(function() {
            $(".monthAndYear").datepicker( {
            changeMonth: true,
            changeYear: true,
            showButtonPanel: true,
            dateFormat: 'MM yy',
            onClose: function(dateText, inst) { 
                $(this).datepicker('setDate', new Date(inst.selectedYear, inst.selectedMonth, 1));
            }
            });
        });
    </script>
    <style>
    .ui-datepicker-calendar {
        display: none;
    }
    </style>

    <input class="monthAndYear form-control" name="startDate" id="startDate"  onchange="location = this.value;">