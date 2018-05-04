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
		$('#edit_user_form').validate({
			rules: {
				usr: {
					required: true,
					minlength: 3,
					maxlength: 50,
					IsValidUsername: true,
					remote: {
						url: "current_username_check",
						type: "post",
						data: {
							id: function(){return $('input[name=user_id]').val();}, //Extra parameter: id
						},
					},
				},
				role: {
					ChosenRoleDropdown: true,
				},
			},
			messages: {
				usr: {
					required: "Lūdzu, aizpildiet Lietotājvārds lauku!",
					minlength: "Lietotājvārdam jābūt garumā no 3 simboliem līdz 50 simboliem!",
					maxlength: "Lietotājvārdam jābūt garumā no 3 simboliem līdz 50 simboliem!",
					IsValidUsername: "Lietotājvārds drīkst saturēt tikai latīņu burtus un ciparus!",
					remote: "Lietotājvārds jau eksistē!",
				},
				role: {
					ChosenRoleDropdown: "Lūdzu, izvēlieties lietotāja lomu!",
				},
			}
		});
	});
});