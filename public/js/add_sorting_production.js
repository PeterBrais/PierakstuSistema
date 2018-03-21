$(document).ready(function(){
	//Add more sorted production
	$('#add').click(function(){ 
		var Sorting = $(`<hr><div class="form-group row">
							<label class="col-md-2 offset-md-1 col-form-label">
								Veids
								<span class="text-danger" title="Šis lauks ir obligāts">
									&#10033;
								</span>
							</label>
							<div class="col-md-5">
								<select class="custom-select" name="sorted_types[]">
									<option selected value="0">Izvēlieties šķirošanas veidu</option>
									<option value="1">Šķirots</option>
									<option value="2">Garināts</option>
								</select>
							</div>
						</div>	
						<div class="form-group row">
							<label class="col-md-2 offset-md-1 col-form-label">
								Skaits
								<span class="text-danger" title="Šis lauks ir obligāts">
									&#10033;
								</span>
							</label>
							<div class="col-md-5">
								<input class="form-control sorted_counts" type="number" min="0" name="sorted_count[]" aria-describedby="sawnCountArea" placeholder="Kopējais skaits">
								<small id="sawnCountArea" class="form-text text-muted">
									* Satur tikai ciparus, kopējo (gab) skaitu *
								</small>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-md-2 offset-md-1 col-form-label">
								Izmēri
								<span class="text-danger" title="Šie lauki ir obligāti">
									&#10033;
								</span>
							</label>
							<div class="col-md-5">
								<div class="row">
									<div class="col-md-4">
										<input class="form-control sorted_thicknesses" type="number" min="0" name="sorted_thick[]" aria-describedby="timeFromArea" placeholder="Biezums">
									</div>
									<div class="col-md-4">
										<input class="form-control sorted_widths" type="number" min="0" name="sorted_width[]" aria-describedby="timeFromArea" placeholder="Platums">
									</div>
									<div class="col-md-4">
										<input class="form-control sorted_lengths" type="number" min="0" name="sorted_length[]" aria-describedby="timeFromArea" placeholder="Garums">
									</div>
								</div>
								<small id="timeFromArea" class="form-text text-muted">
									* Satur tikai ciparus *
								</small>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-md-2 offset-md-1 control-label">
								Tilpums
							</label>
							<div class="col-md-5">
								<p class="form-control-static sorted_capacities"> m<sup>3</sup></p>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-md-2 offset-md-1 control-label">
								Tilpums / gab
							</label>
							<div class="col-md-5">
								<p class="form-control-static sorted_capacities_pieces"> m<sup>3</sup></p>
							</div>
							<div class="col-md-4">
								<button type="button" class="btn btn-danger remove mb-2">Noņemt</button>
							</div>
						</div>`).hide().fadeIn('slow');
	$('#sorted_select').append(Sorting);
	});

	$(document).on('click', '.remove', function(){
		$(this).parent().parent().fadeOut('slow', function(){
			$(this).remove();
		});
		$(this).parent().parent().prev().fadeOut('slow', function(){
			$(this).remove();
		});
		$(this).parent().parent().prev().prev().fadeOut('slow', function(){
			$(this).remove();
		});
		$(this).parent().parent().prev().prev().prev().fadeOut('slow', function(){
			$(this).remove();
		});
		$(this).parent().parent().prev().prev().prev().prev().fadeOut('slow', function(){
			$(this).remove();
		});
		$(this).parent().parent().prev().prev().prev().prev().prev().fadeOut('slow', function(){
			$(this).remove();
		});
	});


	//Show sawn production capacity
	var measure_unit = ' m<sup>3</sup>';
	$('#sawn_count, #thickeness, #width, #length').change(function(){
		var count = $('#sawn_count').val();
    	var thickeness = $("#thickeness").val();
    	var width = $("#width").val();
    	var length = $("#length").val();

		var capacity = ((thickeness * width * length)/1000000000)*count;
		if(isNaN(capacity))
		{
			capacity = "0.000";
			$('#sawn_capacity').html(capacity+measure_unit);
		}
		else
		{
			capacity = capacity.toFixed(3);
			$('#sawn_capacity').html(capacity+measure_unit);
		}
	});

	//Show sorted production capacities and capacities per piece
	var measure_piece = ' m<sup>3</sup> / gab';
	$(document).on('input', '.sorted_counts', function(){
	    var sorted_count = $(this).val();
	    var sorted_thickness = $(this).parent().parent().next().children().find('.sorted_thicknesses').val();
	    var sorted_width = $(this).parent().parent().next().children().find('.sorted_widths').val();
	    var sorted_length = $(this).parent().parent().next().children().find('.sorted_lengths').val();
	    var total_cap = ((sorted_thickness*sorted_width*sorted_length)/1000000000)*sorted_count;
	    var total_cap_piece = (sorted_thickness*sorted_width*sorted_length)/1000000000;

	    if(isNaN(total_cap))
		{
			total_cap = "0.000";
		}
		else
		{
			total_cap = total_cap.toFixed(3);
		}
		$(this).parent().parent().next().next().children().find('.sorted_capacities').html(total_cap+measure_unit);

		if(isNaN(total_cap_piece))
		{
			total_cap_piece = "0.00000"
		}
		else
		{
			total_cap_piece = total_cap_piece.toFixed(5);
		}
		$(this).parent().parent().next().next().next().children().find('.sorted_capacities_pieces').html(total_cap_piece+measure_piece);
	});
});