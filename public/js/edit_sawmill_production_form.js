$(function(){
    jQuery.validator.setDefaults({
		errorPlacement: function(error, element) {
			if(element.attr("name") == "time_from" || element.attr("name") == "time_to")
			{
				error.addClass('alert alert-danger alert-size mb-1')
				error.appendTo( element.parent().parent().parent().next() );
			}
			else if(element.attr("name") == "maintenance_times[]")
			{
				error.addClass('alert alert-danger alert-size mb-1')
				error.appendTo( element.parent().next().next().next() );
			}
			else if(element.attr("name") == "maintenance_notes[]")
			{
				error.addClass('alert alert-danger alert-size mb-1')
				error.appendTo( element.parent().next().next() );
			}
			else if(element.hasClass( "working_class" ))
			{
				error.addClass('alert alert-danger alert-size')
				error.appendTo("#employee_table_error");
			}
			else
			{
				error.addClass('alert alert-danger alert-size');
				error.appendTo( element.parent().next() );
			}		
		},
		wrapper: 'div'
	});

	$().ready(function(){
		//Validate update sawmill production input form
		$('#edit_sawmill_production_form').validate({
			rules: {
				date: {
					required: true,
					date: true,
					IsValidDate: true,
				},
				time_from: {
					required: true,
					IsValidTime: true,
				},
				time_to: {
					required: true,
					IsValidTime: true,
				},
				invoice: {
					required: true,
					number: true,
					min: 0,
					max: 99999999999,
					IsValidIntegerNumber: true,
					remote: {
						url: "current_invoice_check",
						type: "post",
						data: {
							id: function(){return $('input[name=sawmill_production_id]').val();}, //Extra parameter: id
						},
					},
				},
				beam_count: {
					required: true,
					number: true,
					min: 0,
					max: 99999999999,
					IsValidIntegerNumber: true,
				},
				sizes: {
					required: true,
				},
				lumber_count: {
					required: true,
					number: true,
					min: 0,
					max: 99999999999,
					IsValidIntegerNumber: true,
				},
				lumber_capacity: {
					required: true,
					number: true,
					min: 0,
					max: 999999999999.999,
					step: 0.001,
					IsValidFloatNumber: true,
					IsValidFloatNumberWithThreeDigitsAfterDot: true,
				},
				note: {
					required: false,
					minlength: 3,
					maxlength: 50,
					IsValidText: true,
				},
				"maintenance_times[]": {
					number: true,
					min: 0,
					max: 99999999999,
					IsValidIntegerNumber: true,
				},
				"maintenance_notes[]": {
					minlength: 3,
					maxlength: 255,
					IsValidText: true,
				},
				shifts: {
					required: true,
				},
				"working[]": {
					required: true,
					IsValidDropdownWorkingHours: true,
				},
			},
			messages: {
				date: {
					required: "Lūdzu, aizpildiet Datums lauku!",
					date: "Lūdzu, ievadiet korektu datumu (GGGG-MM-DD vai GGGG/MM/DD)!",
					IsValidDate: "Lūdzu, ievadiet korektu datumu (GGGG-MM-DD vai GGGG/MM/DD)!",
				},
				time_from: {
					required: "Lūdzu, aizpildiet 'Laiks no' lauku!",
					IsValidTime: "Lūdzu, ievadiet korektu laiku, formā: hh:mm!",
				},
				time_to: {
					required: "Lūdzu, aizpildiet 'Laiks līdz' lauku!",
					IsValidTime: "Lūdzu, ievadiet korektu laiku, formā: hh:mm!",
				},
				invoice: {
					required: "Lūdzu, aizpildiet Pavadzīmes Nr. lauku!",
					number: "Pavadzīmes Nr. drīkst saturēt tikai ciparus!",
					min: "Pavadzīmes Nr. jābūt lielākam par nulli!",
					max: "Pavadzīmes Nr. jābūt ne vairāk kā 11 ciparus garam!",
					IsValidIntegerNumber: "Pavadzīmes Nr. drīkst saturēt tikai ciparus!",
					remote: "Pavadzīme ar šādu numuru jau eksistē!",
				},
				beam_count: {
					required: "Lūdzu, aizpildiet 'Apaļkoku skaits' lauku!",
					number: "Apaļkoku skaits drīkst saturēt tikai ciparus!",
					min: "Apaļkoku skaitam jābūt lielākam par nulli!",
					max: "Apaļkoku skaitam jābūt ne vairāk kā 11 ciparus garam!",
					IsValidIntegerNumber: "Apaļkoku skaits drīkst saturēt tikai ciparus!",
				},
				sizes: {
					required: "Lūdzu, izvēlieties kubatūras izmēru",
				},
				lumber_count: {
					required: "Lūdzu, aizpildiet 'Zāģmateriālu skaits' lauku!",
					number: "Zāģmateriālu skaits drīkst saturēt tikai ciparus!",
					min: "Zāģmateriālu skaitam jābūt lielākam par nulli!",
					max: "Zāģmateriālu skaitam jābūt ne vairāk kā 11 ciparus garam!",
					IsValidIntegerNumber: "Zāģmateriālu skaits drīkst saturēt tikai ciparus!",
				},
				lumber_capacity: {
					required: "Lūdzu, aizpildiet 'Zāģmateriālu tilpums' lauku!",
					number: "Zāģmateriālu tilpums drīkst saturēt tikai ciparus!",
					min: "Zāģmateriālu tilpumam jābūt lielākam par nulli!",
					max: "Zāģmateriālu tilpumam jābūt ne vairāk kā 11 ciparus garam!",
					step: "Maksimums 3 cipari aiz komata",
					IsValidFloatNumber: "Zāģmateriālu tilpumam jābūt lielākam par nulli!",
					IsValidFloatNumberWithThreeDigitsAfterDot: "Zāģmateriālu tilpums drīkst saturēt tikai ciparus!",
				},
				note: {
					minlength: "Citas piezīmes jābūt garumā no 3 simboliem līdz 255 simboliem!",
					maxlength: "Citas piezīmes jābūt garumā no 3 simboliem līdz 255 simboliem!",
					IsValidText: "Citas piezīmes drīkst saturēt tikai latīņu burtus, ciparus un speciālos simbolus!", 
				},
				"maintenance_times[]": {
					number: "Remonta minūtes drīkst saturēt tikai ciparus!",
					min: "Remonta minūtem jābūt lielākam par nulli!",
					max: "Remonta minūtem jābūt ne vairāk kā 11 ciparus garam!",
					IsValidIntegerNumber: "Remonta minūtes drīkst saturēt tikai ciparus!",
				},
				"maintenance_notes[]": {
					minlength: "Remonta piezīmei jābūt garumā no 3 simboliem līdz 255 simboliem!",
					maxlength: "Remonta piezīmei jābūt garumā no 3 simboliem līdz 255 simboliem!",
					IsValidText: "Remonta piezīme drīkst saturēt tikai latīņu burtus, ciparus un speciālos simbolus!",
				},
				shifts: {
					required: "Lūdzu, izvēlieties maiņu!",
				},
				"working[]": {
					required: "Lūdzu, aizpildiet darbinieku tabulu!",
					IsValidDropdownWorkingHours: "Lūdzu, aizpildiet darbinieku tabulu!",
				},
			}
		});
	});
});