$(document).ready(function(){
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
});