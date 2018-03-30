$(function(){
    jQuery.validator.setDefaults({
		errorPlacement: function(error, element) {
			error.addClass('alert alert-danger alert-size')
			error.appendTo( element.parent().next() );
		},
		wrapper: 'div'
	});

	$().ready(function(){
		//Validate user update input form
		$('#edit_position_form').validate({
			rules: {
				name: {
					required: true,
					minlength: 3,
					maxlength: 40,
					IsValidText: true,
					remote: {
						url: "current_position_check",
						type: "post",
						data: {
							id: function(){return $('input[name=position_id]').val();}, //Extra parameter: id
						},
					},
				},
			},
			messages: {
				name: {
					required: "Lūdzu aizpildiet Amats lauku!",
					minlength: "Amats jābūt garumā no 3 simboliem līdz 40 simboliem!",
					maxlength: "Amats jābūt garumā no 3 simboliem līdz 40 simboliem!",
					IsValidText: "Amats drīkst saturēt tikai latīņu burtus, ciparus un speciālos simbolus!",
					remote: "Amats jau eksistē!",
				},
			}
		});
	});
});