<?php
	include_once "../header.php";
	include_once "../includes/administrator.class.php";

	if(!isset($_SESSION['id']) && !isset($_SESSION['role']))	//Editing user data possible if user is logged in
	{
		header("Location: 404");
		exit();
	}

	if(($_SESSION['role'] != "a") || ($_SESSION['active'] != 1))	//Check if user is Administrator and not blocked
	{
		header("Location: 404");
		exit();
	}

	//Check if ID is set
	if(!isset($_GET['id']))		
	{
		header("Location: 404");
		exit();
	}

	//Admin cannot block himself
	if(($_GET['id']) == ($_SESSION['id']))
	{
		header("Location: 404");
		exit();
	}

	//Check if users ID exists in database
	$user_id = $_GET['id'];
	if(!Administrator::ExistsUserWithID($user_id))
	{
		header("Location: 404");
		exit();
	}

	//Returns all users data
	$user = Administrator::GetUsersData($user_id);
?>

	<!-- Block user -->
	<div class="container">
		<div class="row cont-space">
			<div class="col-md-12">
				<div id="message">
					<?php include "../message.php"; ?>
				</div>
				<div class="card">
					<div class="card-body">
						<h4 class="card-title text-center">
							Bloķēt lietotāju: <u>'<?=$user['username']?>'</u>
						</h4>

						<form id="block_user_form" action="block" method="POST">

							<input type="hidden" name="user_id" value="<?=$user['id']?>">

							<div class="form-group row">
								<label class="col-md-2 offset-md-1 col-form-label">
									Jūsu parole
									<span class="text-danger" title="Šis lauks ir obligāts">
										&#10033;
									</span>
								</label>
								<div class="col-md-5">
									<input class="form-control" type="password" name="pwd" aria-describedby="pwdArea">
									<small id="pwdArea" class="form-text text-muted">
										* Apstiprināt konta bloķēšanu ievadot paroli *
									</small>
								</div>
								<div class="col-md-4">
								</div>
							</div>
							<div class="form-group row">
								<div class="offset-md-3 col-md-5">
									<div class="form-check">
										<label class="form-check-label">
											<input class="form-check-input" name="agree" type="checkbox">
											Apstiprināt un bloķēt!
										</label>
									</div>
								</div>
								<div class="col-md-4">
								</div>
							</div>
							<div class="form-group row">
								<div class="col-md-3 offset-md-3">
									<button class="btn btn-info" type="submit" name="submit">Bloķēt</button>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>

<script src="../public/js/block_user_form.js"></script>

<?php
	include_once "../footer.php";
?>