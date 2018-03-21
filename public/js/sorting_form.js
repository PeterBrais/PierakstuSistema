$(function(){
    jQuery.validator.setDefaults({
		errorPlacement: function(error, element) {
			if(element.attr("name") == "time_from" || element.attr("name") == "time_to")
			{
				error.addClass('alert alert-danger alert-size mb-1')
				error.appendTo( element.parent().parent().parent().next() );
			}
			else if(element.attr("name") == "thick" || element.attr("name") == "width" || element.attr("name") == "length")
			{
				error.addClass('alert alert-danger alert-size mb-1')
				error.appendTo( element.parent().parent().parent().next() );
			}
			else if(element.attr("name") == "sorted_thick[]" || element.attr("name") == "sorted_width[]" || element.attr("name") == "sorted_length[]")
			{
				error.addClass('alert alert-danger alert-size mb-1')
				error.appendTo( element.parent().parent().parent().next() );
			}
			else if(element.hasClass( "working_hours_class" ) || element.hasClass( "nonworking_hours_class" ))
			{
				error.addClass('alert alert-danger alert-size mt-1')
				error.appendTo( element.parent() );
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
		//Validate sawmill production input form
		$('#sorting_form').validate({		
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
				},
				thick: {
					required: true,
					number: true,
					min: 0,
					max: 99999999999,
					IsValidIntegerNumber: true,
					IsValidInteger: true,
				},
				width: {
					required: true,
					number: true,
					min: 0,
					max: 99999999999,
					IsValidIntegerNumber: true,
					IsValidInteger: true,
				},
				length: {
					required: true,
					number: true,
					min: 0,
					max: 99999999999,
					IsValidIntegerNumber: true,
					IsValidInteger: true,
				},
				sawn_count: {
					required: true,
					number: true,
					min: 0,
					max: 99999999999,
					IsValidIntegerNumber: true,
					IsValidInteger: true,
				},
				defect_count: {
					number: true,
					min: 0,
					max: 99999999999,
					IsValidIntegerNumber: true,
				},
				"sorted_types[]": {
					ChosenShiftDropdown: true,
				},
				"sorted_count[]": {
					required: true,
					number: true,
					min: 0,
					max: 99999999999,
					IsValidIntegerNumber: true,
					IsValidInteger: true,
				},
				"sorted_thick[]": {
					required: true,
					number: true,
					min: 0,
					max: 99999999999,
					IsValidIntegerNumber: true,
					IsValidInteger: true,
				},
				"sorted_width[]": {
					required: true,
					number: true,
					min: 0,
					max: 99999999999,
					IsValidIntegerNumber: true,
					IsValidInteger: true,
				},
				"sorted_length[]": {
					required: true,
					number: true,
					min: 0,
					max: 99999999999,
					IsValidIntegerNumber: true,
					IsValidInteger: true,
				},
				"working_hours[]": {
					number: true,
					min: 1,
					max: 24,
					IsValidHours: true,
				},
				"nonworking[]": {
					// ChosenWorkplaceDropdown: true,
				},
			},
			messages: {
				date: {
					required: "Lūdzu aizpildiet Datums lauku!",
					date: "Lūdzu ievadiet korektu datumu (GGGG-MM-DD vai GGGG-MM-DD)!",
					IsValidDate: "Lūdzu ievadiet korektu datumu (GGGG-MM-DD vai GGGG-MM-DD)!",
				},
				time_from: {
					required: "Lūdzu aizpildiet 'Laiks no' lauku!",
					IsValidTime: "Lūdzu ievadiet korektu laiku, formā: hh:mm!",
				},
				time_to: {
					required: "Lūdzu aizpildiet 'Laiks līdz' lauku!",
					IsValidTime: "Lūdzu ievadiet korektu laiku, formā: hh:mm!",
				},
				invoice: {
					required: "Lūdzu aizpildiet Pavadzīmes Nr. lauku!",
					number: "Pavadzīmes Nr. drīkst saturēt tikai ciparus!",
					min: "Pavadzīmes Nr. jābūt lielākam par nulli!",
					max: "Pavadzīmes Nr. jābūt ne vairāk kā 12 ciparus garam!",
					IsValidIntegerNumber: "Pavadzīmes Nr. drīkst saturēt tikai ciparus!",
				},
				thick: {
					required: "Lūdzu aizpildiet 'Biezums' lauku!",
					number: "Biezums drīkst saturēt tikai ciparus!",
					min: "Biezums jābūt lielākam par nulli!",
					max: "Biezums jābūt ne vairāk kā 12 ciparus garam!",
					IsValidIntegerNumber: "Biezums drīkst saturēt tikai ciparus!",
					IsValidInteger: "Biezums jābūt lielākam par nulli!",
				},
				width: {
					required: "Lūdzu aizpildiet 'Platums' lauku!",
					number: "Platums skaits drīkst saturēt tikai ciparus!",
					min: "Platums jābūt lielākam par nulli!",
					max: "Platums jābūt ne vairāk kā 12 ciparus garam!",
					IsValidIntegerNumber: "Platums drīkst saturēt tikai ciparus!",
					IsValidInteger: "Platums jābūt lielākam par nulli!",
				},
				length: {
					required: "Lūdzu aizpildiet 'Garums' lauku!",
					number: "Garums drīkst saturēt tikai ciparus!",
					min: "Garums jābūt lielākam par nulli!",
					max: "Garums jābūt ne vairāk kā 12 ciparus garam!",
					IsValidIntegerNumber: "Garums drīkst saturēt tikai ciparus!",
					IsValidInteger: "Garums jābūt lielākam par nulli!",
				},
				sawn_count: {
					required: "Lūdzu aizpildiet 'Skaits' lauku!",
					number: "Skaits drīkst saturēt tikai ciparus!",
					min: "Skaits jābūt lielākam par nulli!",
					max: "Skaits jābūt ne vairāk kā 12 ciparus garam!",
					IsValidIntegerNumber: "Skaits drīkst saturēt tikai ciparus!",
					IsValidInteger: "Skaits jābūt lielākam par nulli!",
				},
				defect_count: {
					number: "Defektu skaits drīkst saturēt tikai ciparus!",
					min: "Defektu skaits jābūt lielākam par nulli!",
					max: "Defektu skaits jābūt ne vairāk kā 12 ciparus garam!",
					IsValidIntegerNumber: "Defektu skaits drīkst saturēt tikai ciparus!",
				},
				"sorted_types[]": {
					ChosenShiftDropdown: "Lūdzu izvēlieties Šķirošanas veidu!",
				},
				"sorted_count[]": {
					required: "Lūdzu aizpildiet 'Skaits' lauku!",
					number: "Skaits drīkst saturēt tikai ciparus!",
					min: "Skaits jābūt lielākam par nulli!",
					max: "Skaits jābūt ne vairāk kā 12 ciparus garam!",
					IsValidIntegerNumber: "Skaits drīkst saturēt tikai ciparus!",
					IsValidInteger: "Skaits jābūt lielākam par nulli!",
				},
				"sorted_thick[]": {
					required: "Lūdzu aizpildiet 'Biezums' lauku!",
					number: "Biezums drīkst saturēt tikai ciparus!",
					min: "Biezums jābūt lielākam par nulli!",
					max: "Biezums jābūt ne vairāk kā 12 ciparus garam!",
					IsValidIntegerNumber: "Biezums drīkst saturēt tikai ciparus!",
					IsValidInteger: "Biezums jābūt lielākam par nulli!",
				},
				"sorted_width[]": {
					required: "Lūdzu aizpildiet 'Platums' lauku!",
					number: "Platums skaits drīkst saturēt tikai ciparus!",
					min: "Platums jābūt lielākam par nulli!",
					max: "Platums jābūt ne vairāk kā 12 ciparus garam!",
					IsValidIntegerNumber: "Platums drīkst saturēt tikai ciparus!",
					IsValidInteger: "Platums jābūt lielākam par nulli!",
				},
				"sorted_length[]": {
					required: "Lūdzu aizpildiet 'Garums' lauku!",
					number: "Garums drīkst saturēt tikai ciparus!",
					min: "Garums jābūt lielākam par nulli!",
					max: "Garums jābūt ne vairāk kā 12 ciparus garam!",
					IsValidIntegerNumber: "Garums drīkst saturēt tikai ciparus!",
					IsValidInteger: "Garums jābūt lielākam par nulli!",
				},
				"working_hours[]": {
					number: "Nostrādātās stundas drīkst sastāvēt tikai no cipariem!",
					min: "Nostrādātām stundām jābūt lielākām par nulli!",
					max: "Nostrādātās stundas nevar būt vairāk par 24",
					IsValidHours: "Nostrādātās stundas drīkst sastāvēt tikai no cipariem!",
				},
				"nonworking[]": {
					// ChosenWorkplaceDropdown: true,
				},
			}
		});
	});
});