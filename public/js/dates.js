//Gets opened file name in href wihout GET
var url = window.location.pathname;
var file_name = url.substring(url.lastIndexOf('/')+1);
console.log(file_name);

$(document).ready(function(){
	$(".datepicker").datepicker({
		dateFormat: "yy-mm-dd"
	});
   
	$(".monthAndYearInput").datepicker({
		changeMonth: true,
		changeYear: true,
		dateFormat: 'MM yy',
		showButtonPanel: true,
		 
		onClose: function(){
			var iMonth = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
			var iYear = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
			$(this).datepicker('setDate', new Date(iYear, iMonth, 1));

			//Formats period for GET
			var period = $.datepicker.formatDate( "yy-mm", new Date(iYear, iMonth, 1));
			window.location.href = file_name + "?p=" + period;
		},

		beforeShow: function(){
			if((selDate = $(this).val()).length > 0)
			{
				iYear = selDate.substring(selDate.length - 4, selDate.length);
				iMonth = jQuery.inArray(selDate.substring(0, selDate.length - 5),
				$(this).datepicker('option', 'monthNames'));
				$(this).datepicker('option', 'defaultDate', new Date(iYear, iMonth, 1));
				$(this).datepicker('setDate', new Date(iYear, iMonth, 1));
			}
		}
	});

	//Removes calendar days from input
	$(".monthAndYearInput").focus(function(){
		$(".ui-datepicker-calendar").hide();
	});

});

$(function(){
	$(".datepicker").datepicker();
});

//Time input picker
$(function(){
	$(".timepicker").timepicker({'timeFormat': 'H:i'});
});