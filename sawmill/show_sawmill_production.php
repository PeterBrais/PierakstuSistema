<?php

	include_once "../header.php";
	include_once "../includes/manager.class.php";

?>

<div class="container">
	<div class="row cont-space">
		<div class="col-md-12">
			<div id="message">
				<?php include "../message.php"; ?>
			</div>
			<div class="card">
				<div class="card-body">
					<h4 class="card-title text-center">Produkcijas periods</h4>
					<div class="col-md-6">
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