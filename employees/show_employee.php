<?php
	include_once "../header.php";
	include_once "../includes/manager.class.php";

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

	$employees = Manager::Employees();

?>

<!-- Shows all employees -->
<div class="container">
	<div class="row cont-space">
		<div class="col-md-12">
			<div id="message">
				<?php include "../message.php"; ?>
			</div>
			<div class="card">
				<div class="card-body">
					<h4 class="card-title text-center">Zāģētavas darbinieki</h4>
					<table class="table table-bordered table-hover">
						<thead class="thead-default table-active">
							<tr>
								<th>Nr.p.k</th>
								<th>Vārds</th>
								<th>Uzvārds</th>
								<th>Maiņa</th>
								<th>Amats</th>
								<th>Labot</th>
							</tr>
						</thead>
						<tbody>
					<?php
						$i = 1;
						foreach($employees as $employee)
						{
							if($employee['place'] == "Zagetava")
							{
								if($employee['shift'] == "1")
								{
					?>
									<tr>
										<th><?=$i++?></th>
										<td><?=$employee['name']?></td>
										<td><?=$employee['last_name']?></td>
										<td><?=$employee['shift']?></td>
										<td>
										<?php
											$positions = Manager::EmployeePositions($employee['id']);
											foreach($positions as $position)
											{
												echo $position['name'].'<br>';
											}
										?>
										</td>
										<td>
											<a href="edit_employee?id=<?=$employee['id']?>" class="btn btn-info">
												Labot datus
											</a>
										</td>
									</tr>
					<?php
								}
								else if($employee['shift'] == "2")
								{
					?>
									<tr class="table-success">
										<th><?=$i++?></th>
										<td><?=$employee['name']?></td>
										<td><?=$employee['last_name']?></td>
										<td><?=$employee['shift']?></td>
										<td>
										<?php
											$positions = Manager::EmployeePositions($employee['id']);
											foreach($positions as $position)
											{
												echo $position['name'].'<br>';
											}
										?>
										</td>
										<td>
											<a href="edit_employee?id=<?=$employee['id']?>" class="btn btn-info">
												Labot datus
											</a>
										</td>
									</tr>
					<?php
								}
							}
						}
					?>
						</tbody>
					</table>
				</div>

				<div class="card-body">
					<h4 class="card-title text-center">Šķirošanas darbinieki</h4>
					<table class="table table-bordered table-hover">
						<thead class="thead-default table-active">
							<tr>
								<th>Nr.p.k</th>
								<th>Vārds</th>
								<th>Uzvārds</th>
								<th>Amats</th>
								<th>Labot</th>
							</tr>
						</thead>
						<tbody>
					<?php
						$i = 1;
						foreach($employees as $employee)
						{
							if($employee['place'] == "Skirotava")
							{
					?>
								<tr>
									<th><?=$i++?></th>
									<td><?=$employee['name']?></td>
									<td><?=$employee['last_name']?></td>
									<td>
									<?php
										$positions = Manager::EmployeePositions($employee['id']);
										foreach($positions as $position)
										{
											echo $position['name'].'<br>';
										}
									?>
									</td>
									<td>
										<a href="edit_employee?id=<?=$employee['id']?>" class="btn btn-info">
											Labot datus
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
					<h4 class="card-title text-center">Biroja darbinieki</h4>
					<table class="table table-bordered table-hover">
						<thead class="thead-default table-active">
							<tr>
								<th>Nr.p.k</th>
								<th>Vārds</th>
								<th>Uzvārds</th>
								<th>Amats</th>
								<th>Labot</th>
							</tr>
						</thead>
						<tbody>
					<?php
						$i = 1;
						foreach($employees as $employee)
						{
							if($employee['place'] == "Birojs")
							{
					?>
								<tr>
									<th><?=$i++?></th>
									<td><?=$employee['name']?></td>
									<td><?=$employee['last_name']?></td>
									<td>
									<?php
										$positions = Manager::EmployeePositions($employee['id']);
										foreach($positions as $position)
										{
											echo $position['name'].'<br>';
										}
									?>
									</td>
									<td>
										<a href="edit_employee?id=<?=$employee['id']?>" class="btn btn-info">
											Labot datus
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