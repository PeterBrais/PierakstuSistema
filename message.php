<?php
	if(isset($_SESSION['error']))
	{
?>
		<div class="alert alert-danger" role="alert">
			<?=$_SESSION['error']?>
		</div>
<?php
		unset($_SESSION['error']);
	}
	elseif(isset($_SESSION['success']))
	{
?>
		<div class="alert alert-success" role="alert">
			<?=$_SESSION['success']?>
		</div>
<?php
		unset($_SESSION['success']);
	}
?>