$(function(){
    jQuery.validator.setDefaults({
		errorPlacement: function(error, element) {
			error.addClass('alert alert-danger alert-size');
			element.parent().next().empty();	//Deletes Session error message
			error.appendTo( element.parent().next() );
		},
		wrapper: 'div'
	});

	$().ready(function(){
		//Validate user update input form
		$('#edit_beam_size_form').validate({
			rules: {
				name: {
					required: true,
					number: true,
					min: 0,
					max: 999999999999.999,
					step: 0.001,
					IsValidFloatNumber: true,
					IsValidFloatNumberWithThreeDigitsAfterDot: true,
					remote: {
						url: "current_beam_size_check",
						type: "post",
						data: {
							id: function(){return $('input[name=size_id]').val();}, //Extra parameter: id
						},
					},
				},
			},
			messages: {
				name: {
					required: "Lūdzu aizpildiet Izmērs lauku!",
					number: "Izmērs drīkst saturēt tikai ciparus!",
					min: "Izmēram jābūt lielākam par nulli!",
					max: "Izmēram jābūt ne vairāk kā 12 ciparus garam",
					step: "Maksimums 3 cipari aiz komata",
					IsValidFloatNumber: "Izmēram jābūt lielākam par nulli!",
					IsValidFloatNumberWithThreeDigitsAfterDot: "Izmērs drīkst saturēt tikai ciparus ar komatu",
					remote: "Izmērs jau eksistē!",
				},
			}
		});
	});
});