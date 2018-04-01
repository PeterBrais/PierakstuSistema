<?php
	include_once "../header.php";
	include_once "../includes/manager.class.php";
	include_once "../includes/beam_size.class.php";

	if(!isset($_SESSION['id']) && !isset($_SESSION['role']))	//Check if user is logged in
	{
		header("Location: /");
		exit();
	}
	if(($_SESSION['role'] != "p") && ($_SESSION['role'] != "a") && ($_SESSION['active'] != 1))	//Check if user have permission to view data
	{
		header("Location: /");
		exit();
	}

	//Returns all beam sizes
	$sizes = Manager::BeamSizes();
?>

<!-- Shows all beam sizes -->
<div class="container">
	<div class="row cont-space">
		<div class="col-md-12">
			<div id="message">
				<?php include "../message.php"; ?>
			</div>
			<div class="card">
				<div class="card-body">
					<h4 class="card-title text-center">Visi kubatūras izmēri</h4>
					<table class="table table-bordered table-hover">
						<thead class="thead-default table-active">
							<tr>
								<th>Nr.p.k</th>
								<th>Izmērs</th>
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
						foreach($sizes as $size)
						{
					?>
							<tr>
								<th><?=$i++?></th>
								<td><?=$size['size']?></td>
								<?php
									if(($_SESSION['role'] == "a" || $_SESSION['role'] == "p") && ($_SESSION['active'] == 1))
									{
								?>
										<td>
											<a href="edit_beam_size?id=<?=$size['id']?>" class="btn btn-info">
												Labot
											</a>
										</td>
										<td>
										<?php
											if(!BeamSize::IsSizeUsed($size['id']))
											{
										?>
												<a href="delete_beam_size?id=<?=$size['id']?>" class="btn btn-danger">
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