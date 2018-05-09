$(document).ready(function(){

	//Update efficiency rate
	var summ = Number($('#summ').html());
	var lengthening_hours = Number($('#lengthening_hours').html());
	var efficiency = Number($('#efficiency_rate').val());

	var lenghtening = lengthening_hours;
	var total = ((summ/100)*efficiency)+summ+lenghtening;
	var percentage = ((summ/100)*efficiency);
	total = total.toFixed(2);
	percentage = percentage.toFixed(2);
	$('#total_summ').html(total);
	$('#percentage_summ').html(percentage);


	$('#efficiency_rate').on('input', function(){
		var efficiency = Number($('#efficiency_rate').val());

		var total = ((summ/100)*efficiency)+summ+lenghtening;
		var percentage = ((summ/100)*efficiency);
		if(isNaN(total))
		{
			total = "0.00";
			percentage = "0.00";
			$('#total_summ').html(total);
			$('#percentage_summ').html(percentage);
		}
		else
		{
			total = total.toFixed(2);
			percentage = percentage.toFixed(2);
			$('#total_summ').html(total);
			$('#percentage_summ').html(percentage);
		}
	});
	
});

function printPage() {
	window.print();
}