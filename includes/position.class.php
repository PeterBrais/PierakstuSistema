<?php
	
	include "config.php";

	class Position
	{
		private $conn;
		public $position;

		function __construct()
		{
			global $conn;
			$this->conn = $conn;
		}

		public static function ExistsName($position)	//Finds if position already exists in database
		{
			global $conn;

			$sql = $conn->prepare("SELECT position FROM positions WHERE position=?");
			$sql->bind_param('s', $position);
			$sql->execute();
			$result = $sql->get_result();

			$resultCheck = mysqli_num_rows($result);

			return $resultCheck >= 1;
		}

		public static function Exists($id)			//Finds if ID exists
		{
			global $conn;

			$sql = $conn->prepare("SELECT id FROM positions WHERE id=?");
			$sql->bind_param('i', $id);
			$sql->execute();
			$result = $sql->get_result();

			$resultCheck = mysqli_num_rows($result);

			return $resultCheck >= 1;
		}

		function Save()	//Inserts data into database
		{
			try
			{
				$sql = $this->conn->prepare("INSERT INTO positions VALUES (DEFAULT, ?)");
				$sql->bind_param('s', $this->position);
				$sql->execute();
			}
			catch(mysqli_sql_exception $e)
			{
				$_SESSION['error'] = "Radās kļūda ierakstot datus!";
				header("Location: /");
				exit();
			}
		}
	}

?>