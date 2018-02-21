<?php
	include_once "header.php";

	if(!isset($_SESSION['id']) && ($_SESSION['role'] == "p") )	//Adding new employee possible if user role is Manager
	{
		header("Location: /");
		exit();
	}
?>
	<!-- Add employee -->
	<div class="container">
		<div class="row cont-space">
			<div class="col-md-12">

				<div id="message">
					<?php include "message.php"; ?>
				</div>
				
				<div class="card">
					<div class="card-body">
						<h4 class="card-title text-center">Pievienot jaunu darbinieku</h4>

						<form action="new_employee" method="POST" id="add_form">
							<div class="form-group row">
								<label class="col-md-2 offset-md-1 col-form-label">Vārds</label>
								<div class="col-md-5">
									<input class="form-control" type="text" name="name" aria-describedby="nameArea">
									<small id="nameArea" class="form-text text-muted">
										* Satur tikai lielos un mazos latīņu burtus *
									</small>
									<small id="nameArea" class="form-text text-muted">
										* Jābūt garumā no 3 līdz 50 rakstzīmēm *
									</small>
								</div>
								<div class="col-md-4">
									<?php
										if(isset($_SESSION['name']))
										{
									?>
										<div class="alert alert-danger" role="alert">
											<?=$_SESSION['name']?>
										</div>
									<?php
											unset($_SESSION['name']);
										}
									?>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-md-2 offset-md-1 col-form-label">Uzvārds</label>
								<div class="col-md-5">
									<input class="form-control" type="text" name="last" aria-describedby="lastNameArea">
									<small id="lastNameArea" class="form-text text-muted">
										* Satur tikai lielos un mazos latīņu burtus *
									</small>
									<small id="lastNameArea" class="form-text text-muted">
										* Jābūt garumā no 3 līdz 50 rakstzīmēm *
									</small>
								</div>
							</div>							
							<div class="form-group row">
								<label class="col-md-2 offset-md-1 col-form-label">Strādā no</label>
								<div class="col-md-5">
									<input class="form-control" type="time" name="timefrom" aria-describedby="timeFromArea">
									<small id="timeFromArea" class="form-text text-muted">
										* Satur tikai skaitļus un kolu laika formā, piemēram, kā: 00:00 *
									</small>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-md-2 offset-md-1 col-form-label">Strādā līdz</label>
								<div class="col-md-5">
									<input class="form-control" type="time" name="timeto" aria-describedby="timeToArea">
									<small id="timeToArea" class="form-text text-muted">
										* Satur tikai skaitļus un kolu laika formā, piemēram, kā: 00:00 *
									</small>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-md-2 offset-md-1 col-form-label">Darbavieta</label>
								<div class="col-md-5">
									<select class="custom-select" name="place">
										<option selected disabled value="0">Izvēlieties darba vietu</option>
										<option value="1">Birojs</option>
										<option value="2">Zāģētava</option>
										<option value="3">Šķirošana</option>
									</select>
								</div>
							</div>

							<div class="form-group row" id="position_selects">
								<label class="col-md-2 offset-md-1 col-form-label">Amats</label>
								<div class="col-md-5">
									<?php include "position_select.php"; ?>
								</div>
								<div class="col-md-4">
									<button type="button" name="add" id="add" class="btn btn-success">+</button>
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
	var remove_btn = `<div class="col-md-4">
						<button type="button" class="btn btn-danger remove">X</button>
					</div>`;

	$('#add').click(function(){ 
		$.get('position_select.php', function(result){
			var positionSelect = '<div class="offset-md-3 col-md-5 position-select">';
			positionSelect += result+'</div>'+remove_btn;
			$('#position_selects').append(positionSelect);
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