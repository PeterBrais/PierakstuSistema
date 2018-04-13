<?php

	include "config.php";

	class BeamSize
	{
		private $conn;
		public $id;
		public $size;

		function __construct()
		{
			global $conn;
			$this->conn = $conn;
		}

		public static function ExistsSize($size) //Finds if size already exists in database
		{
			global $conn;

			$sql = $conn->prepare("SELECT size FROM beam_sizes WHERE size = ?");
			$sql->bind_param('d', $size);
			$sql->execute();
			$result = $sql->get_result();

			$resultCheck = mysqli_num_rows($result);

			return $resultCheck >= 1;
		}

		public static function ExistsId($id)	//Finds if id exists in database
		{
			global $conn;

			$sql = $conn->prepare("SELECT id FROM beam_sizes WHERE id = ?");
			$sql->bind_param('i', $id);
			$sql->execute();
			$result = $sql->get_result();

			$resultCheck = mysqli_num_rows($result);

			return $resultCheck >= 1;
		}

		public static function Get($id)	//Finds if beam size exists in database, for calculations
		{
			global $conn;

			$sql = $conn->prepare("SELECT * FROM beam_sizes WHERE id = ?");
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

		public static function IsSizeUsed($id)	//Function finds if beam_size with ID is used in sawmill_production
		{
			global $conn;

			$sql = $conn->prepare("SELECT DISTINCT beam_sizes.* FROM beam_sizes
									JOIN sawmill_productions
									ON sawmill_productions.beam_size_id = beam_sizes.id
									WHERE beam_sizes.id = ?");
			$sql->bind_param('s', $id);
			$sql->execute();
			$result = $sql->get_result();

			$resultCheck = mysqli_num_rows($result);

			return $resultCheck >= 1;
		}

		public static function CurrentBeamSizeExists($size, $id)	//Returns true if beam size from other sizes in DB already exists
		{
			global $conn;

			$sql = $conn->prepare("SELECT size FROM beam_sizes WHERE size = ? AND id <> ?");
			$sql->bind_param('ss', $size, $id); //Binds parameter, transforms to string
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
				$sql->close();
			}
			catch(mysqli_sql_exception $e)
			{
				$_SESSION['error'] = "Radās kļūda ierakstot datus!";
				header("Location: /");
				exit();
			}
		}

		function Update()	//Updates data to chosen beam size
		{
			try
			{
				$sql = $this->conn->prepare("UPDATE beam_sizes SET size = ? WHERE id = ?");
				$sql->bind_param('ss', $this->size, $this->id);
				$sql->execute();
				$sql->close();
			}
			catch(mysqli_sql_exception $e)
			{
				$_SESSION['error'] = "Radās kļūda ierakstot datus!";
				header("Location: /");
				exit();
			}
		}

		function Delete()
		{
			try
			{
				$sql = $this->conn->prepare("DELETE FROM beam_sizes WHERE beam_sizes.id = ?");
				$sql->bind_param('s', $this->id);
				$sql->execute();
				$sql->close();
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