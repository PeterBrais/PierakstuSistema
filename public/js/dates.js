$(document).ready(function(){
	$(".datepicker").datepicker({
		dateFormat: "yy-mm-dd"
	});
});

$(function(){
	$(".datepicker").datepicker();
});

$(function() {
	$(".timepicker").timepicker({'timeFormat': 'H:i'});
});

$(function() {
	$(".monthAndYear").datepicker({
		changeMonth: true,
		changeYear: true,
		showButtonPanel: true,
		dateFormat: 'MM yy',
		onClose: function(dateText, inst){ 
			$(this).datepicker('setDate', new Date(inst.selectedYear, inst.selectedMonth, 1));
		}
	});
});