<?php

//include "includes/user.class.php";
/*
$user = new User();

$result = $user->GetAllUsers();

while($row = $result->fetch_assoc())
{
	echo "id: ".$row['id'];
}
*/

	session_start();
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>Pierakstu sistēma</title>
	<link rel="stylesheet" type="text/css" href="public/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="public/css/style.css">
</head>
<body>
	<!-- Login data input form -->
	<form action="login.php" method="POST">
		<input type="text" name="uid" placeholder="Lietotājvārds">
		<br>
		<input type="password" name="pwd" placeholder="Parole">
		<br>
		<button type="submit">Pieteikties</button>
	</form>

	<?php
		if(isset($_SESSION['id']))
		{
			echo $_SESSION['id'] ;
		}
		else
		{
			echo "You are not logged in!";
		}
	?>

	<br><br>
	<!-- Registration data input form -->
	<form action="signup.php" method="POST">
		<input type="text" name="uid" placeholder="Lietotājvārds">
		<br>
		<input type="password" name="pwd" placeholder="Parole">
		<br>
		<button type="submit">Reģistrēties</button>
	</form>


	<br><br>
	<!-- Atslēgties -->
	<form action="logout.php">
		<button>Atslēgties</button>
	</form>

</body>
</html>