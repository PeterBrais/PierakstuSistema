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

		public static function ExistsId($id)	//Finds if id exists in database
		{
			global $conn;

			$sql = $conn->prepare("SELECT id FROM beam_sizes WHERE id=?");
			$sql->bind_param('i', $id);
			$sql->execute();
			$result = $sql->get_result();

			$resultCheck = mysqli_num_rows($result);

			return $resultCheck >= 1;
		}

		public static function Get($id)	//Finds if id exists in database
		{
			global $conn;

			$sql = $conn->prepare("SELECT * FROM beam_sizes WHERE id=?");
			$sql->bind_param('i', $id);
			$sql->execute();
			$result = $sql->get_result();
			$row = mysqli_fetch_assoc($result);

			if(mysqli_num_rows($result))
			{
				$beamSize = new BeamSize();
				$beamSize->size = $row['size'];
				return $beamSize;
			}

			return null;
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