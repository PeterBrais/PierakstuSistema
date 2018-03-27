<?php

	include "config.php";

	class Administrator
	{
		private $conn;

		function __construct()
		{
			global $conn;
			$this->conn = $conn;
		}

		public static function AllUsers()	//Returns all users
		{
			global $conn;

			$sql = $conn->query("SELECT * FROM users");

			return mysqli_fetch_all($sql, MYSQLI_ASSOC);
		}

		public static function ExistsUserWithID($id) //Checks if user with such ID exists
		{
			global $conn;

			$sql = $conn->prepare("SELECT id FROM users WHERE id = ?");
			$sql->bind_param('s', $id);
			$sql->execute();
			$result = $sql->get_result();

			$resultCheck = mysqli_num_rows($result);

			return $resultCheck >= 1;
		}

		public static function GetUsersData($id)	//Returns all user data with ID
		{
			global $conn;

			$sql = $conn->prepare("SELECT users.* FROM users 
									WHERE users.id = ?");
			$sql->bind_param('s', $id);
			$sql->execute();
			$result = $sql->get_result();

			return mysqli_fetch_assoc($result);
		}

		public static function Confirm($password, $id)	//Comapare entered password with database password
		{
			global $conn;

			$sql = $conn->prepare("SELECT * FROM users WHERE id = ?");
			$sql->bind_param('s', $id);
			$sql->execute();
			$result = $sql->get_result();

			$resultCheck = mysqli_num_rows($result);

			$row = mysqli_fetch_assoc($result);

			return password_verify($password, $row['password']);
		}

	}

?>