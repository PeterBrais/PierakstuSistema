<?php
	include_once "header.php";

	if(!isset($_SESSION['id']) && ($_SESSION['role']) == "p")	//Adding new production available only if user has sign up and role is Manager
	{
		header("Location: /");
		exit();
	}
?>

	<!-- Add sawmill production -->
	<div class="container">
		<div class="row cont-space">
			<div class="col-md-12">
				<div id="message">
					<?php include "message.php"; ?>
				</div>
				<div class="card">
					<div class="card-body">
						<h4 class="card-title text-center">Pievienot jaunu zāģētavas produkciju</h4>

						<form action="new_sawmill_production" method="POST">
							<div class="form-group row">
								<label class="col-md-2 offset-md-1 col-form-label">
									Datums
									<span class="text-danger" title="Šis lauks ir obligāts">
										&#10033;
									</span>
								</label>
								<div class="col-md-5">
									<input class="form-control" type="text" name="date" aria-describedby="dateArea" placeholder="2000/01/01">
									<small id="dateArea" class="form-text text-muted">
										* Satur tikai datumu, piemēram, formātā: GGGG-MM-DD *
									</small>
								</div>
								<div class="col-md-4">
									<?php
										if(isset($_SESSION['date']))
										{
									?>
										<div class="alert alert-danger alert-size" role="alert">
											<?=$_SESSION['date']?>
										</div>
									<?php
											unset($_SESSION['date']);
										}
									?>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-md-2 offset-md-1 col-form-label">
									Laiks
									<span class="text-danger" title="Šie lauki ir obligāti">
										&#10033;
									</span>
								</label>
								<div class="col-md-5">
									<div class="row">
										<div class="col-md-6">
											<input class="form-control" type="time" name="time_from" aria-describedby="timeFromArea">
										</div>
										<div class="col-md-6">
											<input class="form-control" type="time" name="time_to" aria-describedby="timeFromArea">
										</div>
									</div>
									<small id="timeFromArea" class="form-text text-muted">
										* Satur tikai laikus, piemēram, formātā: 00:00 *
									</small>
								</div>
								<div class="col-md-4">
									<?php
										if(isset($_SESSION['time']))
										{
									?>
										<div class="alert alert-danger alert-size" role="alert">
											<?=$_SESSION['time']?>
										</div>
									<?php
											unset($_SESSION['time']);
										}
									?>
								</div>
							</div>							
							<div class="form-group row">
								<label class="col-md-2 offset-md-1 col-form-label">
									Pavadzīmes Nr.
									<span class="text-danger" title="Šis lauks ir obligāts">
										&#10033;
									</span>
								</label>
								<div class="col-md-5">
									<input class="form-control" type="text" name="invoice" aria-describedby="invoiceArea">
									<small id="invoiceArea" class="form-text text-muted">
										* Satur tikai ciparus *
									</small>
								</div>
								<div class="col-md-4">
									<?php
										if(isset($_SESSION['invoice']))
										{
									?>
										<div class="alert alert-danger alert-size" role="alert">
											<?=$_SESSION['invoice']?>
										</div>
									<?php
											unset($_SESSION['invoice']);
										}
									?>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-md-2 offset-md-1 col-form-label">
									Apaļkoku skaits
									<span class="text-danger" title="Šis lauks ir obligāts">
										&#10033;
									</span>
								</label>
								<div class="col-md-5">
									<input class="form-control" type="text" name="beam_count" aria-describedby="beamCountArea" id="beam_count_input">
									<small id="beamCountArea" class="form-text text-muted">
										* Satur tikai ciparus, kopējo (gab) skaitu *
									</small>
								</div>
								<div class="col-md-4">
									<?php
										if(isset($_SESSION['beam_count']))
										{
									?>
										<div class="alert alert-danger alert-size" role="alert">
											<?=$_SESSION['beam_count']?>
										</div>
									<?php
											unset($_SESSION['beam_count']);
										}
									?>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-md-2 offset-md-1 col-form-label">
									Kubatūras izmērs
									<span class="text-danger" title="Šis lauks ir obligāts">
										&#10033;
									</span>
								</label>
								<div class="col-md-5">
									<?php include "beam_size_select.php"; ?> 
								</div>
								<div class="col-md-4">
									<?php
										if(isset($_SESSION['beam_size']))
										{
									?>
										<div class="alert alert-danger alert-size" role="alert">
											<?=$_SESSION['beam_size']?>
										</div>
									<?php
											unset($_SESSION['beam_size']);
										}
									?>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-md-2 offset-md-1 control-label">
									Apaļkoku tilpums
								</label>
								<div class="col-md-5">
									<p class="form-control-static" id="beam_capacity"> m<sup>3</sup></p>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-md-2 offset-md-1 col-form-label">
									Zāģmatariālu skaits
									<span class="text-danger" title="Šis lauks ir obligāts">
										&#10033;
									</span>
								</label>
								<div class="col-md-5">
									<input class="form-control" type="text" name="lumber_count" aria-describedby="lumberCountArea">
									<small id="lumberCountArea" class="form-text text-muted">
										* Satur tikai ciparus, kopējo (gab) skaitu *
									</small>
								</div>
								<div class="col-md-4">
									<?php
										if(isset($_SESSION['lumber_count']))
										{
									?>
										<div class="alert alert-danger alert-size" role="alert">
											<?=$_SESSION['lumber_count']?>
										</div>
									<?php
											unset($_SESSION['lumber_count']);
										}
									?>
								</div>	
							</div>
							<div class="form-group row">
								<label class="col-md-2 offset-md-1 col-form-label">
									Zāģmatariālu tilpums
									<span class="text-danger" title="Šis lauks ir obligāts">
										&#10033;
									</span>
								</label>
								<div class="col-md-5">
									<input class="form-control" type="number" min="0" step="0.001" name="lumber_capacity" aria-describedby="lumberCapacityArea" placeholder="0,000">
									<small id="lumberCapacityArea" class="form-text text-muted">
										* Satur tikai ciparus, kopējo tilpumu m<sup>3</sup>. (Maksimums 3 cipari aiz komata) *
									</small>
								</div>
								<div class="col-md-4">
									<?php
										if(isset($_SESSION['lumber_capacity']))
										{
									?>
										<div class="alert alert-danger alert-size" role="alert">
											<?=$_SESSION['lumber_capacity']?>
										</div>
									<?php
											unset($_SESSION['lumber_capacity']);
										}
									?>
								</div>	
							</div>
							<div class="form-group row has-success">
								<label class="col-md-2 offset-md-1 col-form-label">
									Citas piezīmes
								</label>
								<div class="col-md-5">
									<textarea class="form-control rounded-0" name="note" rows="3" aria-describedby="noteArea"></textarea>
								</div>
								<div class="col-md-4">
									<?php
										if(isset($_SESSION['note']))
										{
									?>
										<div class="alert alert-danger alert-size" role="alert">
											<?=$_SESSION['note']?>
										</div>
									<?php
											unset($_SESSION['note']);
										}
									?>
								</div>	
							</div>
							<div class="form-group row" id="maintenance_select">
								<label class="col-md-2 offset-md-1 col-form-label">
									Remontlaiks
									<span id="show_required" class="text-danger" title="Šie lauki ir obligāti" style="display:none">
										&#10033;
									</span>
								</label>
								<div class="col-md-1">
									<input class="form-control" type="text" name="maintenance_times[]" aria-describedby="lumberCapacityArea" placeholder="Laiks">
								</div>
								<div class="col-md-4">
									<input class="form-control" type="text" name="maintenance_notes[]" aria-describedby="lumberCapacityArea" placeholder="Piezīme">
								</div>
								<div class="col-md-1">
									<button type="button" name="add" id="add" class="btn btn-success">+</button>
								</div>
								<div class="col-md-3">
									<?php
										if(isset($_SESSION['maintenance']))
										{
									?>
										<div class="alert alert-danger alert-size" role="alert">
											<?=$_SESSION['maintenance']?>
										</div>
									<?php
											unset($_SESSION['maintenance']);
										}
									?>
								</div>	
							</div>
							<div class="form-group row" id="shift_select">
								<label class="col-md-2 offset-md-1 col-form-label">
									Maiņa
									<span class="text-danger" title="Šis lauks ir obligāts">
										&#10033;
									</span>
								</label>
								<div class="col-md-5">
									<?php include "shift_select.php"; ?> 
								</div>
								<div class="col-md-4">
									<?php
										if(isset($_SESSION['shift']))
										{
									?>
										<div class="alert alert-danger alert-size" role="alert">
											<?=$_SESSION['shift']?>
										</div>
									<?php
											unset($_SESSION['shift']);
										}
									?>
								</div>
								<div class="col-md-12 mt-3" id="table_show">

								</div>
							</div>
							<div class="form-group row">
								<div class="col-md-3 offset-md-3">
									<button class="btn btn-info" type="submit" name="submit">Pievienot</button>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>

