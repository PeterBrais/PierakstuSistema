<?php
	include_once "../header.php";

	if(!isset($_SESSION['id']) && ($_SESSION['role'] == "p"))	//Adding new employee possible if user role is Manager
	{
		header("Location: /");
		exit();
	}
	if(isset($_SESSION['employee']))
	{
		extract($_SESSION['employee']);
	}
?>
	<!-- Add employee -->
	<div class="container">
		<div class="row cont-space">
			<div class="col-md-12">
				<div id="message">
					<?php include "../message.php"; ?>
				</div>
				<div class="card">
					<div class="card-body">
						<h4 class="card-title text-center">Pievienot jaunu darbinieku</h4>
						<form action="new_employee" method="POST">
							<div class="form-group row">
								<label class="col-md-2 offset-md-1 col-form-label">
									Vārds
									<span class="text-danger" title="Šis lauks ir obligāts">
										&#10033;
									</span>
								</label>
								<div class="col-md-5">
									<input class="form-control" type="text" name="name" aria-describedby="nameArea" value="<?php echo isset($_SESSION['employee']) ? $name : ''; ?>">
									<small id="nameArea" class="form-text text-muted">
										* Satur tikai latīņu burtus, garumā no 3 līdz 50 rakstzīmēm *
									</small>
								</div>
								<div class="col-md-4">
									<?php
										if(isset($_SESSION['name']))
										{
									?>
										<div class="alert alert-danger alert-size" role="alert">
											<?=$_SESSION['name']?>
										</div>
									<?php
											unset($_SESSION['name']);
										}
									?>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-md-2 offset-md-1 col-form-label">
									Uzvārds
									<span class="text-danger" title="Šis lauks ir obligāts">
										&#10033;
									</span>
								</label>
								<div class="col-md-5">
									<input class="form-control" type="text" name="last_name" aria-describedby="lastNameArea" value="<?php echo isset($_SESSION['employee']) ? $last_name : ''; ?>">
									<small id="lastNameArea" class="form-text text-muted">
										* Satur tikai latīņu burtus, garumā no 3 līdz 50 rakstzīmēm *
									</small>
								</div>
								<div class="col-md-4">
									<?php
										if(isset($_SESSION['last_name']))
										{
									?>
										<div class="alert alert-danger alert-size" role="alert">
											<?=$_SESSION['last_name']?>
										</div>
									<?php
											unset($_SESSION['last_name']);
										}
									?>
								</div>
							</div>
							<div class="form-group row" id="workplace_input">
								<label class="col-md-2 offset-md-1 col-form-label">
									Darbavieta
									<span class="text-danger" title="Šis lauks ir obligāts">
										&#10033;
									</span>
								</label>
								<div class="col-md-5">
									<select class="custom-select" name="place" id="place_selects">
										<option value="0" <?php echo (isset($_SESSION['employee']) && $place == "0") ? 'selected' : ''; ?> >Izvēlieties darba vietu</option>
										<option value="1" <?php echo (isset($_SESSION['employee']) && $place == "1") ? 'selected' : ''; ?> >Birojs</option>
										<option value="2" <?php echo (isset($_SESSION['employee']) && $place == "2") ? 'selected' : ''; ?> >Zāģētava</option>
										<option value="3" <?php echo (isset($_SESSION['employee']) && $place == "3") ? 'selected' : ''; ?> >Šķirotava</option>
									</select>
								</div>
								<div class="col-md-4">
									<?php
										if(isset($_SESSION['place']))
										{
									?>
										<div class="alert alert-danger alert-size" role="alert">
											<?=$_SESSION['place']?>
										</div>
									<?php
											unset($_SESSION['place']);
										}
									?>
								</div>
							</div>
							<div class="form-group row" id="position_selects">
								<label class="col-md-2 offset-md-1 col-form-label">
									Amats
									<span class="text-danger" title="Šis lauks ir obligāts">
										&#10033;
									</span>
								</label>
								<div class="col-md-5">
									<?php include "position_select.php"; ?>
								</div>
								<div class="col-md-1">
									<button type="button" name="add" id="add" class="btn btn-success">+</button>
								</div>
								<div class="col-md-3">
									<?php
										if(isset($_SESSION['position']))
										{
									?>
										<div class="alert alert-danger alert-size" role="alert">
											<?=$_SESSION['position']?>
										</div>
									<?php
											unset($_SESSION['position']);
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
			$('#position_selects').append(positionSelect);
		});
	});

	$(document).on('click', '.remove', function(){  
		$(this).parent().prev('.position-select').remove();
		$(this).parent().remove();
	});

	//Shows shift and rates input for sawmill workers
	var shift = '<label class="col-md-2 offset-md-1 col-form-label mt-3">Maiņa<span class="text-danger" title="Šis lauks ir obligāts"> &#10033;</span></label><div class="col-md-5 mt-3"><select class="custom-select" name="shift"><option selected value="0">Izvēlieties maiņu</option><option value="1">1</option><option value="2">2</option></select></div><div class="col-md-4"></div>';
	var rates = '<label class="col-md-2 offset-md-1 col-form-label mt-3">Likmes<span class="text-danger" title="Šis lauks ir obligāts"> &#10033;</span></label><div class="col-md-5 mt-3"><div class="row"><div class="col-md-6"><input class="form-control" type="number" min="0" step="0.01" name="capacity_rate" aria-describedby="ratesArea" placeholder="m&sup3 likme"></div><div class="col-md-6"><input class="form-control" type="number" min="0" step="0.01" name="hour_rate" aria-describedby="ratesArea" placeholder="Stundas likme"></div></div><small id="ratesArea" class="form-text text-muted">* Satur tikai ciparus, likmi par m<sup>3</sup> un stundu *</small></div><div class="col-md-4 mt-3"></div>';

	$('#place_selects').on('change', function(){
		var place_value = $(this).val();
		if(place_value == "2")
		{
			$('#workplace_input').append(shift);
			$('#workplace_input').append(rates);
		}
		else //Removes label and div from variable shift
		{
			$(this).parent().next().next().remove();
			$(this).parent().next().next().remove();
			$(this).parent().next().next().remove();
			$(this).parent().next().next().remove();
			$(this).parent().next().next().remove();
			$(this).parent().next().next().remove();
		}
	});
});  
</script>

<?php
	unset($_SESSION['employee']);
	include_once "../footer.php";
?>