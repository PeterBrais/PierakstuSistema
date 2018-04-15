$(document).ready(function(){

	//Update efficiency rate
	var summ = Number($('#summ').html());
	var lengthening_hours = Number($('#lengthening_hours').html());
	var lenghtening_overtime = Number($('#lengthening_overtime').html());
	var efficiency = Number($('#efficiency_rate').val())

	var lenghtening = lengthening_hours+lenghtening_overtime;
	var total = ((summ/100)*efficiency)+summ+lenghtening;
	var percentage = ((summ/100)*efficiency);
	$('#total_summ').html(total);
	$('#percentage_summ').html(percentage);


	$('#efficiency_rate').on('input', function(){
		var efficiency = Number($('#efficiency_rate').val())

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

	//Input border style change
	$('#order_input').blur(function(){
		if ($(this).val()) {
			$(this).css("border","0");
			$(this).addClass('bordered');   
		}
	});
	$('#order_input').focus(function(){
		$(this).removeClass('bordered');
		$(this).css("border","1px solid #ff0000");
	});
	
});