$(document).ready(function(){
	var remove_btn = '<div class="col-md-1"><button type="button" class="btn btn-danger remove mt-2">X</button></div><div class="col-md-3"></div>';

	$('#add').click(function(){ 
		var maintenanceSelect = '<div class="offset-md-3 col-md-1"><input class="form-control mt-2 maintenance_times_class" type="text" name="maintenance_times[]" placeholder="Laiks"></div><div class="col-md-4"><input class="form-control mt-2 maintenance_notes_class" type="text" name="maintenance_notes[]" placeholder="Piezīme"></div>'+remove_btn;
		$('#maintenance_select').append($(maintenanceSelect).hide().fadeIn('slow'));
	});

	$(document).on('click', '.remove', function(){  
		$(this).parent().prev().prev().fadeOut('slow', function(){
			$(this).remove();
		});
		$(this).parent().prev().fadeOut('slow', function(){
			$(this).remove();
		});
		$(this).parent().next().fadeOut('slow', function(){
			$(this).remove();
		});
		$(this).parent().fadeOut('slow', function(){
			$(this).remove();
		});
	}); 

	//Show beam count and beam size multiplier
	var measure_unit = ' m<sup>3</sup>';
	$('#beam_count_input, #beam_size_select').change(function(){

		var count = Number($('#beam_count_input').val());
    	var size = $("#beam_size_select option:selected").text();

		var capacity = count*size;
		if(isNaN(capacity))
		{
			capacity = "0.000";
			$('#beam_capacity').html(capacity+measure_unit);
		}
		else
		{
			capacity = capacity.toFixed(3);
			$('#beam_capacity').html(capacity+measure_unit);
		}
	});

	//Show all employees on selected shift
	$('#employees_shift').change(function(){ //id from file: shift_select.php
		var shift_id = $(this).val();

		$.ajax({
			url:"employee_times_table.php",
			method:"POST",
			data:{shift_id:shift_id},
			success:function(data){
				$('#table_show').html(data);
			}
		});
	});

});