<script>  
$(document).ready(function(){
	var remove_btn = '<div class="col-md-4"><button type="button" class="btn btn-danger remove mt-2">X</button></div>';

	$('#add').click(function(){ 
		var maintenanceSelect = '<div class="offset-md-3 col-md-1"><input class="form-control mt-2" type="text" name="maintenance_times[]" placeholder="Laiks"></div><div class="col-md-4"><input class="form-control mt-2" type="text" name="maintenance_notes[]" placeholder="Piezīme"></div>'+remove_btn;
		$('#maintenance_select').append(maintenanceSelect);
	});

	$(document).on('click', '.remove', function(){  
		$(this).parent().prev().remove();
		$(this).parent().prev().remove();
		$(this).parent().remove();
	}); 

	//Show beam count and beam size multiplier
	var measure_unit = ' m<sup>3</sup>';
	$('#beam_count_input, #beam_size_select').change(function(){

		var count = Number($('#beam_count_input').val());
    	var size = $("#beam_size_select option:selected").text();

		var capacity = count*size;
		if(isNaN(capacity))
		{
			capacity = "0.000";
			$('#beam_capacity').html(capacity+measure_unit);
		}
		else
		{
			capacity = capacity.toFixed(3);
			$('#beam_capacity').html(capacity+measure_unit);
		}
	});

	//Show required input field notification, when multiple fields are chosen
	$(document).on('click', '#add, .remove', function(){
		if($(".remove")[0])
		{
			$('#show_required').show();
		}
		else
		{
			$('#show_required').hide();
		}
	});

	//Show all employees on selected shift
	$('#employees_shift').change(function(){ //id from file: shift_select.php
		var shift_id = $(this).val();

		$.ajax({
			url:"employee_times_table.php",
			method:"POST",
			data:{shift_id:shift_id},
			success:function(data){
				$('#table_show').html(data);
			}
		});
	});

});  
</script>

<?php
	include_once "footer.php";
?>