<?php

	include_once "../header.php";
	include_once "../includes/manager.class.php";

	if(!isset($_SESSION['id']) && !isset($_SESSION['role']))	//Check if user is logged in
	{
		header("Location: 404");
		exit();
	}

	if((($_SESSION['role'] != "a") && ($_SESSION['role'] != "p") && ($_SESSION['role'] != "l")) || ($_SESSION['active'] != 1))	//Check if user have permission
	{
		header("Location: 404");
		exit();
	}

?>

<div class="container-fluid container-scroll">
	<div class="row cont-space">
		<div class="col-md-12">
			<div id="message">
				<?php include "../message.php"; ?>
			</div>
			<div class="card">
				<div class="card-body">
					<h4 class="card-title text-center">Produkcijas periods</h4>
					<div class="offset-md-4 col-md-4 offset-md-4">
						<?php include_once "period_select.php"; ?>
					</div>
				</div>
				<div id="productions_table">
					<?php include_once "productions_period_table.php"; ?>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
	$(document).ready(function(){
		//
		$('#period_select').change(function(){
			var date_string = $(this).val();

			$.ajax({
				url:"productions_period_table.php",
				method:"POST",
				data:{date_string:date_string},
				success:function(data){
					$('#productions_table').html(data);
				}
			});
		});
	});
</script>

<?php
	include_once "../footer.php";
?>