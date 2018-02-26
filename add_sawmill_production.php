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

						<form action="" method="POST">
							<div class="form-group row">
								<label class="col-md-2 offset-md-1 col-form-label">Datums</label>
								<div class="col-md-5">
									<input class="form-control" type="date" name="date" aria-describedby="dateArea">
									<small id="dateArea" class="form-text text-muted">
										* Satur tikai datuma formātu, piemēram, kā: YYYY-MM-DD *
									</small>
								</div>
								<div class="col-md-4">
									<?php
										if(isset($_SESSION['']))
										{
									?>
										<div class="alert alert-danger alert-size" role="alert">
											<?=$_SESSION['']?>
										</div>
									<?php
											unset($_SESSION['']);
										}
									?>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-md-2 offset-md-1 col-form-label">Laiks</label>
								<div class="col-md-5 row">
									<div class="col-md-6">
										<input class="form-control" type="time" name="time_from" aria-describedby="timeFromArea">
									</div>
									<div class="col-md-6">
										<input class="form-control" type="time" name="time_to" aria-describedby="timeFromArea">
									</div>
									<small id="timeFromArea" class="form-text text-muted">
										* Satur tikai skaitļus un kolu laika formā, piemēram, kā: 00:00 *
									</small>
								</div>

								<div class="col-md-4">
									<?php
										if(isset($_SESSION['']))
										{
									?>
										<div class="alert alert-danger alert-size" role="alert">
											<?=$_SESSION['']?>
										</div>
									<?php
											unset($_SESSION['']);
										}
									?>
								</div>
							</div>							
							<div class="form-group row">
								<label class="col-md-2 offset-md-1 col-form-label">Pavadzīmes Nr.</label>
								<div class="col-md-5">
									<input class="form-control" type="text" name="invoice" aria-describedby="invoiceArea">
									<small id="invoiceArea" class="form-text text-muted">
										* Satur tikai skaitļus *
									</small>
								</div>
								<div class="col-md-4">
									<?php
										if(isset($_SESSION['']))
										{
									?>
										<div class="alert alert-danger alert-size" role="alert">
											<?=$_SESSION['']?>
										</div>
									<?php
											unset($_SESSION['']);
										}
									?>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-md-2 offset-md-1 col-form-label">
									Apaļkoku skaits
								</label>
								<div class="col-md-5">
									<input class="form-control" type="text" name="beem_count" aria-describedby="beemCountArea">
									<small id="beemCountArea" class="form-text text-muted">
										* Satur tikai skaitļus, kopējo (gab) skaitu *
									</small>
								</div>
								<div class="col-md-4">
									<?php
										if(isset($_SESSION['']))
										{
									?>
										<div class="alert alert-danger alert-size" role="alert">
											<?=$_SESSION['']?>
										</div>
									<?php
											unset($_SESSION['']);
										}
									?>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-md-2 offset-md-1 col-form-label">Kubatūras izmērs</label>
								<div class="col-md-5">
									<?php  ?> include
								</div>
								<div class="col-md-4">
									<?php
										if(isset($_SESSION['']))
										{
									?>
										<div class="alert alert-danger alert-size" role="alert">
											<?=$_SESSION['']?>
										</div>
									<?php
											unset($_SESSION['']);
										}
									?>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-md-2 offset-md-1 col-form-label">Apaļkoku tilpums</label>
								<div class="col-md-5">
									<p class="form-control-static">ApaļķokuSkaits * KubatūrasIzvēle m<sup>3</sup></p>
								</div>
								<div class="col-md-4">
									<?php
										if(isset($_SESSION['']))
										{
									?>
										<div class="alert alert-danger alert-size" role="alert">
											<?=$_SESSION['']?>
										</div>
									<?php
											unset($_SESSION['']);
										}
									?>
								</div>	
							</div>
							<div class="form-group row">
								<label class="col-md-2 offset-md-1 col-form-label">
									Zāģmatariālu skaits
								</label>
								<div class="col-md-5">
									<input class="form-control" type="text" name="lumber_count" aria-describedby="lumberCountArea">
									<small id="lumberCountArea" class="form-text text-muted">
										* Satur tikai skaitļus, kopējo (gab) skaitu *
									</small>
								</div>
								<div class="col-md-4">
									<?php
										if(isset($_SESSION['']))
										{
									?>
										<div class="alert alert-danger alert-size" role="alert">
											<?=$_SESSION['']?>
										</div>
									<?php
											unset($_SESSION['']);
										}
									?>
								</div>	
							</div>
							<div class="form-group row">
								<label class="col-md-2 offset-md-1 col-form-label">
									Zāģmatariālu tilpums
								</label>
								<div class="col-md-5">
									<input class="form-control" type="text" name="lumber_capacity" aria-describedby="lumberCapacityArea">
									<small id="lumberCapacityArea" class="form-text text-muted">
										* Satur tikai skaitļus, kopējo m<sup>3</sup> tilpumu *
									</small>
								</div>
								<div class="col-md-4">
									<?php
										if(isset($_SESSION['']))
										{
									?>
										<div class="alert alert-danger alert-size" role="alert">
											<?=$_SESSION['']?>
										</div>
									<?php
											unset($_SESSION['']);
										}
									?>
								</div>	
							</div>
							<div class="form-group row">
								<label class="col-md-2 offset-md-1 col-form-label">
									Citas piezīmes
								</label>
								<div class="col-md-5">
									<textarea class="form-control rounded-0" name="note" rows="3" aria-describedby="noteArea"></textarea>
									<small id="noteArea" class="form-text text-muted">
										* Nav obligāts *
									</small>
								</div>
								<div class="col-md-4">
									<?php
										if(isset($_SESSION['']))
										{
									?>
										<div class="alert alert-danger alert-size" role="alert">
											<?=$_SESSION['']?>
										</div>
									<?php
											unset($_SESSION['']);
										}
									?>
								</div>	
							</div>

							<div class="form-group row" id="maintenance_select">
								<label class="col-md-2 offset-md-1 col-form-label">Remontlaiks</label>
								<div class="col-md-1">
									<input class="form-control" type="text" name="" aria-describedby="lumberCapacityArea">
								</div>
								<div class="col-md-4">
									<input class="form-control" type="text" name="" aria-describedby="lumberCapacityArea">
								</div>
								<div class="col-md-1">
									<button type="button" name="add" id="add" class="btn btn-success">+</button>
								</div>
								<div class="col-md-3">
									<?php
										if(isset($_SESSION['']))
										{
									?>
										<div class="alert alert-danger alert-size" role="alert">
											<?=$_SESSION['']?>
										</div>
									<?php
											unset($_SESSION['']);
										}
									?>
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
	var remove_btn = '<div class="col-md-4"><button type="button" class="btn btn-danger remove">X</button></div>';

	$('#add').click(function(){ 
		$.get('position_select.php', function(result){
			var positionSelect = '<div class="offset-md-3 col-md-5 position-select">';
			positionSelect += result+'</div>'+remove_btn;
			$('#maintenance_select').append(positionSelect);
		});
	});

	$(document).on('click', '.remove', function(){  
		$(this).parent().prev('.position-select').remove();
		$(this).parent().remove();
	}); 

});  
</script>

<?php
	include_once "footer.php";
?>