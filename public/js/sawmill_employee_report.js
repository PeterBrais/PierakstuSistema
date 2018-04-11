$(document).ready(function(){

	//Update efficiency rate
	var summ = Number($('#summ').html());

	$('#efficiency_rate').on('input', function(){
		var efficiency = Number($('#efficiency_rate').val())

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

	//Input border style change
	$('#order_input').blur(function(){
		if ($(this).val()) {
			$(this).addClass('bordered');   
		}
	});
	$('#order_input').focus(function(){
		$(this).removeClass('bordered');  
	});
	
});