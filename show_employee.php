<?php

	include "header.php";
	include "includes/manager.class.php";
	include "includes/employee.class.php";

	$employees = Manager::Employees();

?>

<div class="container">
	<div class="row cont-space">
		<div class="col-md-12">
			<div id="message">
				<?php include "message.php"; ?>
			</div>
			<div class="card">
				<div class="card-body">
					<?php

						foreach($employees as $employee)
						{

							echo $employee['name'];

						}

					?>
				</div>
			</div>
		</div>
	</div>
</div>

<?php

	include "footer.php";

?>