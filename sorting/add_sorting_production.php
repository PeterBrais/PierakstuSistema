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
		var Sorting = `<hr><div class="form-group row">
							<label class="col-md-2 offset-md-1 col-form-label">
								Veids
								<span class="text-danger" title="Šis lauks ir obligāts">
									&#10033;
								</span>
							</label>
							<div class="col-md-5">
								<select class="custom-select" name="type[]">
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
								<input class="form-control" type="number" min="0" name="sorted_count[]" aria-describedby="sawnCountArea" placeholder="Kopējais skaits">
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
										<input class="form-control" type="number" min="0" name="sorted_thick[]" aria-describedby="timeFromArea" placeholder="Biezums" id="thickeness">
									</div>
									<div class="col-md-4">
										<input class="form-control" type="number" min="0" name="sorted_width[]" aria-describedby="timeFromArea" placeholder="Platums" id="width">
									</div>
									<div class="col-md-4">
										<input class="form-control" type="number" min="0" name="sorted_length[]" aria-describedby="timeFromArea" placeholder="Garums" id="length">
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
								<p class="form-control-static" id="sorted_capacity"> m<sup>3</sup></p>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-md-2 offset-md-1 control-label">
								Tilpums / gab
							</label>
							<div class="col-md-5">
								<p class="form-control-static" id="sorted_capacity_piece"> m<sup>3</sup></p>
							</div>
							<div class="col-md-4">
								<button type="button" class="btn btn-danger remove mb-2">Noņemt</button>
							</div>
						</div>`;
	$('#sorted_select').append(Sorting);
	});

	$(document).on('click', '.remove', function(){  
		$(this).parent().parent().prev().remove();
		$(this).parent().parent().prev().remove();
		$(this).parent().parent().prev().remove();
		$(this).parent().parent().prev().remove();
		$(this).parent().parent().prev().remove();
		$(this).parent().parent().remove();
	});


	//Show sawn capacity
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
});
</script>

<?php
	include_once "../footer.php";
?>