$(function(){
    jQuery.validator.setDefaults({
		errorPlacement: function(error, element) {
			error.appendTo('.col-md-4');
		}
	});

	$().ready(function(){
		//Validate employee position form
		$('#position_form').validate({
			rules: {
				name: {
					required: true,
					minlength: 3,
					maxlength: 40
				}
			},
			messages: {
				name: {
					required: $(".col-md-4").append("<div class='alert alert-danger alert-size'>Lūdzu aizpildiet Amats lauku!</div>"),
					minlength: "Amats jābūt garumā no 3 simboliem līdz 40 simboliem!",
					maxlength: "Amats jābūt garumā no 3 simboliem līdz 40 simboliem!"
				}
			}
		});
	});
});