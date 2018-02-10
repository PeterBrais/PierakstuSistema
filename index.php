<?php

include "includes/user.class.php";

$user = new User();

$resault = $user->GetAllUsers();

while($row = $resault->fetch_assoc())
{
	echo "id: ".$row['idfsdgsdgfsd'];
}

?>