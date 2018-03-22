$(document).ready(function(){
	var remove_btn = '<div class="col-md-1"><button type="button" class="btn btn-danger remove">X</button></div>';

	$('#add').click(function(){ 
		$.get('position_select.php', function(result){
			var positionSelect = '<div class="offset-md-3 col-md-5 position-select">';
			positionSelect += result+'</div>'+remove_btn+'<div class="col-md-3"></div>';
			$('#position_selects').append($(positionSelect).hide().fadeIn(250));
		});
	});

	$(document).on('click', '.remove', function(){
		$(this).parent().prev('.position-select').fadeOut(250, function(){
			$(this).remove();
		});
		$(this).parent().next().fadeOut(250, function(){
			$(this).remove();
		});
		$(this).parent().fadeOut(250, function(){
			$(this).remove();
		});
	});
});