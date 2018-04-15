<?php
	include_once "../header.php";
	include_once "../includes/administrator.class.php";

	if(!isset($_SESSION['id']) && !isset($_SESSION['role']))	//Check if user is logged in
	{
		header("Location: 404");
		exit();
	}

	if(($_SESSION['role'] != "a") || ($_SESSION['active'] != 1))	//Check if user is Administrator and not blocked
	{
		header("Location: 404");
		exit();
	}

	//Returns all users with data
	$users = Administrator::AllUsers();
?>

<div class="container">
	<div class="row cont-space">
		<div class="col-md-12">
			<div id="message">
				<?php include "../message.php"; ?>
			</div>
			<div class="card">
				<div class="card-body">
					<h4 class="card-title text-center">Reģistrētie Administratori</h4>
					<table class="table table-bordered table-hover">
						<thead class="thead-default table-active">
							<tr>
								<th>Nr.p.k</th>
								<th>Lietotājvārds</th>
								<th>Reģistrējies</th>
								<th>Mainīt paroli</th>
								<th>Labot</th>
								<th>Bloķēt</th>
							</tr>
						</thead>
						<tbody>
					<?php
						$i = 1;
						foreach($users as $user)
						{
							if($user['role'] == "a" && $user['active'] == 1)
							{
					?>
								<tr>
									<th><?=$i++?></th>
									<td><?=$user['username']?></td>
									<td><?=$user['created']?></td>
									<td>
									<?php
										if(($user['id'] == $_SESSION['id']) || ($_SESSION['id'] == 1))
										{
									?>
											<a href="reset?id=<?=$user['id']?>" class="btn btn-warning">
												Mainīt paroli
											</a>
									<?php
										}
									?>
									</td>
									<td>
										<a href="edit_user?id=<?=$user['id']?>" class="btn btn-info">
											Labot datus
										</a>
									</td>
									<td>
									<?php
										if(($user['id']) != ($_SESSION['id']))
										{
									?>
											<a href="block_user?id=<?=$user['id']?>" class="btn btn-danger">
												Bloķēt
											</a>
									<?php
										}
									?>
									</td>
								</tr>
					<?php
							}
						}
					?>
						</tbody>
					</table>
				</div>

				<div class="card-body">
					<h4 class="card-title text-center">Reģistrētie Pārvaldnieki</h4>
					<table class="table table-bordered table-hover">
						<thead class="thead-default table-active">
							<tr>
								<th>Nr.p.k</th>
								<th>Lietotājvārds</th>
								<th>Reģistrējies</th>
								<th>Mainīt paroli</th>
								<th>Labot</th>
								<th>Bloķēt</th>
							</tr>
						</thead>
						<tbody>
					<?php
						$i = 1;
						foreach($users as $user)
						{
							if($user['role'] == "p" && $user['active'] == 1)
							{
					?>
								<tr>
									<th><?=$i++?></th>
									<td><?=$user['username']?></td>
									<td><?=$user['created']?></td>
									<td>
										<a href="reset?id=<?=$user['id']?>" class="btn btn-warning">
											Mainīt paroli
										</a>
									</td>
									<td>
										<a href="edit_user?id=<?=$user['id']?>" class="btn btn-info">
											Labot datus
										</a>
									</td>
									<td>
										<a href="block_user?id=<?=$user['id']?>" class="btn btn-danger">
											Bloķēt
										</a>
									</td>
								</tr>
					<?php
							}
						}
					?>
						</tbody>
					</table>
				</div>

				<div class="card-body">
					<h4 class="card-title text-center">Reģistrētie Darbinieki</h4>
					<table class="table table-bordered table-hover">
						<thead class="thead-default table-active">
							<tr>
								<th>Nr.p.k</th>
								<th>Lietotājvārds</th>
								<th>Reģistrējies</th>
								<th>Mainīt paroli</th>
								<th>Labot</th>
								<th>Bloķēt</th>
							</tr>
						</thead>
						<tbody>
					<?php
						$i = 1;
						foreach($users as $user)
						{
							if($user['role'] == "l" && $user['active'] == 1)
							{
					?>
								<tr>
									<th><?=$i++?></th>
									<td><?=$user['username']?></td>
									<td><?=$user['created']?></td>
									<td>
										<a href="reset?id=<?=$user['id']?>" class="btn btn-warning">
											Mainīt paroli
										</a>
									</td>
									<td>
										<a href="edit_user?id=<?=$user['id']?>" class="btn btn-info">
											Labot datus
										</a>
									</td>
									<td>
										<a href="block_user?id=<?=$user['id']?>" class="btn btn-danger">
											Bloķēt
										</a>
									</td>
								</tr>
					<?php
							}
						}
					?>
						</tbody>
					</table>
				</div>

				<div class="card-body">
					<h4 class="card-title text-center">Bloķētie Lietotāji</h4>
					<table class="table table-bordered table-hover">
						<thead class="thead-default table-active">
							<tr>
								<th>Nr.p.k</th>
								<th>Lietotājvārds</th>
								<th>Loma</th>
								<th>Reģistrējies</th>
								<th>Mainīt paroli</th>
								<th>Labot</th>
								<th>Atbloķēt</th>
							</tr>
						</thead>
						<tbody>
					<?php
						$i = 1;
						foreach($users as $user)
						{
							if($user['active'] == 0)
							{
					?>
								<tr>
									<th><?=$i++?></th>
									<td><?=$user['username']?></td>
									<td>
									<?php 
										if($user['role'] == "a"){
											echo "Administrators";
										}
										else if($user['role'] == "p")
										{
											echo "Pārvaldnieks";
										}
										else if($user['role'] == "l")
										{
											echo "Darbinieks";
										}
									?>
									</td>
									<td><?=$user['created']?></td>
									<td>
										<a href="reset?id=<?=$user['id']?>" class="btn btn-warning">
											Mainīt paroli
										</a>
									</td>
									<td>
										<a href="edit_user?id=<?=$user['id']?>" class="btn btn-info">
											Labot datus
										</a>
									</td>
									<td>
										<a href="unblock_user?id=<?=$user['id']?>" class="btn btn-danger">
											Atbloķēt
										</a>
									</td>
								</tr>
					<?php
							}
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