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
		//Validate new user password input form
		$('#reset_user_form').validate({
			rules: {
				current_pwd: {
					required: true,
				},
				pwd: {
					required: true,
					minlength: 8,
					maxlength: 64,
					IsValidPassword: true,
				},
				pwd2: {
					equalTo: "#pwd_area"
				},
			},
			messages: {
				current_pwd: {
					required: "Lūdzu ievadiet jūsu pašreizējo konta paroli!",
				},
				pwd: {
					required: "Lūdzu aizpildiet Jaunā parole lauku!",
					minlength: "Jaunai parolei jābūt garumā no 8 simboliem līdz 64 simboliem!",
					maxlength: "Jaunai parolei garumā no 8 simboliem līdz 64 simboliem!",
					IsValidPassword: "Parolei jāsastāv vismaz no viena cipara, lielā un mazā latīņu burta un speciālā simbola!",
				},
				pwd2: {
					equalTo: "Ievadītās jaunās paroles nesakrīt!",
				},
			}
		});
	});
});