$(document).ready(function(){
	var remove_btn = '<div class="col-md-1"><button type="button" class="btn btn-danger remove">X</button></div>';

	$('#add').click(function(){ 
		$.get('position_select.php', function(result){
			var positionSelect = '<div class="offset-md-3 col-md-5 position-select">';
			positionSelect += result+'</div>'+remove_btn+'<div class="col-md-3"></div>';
			$('#position_selects').append($(positionSelect).hide().fadeIn('slow'));
		});
	});

	$(document).on('click', '.remove', function(){
		$(this).parent().prev('.position-select').fadeOut('slow', function(){
			$(this).remove();
		});
		$(this).parent().next().fadeOut('slow', function(){
			$(this).remove();
		});
		$(this).parent().fadeOut('slow', function(){
			$(this).remove();
		});
	});

	//Shows shift and rates input for sawmill workers
	var shift = '<label class="col-md-2 offset-md-1 col-form-label mt-3">Maiņa<span class="text-danger" title="Šis lauks ir obligāts"> &#10033;</span></label><div class="col-md-5 mt-3"><select class="custom-select" name="shift"><option selected value="0">Izvēlieties maiņu</option><option value="1">1</option><option value="2">2</option></select></div><div class="col-md-4 mt-3"></div>';
	var rates = '<label class="col-md-2 offset-md-1 col-form-label mt-3">Likmes<span class="text-danger" title="Šis lauks ir obligāts"> &#10033;</span></label><div class="col-md-5 mt-3"><div class="row"><div class="col-md-6"><input class="form-control" type="number" min="0" step="0.01" name="capacity_rate" aria-describedby="ratesArea" placeholder="m&sup3 likme"></div><div class="col-md-6"><input class="form-control" type="number" min="0" step="0.01" name="hour_rate" aria-describedby="ratesArea" placeholder="Stundas likme"></div></div><small id="ratesArea" class="form-text text-muted">* Satur tikai ciparus, likmi par m<sup>3</sup> un stundu *</small></div><div class="col-md-4 mt-3"></div>';

	$('#place_selects').on('change', function(){
		var place_value = $(this).val();
		if(place_value == "2")
		{
			$('#workplace_input').append($(shift).hide().fadeIn('slow'));
			$('#workplace_input').append($(rates).hide().fadeIn('slow'));
		}
		else //Removes label and div from variable shift
		{
			$(this).parent().next().next().fadeOut(250, function(){
				$(this).remove();
			});
			$(this).parent().next().next().next().fadeOut(250, function(){
				$(this).remove();
			});
			$(this).parent().next().next().next().next().fadeOut(250, function(){
				$(this).remove();
			});
			$(this).parent().next().next().next().next().next().fadeOut(250, function(){
				$(this).remove();
			});
			$(this).parent().next().next().next().next().next().next().fadeOut(250, function(){
				$(this).remove();
			});
			$(this).parent().next().next().next().next().next().next().next().fadeOut(250, function(){
				$(this).remove();
			});
		}
	});
});