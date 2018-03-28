$(function(){
    jQuery.validator.setDefaults({
		errorPlacement: function(error, element) {
			if(element.attr("name") == "agree")
			{
				error.addClass('alert alert-danger alert-size mb-1')
				error.appendTo( element.parent().parent().parent().next() );
			}
			else
			{
				error.addClass('alert alert-danger alert-size')
				error.appendTo( element.parent().next() );
			}
		},
		wrapper: 'div'
	});

	$().ready(function(){
		//Validate user update input form
		$('#unblock_user_form').validate({
			rules: {
				pwd: {
					required: true,
				},
				agree: {
					required: true,
				},
			},
			messages: {
				pwd: {
					required: "Lūdzu ievadiet profila paroli!",
				},
				agree: {
					required: "Lūdzu apstipriniet izvēles rūtiņu!",
				},
			}
		});
	});
});