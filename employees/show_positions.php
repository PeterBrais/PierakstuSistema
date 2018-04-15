<?php
	include_once "../header.php";
	include_once "../includes/manager.class.php";
	include_once "../includes/position.class.php";

	if(!isset($_SESSION['id']) && !isset($_SESSION['role']))	//Check if user is logged in
	{
		header("Location: 404");
		exit();
	}

	if((($_SESSION['role'] != "a") && ($_SESSION['role'] != "p")) || ($_SESSION['active'] != 1))	//Check if user have permission to view data
	{
		header("Location: 404");
		exit();
	}

	//Returns all positions
	$positions = Manager::Positions();
?>

<!-- Shows all positions -->
<div class="container">
	<div class="row cont-space">
		<div class="col-md-12">
			<div id="message">
				<?php include "../message.php"; ?>
			</div>
			<div class="card">
				<div class="card-body">
					<h4 class="card-title text-center">Visi amati</h4>
					<table class="table table-bordered table-hover">
						<thead class="thead-default table-active">
							<tr>
								<th>Nr.p.k</th>
								<th>Amats</th>
								<?php
									if(($_SESSION['role'] == "a" || $_SESSION['role'] == "p") && ($_SESSION['active'] == 1))
									{
								?>
										<th>Labot</th>
										<th>Dzēst</th>
								<?php
									}
								?>
							</tr>
						</thead>
						<tbody>
					<?php
						$i = 1;
						foreach($positions as $position)
						{
					?>
							<tr>
								<th><?=$i++?></th>
								<td><?=$position['name']?></td>
								<?php
									if(($_SESSION['role'] == "a" || $_SESSION['role'] == "p") && ($_SESSION['active'] == 1))
									{
								?>
										<td>
											<a href="edit_position?id=<?=$position['id']?>" class="btn btn-info">
												Labot
											</a>
										</td>
										<td>
										<?php
											if(!Position::IsPositionUsed($position['id']))
											{
										?>
												<a href="delete_position?id=<?=$position['id']?>" class="btn btn-danger">
													Dzēst
												</a>
										<?php
											}
										?>
										</td>
								<?php
									}
								?>
							</tr>
					<?php
						}
					?>
						</tbody>
					</table>
				</div>

			</div>
		</div>
	</div>
</div>

<?php
	include_once "../footer.php";
?>