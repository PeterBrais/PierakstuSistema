<?php

	include "config.php";

	class BeamSize
	{
		private $conn;
		public $size;

		function __construct()
		{
			global $conn;
			$this->conn = $conn;
		}

		public static function ExistsSize($size) //Finds if size already exists in database
		{
			global $conn;

			$sql = $conn->prepare("SELECT size FROM beam_sizes WHERE size=?");
			$sql->bind_param('d', $size);
			$sql->execute();
			$result = $sql->get_result();

			$resultCheck = mysqli_num_rows($result);

			return $resultCheck >= 1;
		}

		function Save()	//Inserts data into database
		{
			try
			{
				$sql = $this->conn->prepare("INSERT INTO beam_sizes VALUES (DEFAULT, ?)");
				$sql->bind_param('d', $this->size);
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