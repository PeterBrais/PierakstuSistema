$(document).ready(function(){

	//Update efficiency rate
	var summ = Number($('#summ').html());
	var efficiency = Number($('#efficiency_rate').val());
	var total = ((summ/100)*efficiency)+summ;
	total = total.toFixed(2);
	$('#total_summ').html(total);

	$('#efficiency_rate').on('input', function(){
		var efficiency = Number($('#efficiency_rate').val());

		var total = ((summ/100)*efficiency)+summ;
		if(isNaN(total))
		{
			total = "0.00";
			$('#total_summ').html(total);
		}
		else
		{
			total = total.toFixed(2);
			$('#total_summ').html(total);
		}
	});
	
});

function printPage() {
	window.print();
}