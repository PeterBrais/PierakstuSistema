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
									<input class="form-control" type="text" name="name">
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
									<input class="form-control" type="text" name="last">
								</div>
							</div>							
							<div class="form-group row">
								<label class="col-md-2 offset-md-1 col-form-label">Strādā no</label>
								<div class="col-md-5">
									<input class="form-control" type="time" name="timefrom">
								</div>
							</div>
							<div class="form-group row">
								<label class="col-md-2 offset-md-1 col-form-label">Strādā līdz</label>
								<div class="col-md-5">
									<input class="form-control" type="time" name="timeto">
								</div>
							</div>
							<div class="form-group row">
								<label class="col-md-2 offset-md-1 col-form-label">Darbavieta</label>
								<div class="col-md-5">
									<select class="custom-select" name="place">
										<option>Izvēlieties darba vietu</option>
										<option value="1">Birojs</option>
										<option value="2">Zāģētava</option>
										<option value="3">Šķirošana</option>
									</select>
								</div>
							</div>
							<div id="dynamic_field">
								<div class="form-group row">
									<label class="col-md-2 offset-md-1 col-form-label">Amats</label>
									<div class="col-md-5">
										<input type="text" name="positions[]" class="form-control" placeholder="Ievadiet darbinieka amatu">
									</div>
									<div class="col-md-3">
										<button type="button" name="add" id="add" class="btn btn-info">Vēl amats!</button>
									</div>
								</div>
							</div>

							<div class="form-group row">
								<div class="col-md-3 offset-md-3">
									<button class="btn btn-success" type="submit" name="submit">Pievienot</button>
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
 	var positionDiv = `<div class="form-group row positions">
 							<div class="offset-md-3 col-md-5">
 								<input type="text" name="positions[]" class="form-control" placeholder="Ievadiet darbinieka amatu">
 								</div>
 							<div class="col-md-3">
 								<button type="button" name="remove" class="btn btn-danger btn_remove">X</button>
 							</div>
 						</div>`;

	$('#add').click(function(){ 
		$('#dynamic_field').append(positionDiv);
	});  

	$(document).on('click', '.btn_remove', function(){  
		$(this).closest('.positions').remove();
	}); 

});  
</script>

<?php
	include_once "footer.php";
?>