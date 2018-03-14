<?php
	include_once "../header.php";

	if(!isset($_SESSION['id']) && ($_SESSION['role']) == "p")	//Adding new sorting production available only if user has sign up and role is Manager
	{
		header("Location: /");
		exit();
	}
?>

<!-- Add sorting production -->
	<div class="container">
		<div class="row cont-space">
			<div class="col-md-12">
				<div id="message">
					<?php include "../message.php"; ?>
				</div>
				<div class="card">
					<div class="card-body">
						<h4 class="card-title text-center">Pievienot jaunu šķirošanas produkciju</h4>

						<form action="new_sorting_production" method="POST">
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
									Izmēri
									<span class="text-danger" title="Šie lauki ir obligāti">
										&#10033;
									</span>
								</label>
								<div class="col-md-5">
									<div class="row">
										<div class="col-md-4">
											<input class="form-control" type="number" min="0" name="thick" aria-describedby="timeFromArea" placeholder="Biezums" id="thickeness">
										</div>
										<div class="col-md-4">
											<input class="form-control" type="number" min="0" name="width" aria-describedby="timeFromArea" placeholder="Platums" id="width">
										</div>
										<div class="col-md-4">
											<input class="form-control" type="number" min="0" name="length" aria-describedby="timeFromArea" placeholder="Garums" id="length">
										</div>
									</div>
									<small id="timeFromArea" class="form-text text-muted">
										* Satur tikai ciparus *
									</small>
								</div>
								<div class="col-md-4">
									<?php
										if(isset($_SESSION['sizes']))
										{
									?>
										<div class="alert alert-danger alert-size" role="alert">
											<?=$_SESSION['sizes']?>
										</div>
									<?php
											unset($_SESSION['sizes']);
										}
									?>
								</div>
							</div>	
							<div class="form-group row">
								<label class="col-md-2 offset-md-1 col-form-label">
									Skaits
									<span class="text-danger" title="Šis lauks ir obligāts">
										&#10033;
									</span>
								</label>
								<div class="col-md-5">
									<input class="form-control" type="number" min="0" name="sawn_count" aria-describedby="sawnCountArea" id="sawn_count" placeholder="Kopējais skaits">
									<small id="sawnCountArea" class="form-text text-muted">
										* Satur tikai ciparus, kopējo (gab) skaitu *
									</small>
								</div>
								<div class="col-md-4">
									<?php
										if(isset($_SESSION['sawn_count']))
										{
									?>
										<div class="alert alert-danger alert-size" role="alert">
											<?=$_SESSION['sawn_count']?>
										</div>
									<?php
											unset($_SESSION['sawn_count']);
										}
									?>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-md-2 offset-md-1 control-label">
									Tilpums
								</label>
								<div class="col-md-5">
									<p class="form-control-static" id="sawn_capacity"> m<sup>3</sup></p>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-md-2 offset-md-1 control-label">
									Defektu skaits
								</label>
								<div class="col-md-5">
									<input class="form-control" type="number" min="0" name="defect_count" aria-describedby="sawnCountArea" id="defect_count" placeholder="Defektu skaits">
								</div>
								<div class="col-md-4">
									<?php
										if(isset($_SESSION['defect_count']))
										{
									?>
										<div class="alert alert-danger alert-size" role="alert">
											<?=$_SESSION['defect_count']?>
										</div>
									<?php
											unset($_SESSION['defect_count']);
										}
									?>
								</div>
							</div>
							<hr>

							<h5 class="text-center">Sašķirotā produkcija</h5>
							<div id="sorted_select">
								<?php include_once "sorted_production_inputs.php"; ?>
							</div>
							<div class="form-group row">
								<div class="offset-md-3 col-md-4">
									<button type="button" name="add" id="add" class="btn btn-success">Pievienot sašķiroto produkciju</button>
								</div>
							</div>
							<hr>

							<h5 class="text-center">Šķirotavas darbinieki</h5>
							<?php include_once "sorting_employees_table.php"; ?>
							
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
	//Add more sorted production
	$('#add').click(function(){ 
		var Sorting = $(`<hr><div class="form-group row">
							<label class="col-md-2 offset-md-1 col-form-label">
								Veids
								<span class="text-danger" title="Šis lauks ir obligāts">
									&#10033;
								</span>
							</label>
							<div class="col-md-5">
								<select class="custom-select" name="sorted_types[]">
									<option selected value="0">Izvēlieties šķirošanas veidu</option>
									<option value="1">Šķirots</option>
									<option value="2">Garināts</option>
								</select>
							</div>
						</div>	
						<div class="form-group row">
							<label class="col-md-2 offset-md-1 col-form-label">
								Skaits
								<span class="text-danger" title="Šis lauks ir obligāts">
									&#10033;
								</span>
							</label>
							<div class="col-md-5">
								<input class="form-control sorted_counts" type="number" min="0" name="sorted_count[]" aria-describedby="sawnCountArea" placeholder="Kopējais skaits">
								<small id="sawnCountArea" class="form-text text-muted">
									* Satur tikai ciparus, kopējo (gab) skaitu *
								</small>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-md-2 offset-md-1 col-form-label">
								Izmēri
								<span class="text-danger" title="Šie lauki ir obligāti">
									&#10033;
								</span>
							</label>
							<div class="col-md-5">
								<div class="row">
									<div class="col-md-4">
										<input class="form-control sorted_thicknesses" type="number" min="0" name="sorted_thick[]" aria-describedby="timeFromArea" placeholder="Biezums">
									</div>
									<div class="col-md-4">
										<input class="form-control sorted_widths" type="number" min="0" name="sorted_width[]" aria-describedby="timeFromArea" placeholder="Platums">
									</div>
									<div class="col-md-4">
										<input class="form-control sorted_lengths" type="number" min="0" name="sorted_length[]" aria-describedby="timeFromArea" placeholder="Garums">
									</div>
								</div>
								<small id="timeFromArea" class="form-text text-muted">
									* Satur tikai ciparus *
								</small>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-md-2 offset-md-1 control-label">
								Tilpums
							</label>
							<div class="col-md-5">
								<p class="form-control-static sorted_capacities"> m<sup>3</sup></p>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-md-2 offset-md-1 control-label">
								Tilpums / gab
							</label>
							<div class="col-md-5">
								<p class="form-control-static sorted_capacities_pieces"> m<sup>3</sup></p>
							</div>
							<div class="col-md-4">
								<button type="button" class="btn btn-danger remove mb-2">Noņemt</button>
							</div>
						</div>`).hide().fadeIn('slow');
	$('#sorted_select').append(Sorting);
	});

	$(document).on('click', '.remove', function(){
		$(this).parent().parent().fadeOut('slow', function(){
			$(this).remove();
		});
		$(this).parent().parent().prev().fadeOut('slow', function(){
			$(this).remove();
		});
		$(this).parent().parent().prev().prev().fadeOut('slow', function(){
			$(this).remove();
		});
		$(this).parent().parent().prev().prev().prev().fadeOut('slow', function(){
			$(this).remove();
		});
		$(this).parent().parent().prev().prev().prev().prev().fadeOut('slow', function(){
			$(this).remove();
		});
		$(this).parent().parent().prev().prev().prev().prev().prev().fadeOut('slow', function(){
			$(this).remove();
		});
	});


	//Show sawn production capacity
	var measure_unit = ' m<sup>3</sup>';
	$('#sawn_count, #thickeness, #width, #length').change(function(){
		var count = $('#sawn_count').val();
    	var thickeness = $("#thickeness").val();
    	var width = $("#width").val();
    	var length = $("#length").val();

		var capacity = ((thickeness * width * length)/1000000000)*count;
		if(isNaN(capacity))
		{
			capacity = "0.000";
			$('#sawn_capacity').html(capacity+measure_unit);
		}
		else
		{
			capacity = capacity.toFixed(3);
			$('#sawn_capacity').html(capacity+measure_unit);
		}
	});

	//Show sorted production capacities and capacities per piece
	var measure_piece = ' m<sup>3</sup> / gab';
	$(document).on('input', '.sorted_counts', function(){
	    var sorted_count = $(this).val();
	    var sorted_thickness = $(this).parent().parent().next().children().find('.sorted_thicknesses').val();
	    var sorted_width = $(this).parent().parent().next().children().find('.sorted_widths').val();
	    var sorted_length = $(this).parent().parent().next().children().find('.sorted_lengths').val();
	    var total_cap = ((sorted_thickness*sorted_width*sorted_length)/1000000000)*sorted_count;
	    var total_cap_piece = (sorted_thickness*sorted_width*sorted_length)/1000000000;

	    if(isNaN(total_cap))
		{
			total_cap = "0.000";
		}
		else
		{
			total_cap = total_cap.toFixed(3);
		}
		$(this).parent().parent().next().next().children().find('.sorted_capacities').html(total_cap+measure_unit);

		if(isNaN(total_cap_piece))
		{
			total_cap_piece = "0.00000"
		}
		else
		{
			total_cap_piece = total_cap_piece.toFixed(5);
		}
		$(this).parent().parent().next().next().next().children().find('.sorted_capacities_pieces').html(total_cap_piece+measure_piece);
	});
});
</script>

<?php
	include_once "../footer.php";
?>