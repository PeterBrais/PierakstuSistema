$(function(){
    jQuery.validator.setDefaults({
		errorPlacement: function(error, element) {			
			error.addClass('alert alert-danger alert-size');
			error.appendTo( element.parent().next() );
		},
		wrapper: 'div'
	});

	$().ready(function(){
		//Validate new user input form
		$('#signup_form').validate({
			rules: {
				usr: {
					required: true,
					minlength: 3,
					maxlength: 50,
					IsValidUsername: true,
					remote: {
						url: "username_check",
						type: "post"
					}
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
				role: {
					ChosenRoleDropdown: true,
				},
			},
			messages: {
				usr: {
					required: "Lūdzu, aizpildiet Lietotājvārds lauku!",
					minlength: "Lietotājvārds jābūt garumā no 3 simboliem līdz 50 simboliem!",
					maxlength: "Lietotājvārds jābūt garumā no 3 simboliem līdz 50 simboliem!",
					IsValidUsername: "Lietotājvārds drīkst saturēt tikai latīņu burtus un ciparus!",
					remote: "Lietotājvārds jau eksistē!",
				},
				pwd: {
					required: "Lūdzu, aizpildiet Parole lauku!",
					minlength: "Parolei jābūt garumā no 8 simboliem līdz 64 simboliem!",
					maxlength: "Parolei jābūt garumā no 8 simboliem līdz 64 simboliem!",
					IsValidPassword: "Parolei jāsastāv vismaz no viena cipara, lielā un mazā latīņu burta un speciālā simbola!",
				},
				pwd2: {
					equalTo: "Ievadītās paroles nesakrīt!",
				},
				role: {
					ChosenRoleDropdown: "Lūdzu, izvēlieties lietotāja lomu!",
				}
			}
		});
	});
});