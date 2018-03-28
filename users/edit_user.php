<?php
	include_once "../header.php";
	include_once "../includes/administrator.class.php";

	if(!isset($_SESSION['id']) && !isset($_SESSION['role']))	//Editing user data possible if user is logged in
	{
		header("Location: /");
		exit();
	}

	if(($_SESSION['role'] != "a") || ($_SESSION['active'] != 1))	//Check if user is Administrator and not blocked
	{
		header("Location: /");
		exit();
	}

	if(!isset($_GET['id']))		//Check if ID is set
	{
		header("Location: show_users");
		exit();
	}

	//Check if users ID exists in database
	$user_id = $_GET['id'];
	if(!Administrator::ExistsUserWithID($user_id))
	{
		header("Location: show_users");
		exit();
	}

	//Extract Session data
	if(isset($_SESSION['edit_user']))
	{
		extract($_SESSION['edit_user']);
	}

	//Returns all users data
	$user = Administrator::GetUsersData($user_id);
?>

	<!-- Update User data -->
	<div class="container">
		<div class="row cont-space">
			<div class="col-md-12">
				<div id="message">
					<?php include "../message.php"; ?>
				</div>
				<div class="card">
					<div class="card-body">
						<h4 class="card-title text-center">
							Labot lietotāja: <u>'<?=$user['username']?>'</u> datus. Loma: 
							<u>
							<?php 
								if($user['role'] == "a"){
									echo "'Administrators'";
								}
								else if($user['role'] == "p")
								{
									echo "'Pārvaldnieks'";
								}
								else if($user['role'] == "l")
								{
									echo "'Darbinieks'";
								}
							?>
							</u>
						</h4>

						<form id="edit_user_form" action="update_user" method="POST">

							<input type="hidden" name="user_id" value="<?=$user['id']?>">

							<div class="form-group row">
								<label class="col-md-2 offset-md-1 col-form-label">
									Lietotājvārds
									<span class="text-danger" title="Šis lauks ir obligāts">
										&#10033;
									</span>
								</label>
								<div class="col-md-5">
									<input class="form-control" type="text" name="usr" aria-describedby="usernameArea" value="<?php echo isset($_SESSION['edit_user']) ? $usr : $user['username']; ?>">
									<small id="usernameArea" class="form-text text-muted">
										
									</small>
								</div>
								<div class="col-md-4">
									<?php
										if(isset($_SESSION['usr_name']))
										{
									?>
										<div class="alert alert-danger alert-size" role="alert">
											<?=$_SESSION['usr_name']?>
										</div>
									<?php
											unset($_SESSION['usr_name']);
										}
									?>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-md-2 offset-md-1 col-form-label">
									Loma
									<span class="text-danger" title="Šis lauks ir obligāts">
										&#10033;
									</span>
								</label>
								<div class="col-md-5">
									<select class="custom-select" name="role">
										<option value="1" <?php echo ((isset($_SESSION['edit_user']) && $role == "1")|| ($user['role'] == "l")) ? 'selected' : ''; ?> >Darbinieks</option>
										<option value="2" <?php echo ((isset($_SESSION['edit_user']) && $role == "2")|| ($user['role'] == "p")) ? 'selected' : ''; ?> >Pārvaldnieks</option>
										<option value="3" <?php echo ((isset($_SESSION['edit_user']) && $role == "3")|| ($user['role'] == "a")) ? 'selected' : ''; ?> >Administrators</option>
									</select>
								</div>
								<div class="col-md-4">
									<?php
										if(isset($_SESSION['usr_role']))
										{
									?>
										<div class="alert alert-danger alert-size" role="alert">
											<?=$_SESSION['usr_role']?>
										</div>
									<?php
											unset($_SESSION['usr_role']);
										}
									?>
								</div>
							</div>
							<div class="form-group row">
								<div class="col-md-3 offset-md-3">
									<button class="btn btn-info" type="submit" name="submit">Labot</button>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>

<script src="../public/js/edit_user_form.js"></script>

<?php
	unset($_SESSION['edit_user']);
	include_once "../footer.php";
?>