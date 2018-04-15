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

	//Check if users ID exists in database
	$user_id = $_GET['id'];
	if(!Administrator::ExistsUserWithID($user_id))
	{
		header("Location: 404");
		exit();
	}

	//Returns all users data
	$user = Administrator::GetUsersData($user_id);

	//Admin cannot change other admins password except first admin
	if((($_GET['id'] != $_SESSION['id']) && ($user['role'] == "a") && ($user['active'] == 1)) && ($_SESSION['id'] != 1))
	{
		header("Location: 404");
		exit();
	}
?>

	<!-- Change password -->
	<div class="container">
		<div class="row cont-space">
			<div class="col-md-12">
				<div id="message">
					<?php include "../message.php"; ?>
				</div>
				<div class="card">
					<div class="card-body">
						<h4 class="card-title text-center">
							Mainīt paroli lietotājam: <u>'<?=$user['username']?>'</u>
						</h4>

						<form id="reset_user_form" action="change" method="POST">

							<input type="hidden" name="user_id" value="<?=$user['id']?>">

							<div class="form-group row">
								<label class="col-md-2 offset-md-1 col-form-label">
									Jūsu parole
									<span class="text-danger" title="Šis lauks ir obligāts">
										&#10033;
									</span>
								</label>
								<div class="col-md-5">
									<input class="form-control" type="password" name="current_pwd" aria-describedby="currentPwdArea">
									<small id="pwdArea" class="form-text text-muted">
										* Apstiprināt ievadot jūsu pašreizējo paroli *
									</small>
								</div>
								<div class="col-md-4">
									<?php
										if(isset($_SESSION['current_pwd']))
										{
									?>
										<div class="alert alert-danger alert-size" role="alert">
											<?=$_SESSION['current_pwd']?>
										</div>
									<?php
											unset($_SESSION['current_pwd']);
										}
									?>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-md-2 offset-md-1 col-form-label">
									Jaunā parole
									<span class="text-danger" title="Šis lauks ir obligāts">
										&#10033;
									</span>
								</label>
								<div class="col-md-5">
									<input class="form-control" type="password" name="pwd" placeholder="********" aria-describedby="pwdArea" id="pwd_area">
									<small id="pwdArea" class="form-text text-muted">
										* Satur vismaz no vienu mazo un lielo latīņu burtu, ciparu un speciālo simbolu *
									</small>
								</div>
								<div class="col-md-4">
									<?php
										if(isset($_SESSION['pwd']))
										{
									?>
										<div class="alert alert-danger alert-size" role="alert">
											<?=$_SESSION['pwd']?>
										</div>
									<?php
											unset($_SESSION['pwd']);
										}
									?>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-md-2 offset-md-1 col-form-label">
									Jaunā parole atkārtoti
									<span class="text-danger" title="Šis lauks ir obligāts">
										&#10033;
									</span>
								</label>
								<div class="col-md-5">
									<input class="form-control" type="password" name="pwd2" placeholder="********" aria-describedby="pwdArea2">
									<small id="pwdArea2" class="form-text text-muted">
										* Ievadītā jaunā parole atkārtoti *
									</small>
								</div>
								<div class="col-md-4">
									<?php
										if(isset($_SESSION['pwd2']))
										{
									?>
										<div class="alert alert-danger alert-size" role="alert">
											<?=$_SESSION['pwd2']?>
										</div>
									<?php
											unset($_SESSION['pwd2']);
										}
									?>
								</div>
							</div>
							<div class="form-group row">
								<div class="col-md-3 offset-md-3">
									<button class="btn btn-info" type="submit" name="submit">Mainīt</button>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>

<script src="../public/js/reset_user_form.js"></script>

<?php
	include_once "../footer.php";
?>