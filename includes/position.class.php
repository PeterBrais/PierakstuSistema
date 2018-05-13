<?php
	
	include "config.php";

	class Position
	{
		private $conn;
		public $id;
		public $name;

		function __construct()
		{
			global $conn;
			$this->conn = $conn;
		}

		public static function ExistsName($name)	//Finds if position already exists in database
		{
			global $conn;

			$sql = $conn->prepare("SELECT name FROM positions WHERE name = ?");
			$sql->bind_param('s', $name);
			$sql->execute();
			$result = $sql->get_result();

			$resultCheck = mysqli_num_rows($result);

			return $resultCheck >= 1;
		}

		public static function Exists($id)			//Finds if ID exists in database
		{
			global $conn;

			$sql = $conn->prepare("SELECT id FROM positions WHERE id = ?");
			$sql->bind_param('i', $id);
			$sql->execute();
			$result = $sql->get_result();

			$resultCheck = mysqli_num_rows($result);

			return $resultCheck >= 1;
		}

		public static function CurrentPositionExists($name, $id)	//Returns true if position from other user in DB already exists
		{
			global $conn;

			$sql = $conn->prepare("SELECT name FROM positions WHERE name = ? AND id <> ?");
			$sql->bind_param('ss', $name, $id); //Binds parameter, transforms to string
			$sql->execute();
			$result = $sql->get_result();

			$resultCheck = mysqli_num_rows($result);

			return $resultCheck >= 1;
		}

		public static function IsPositionUsed($id)	//Check if position is used, so it can be deleted
		{
			global $conn;

			$sql = $conn->prepare("SELECT positions.* FROM positions
									JOIN employees_positions
									ON employees_positions.position_id = positions.id
									WHERE position_id = ?");
			$sql->bind_param('s', $id);	//Binds parameter, transforms to string
			$sql->execute();
			$result = $sql->get_result();

			$resultCheck = mysqli_num_rows($result);

			return $resultCheck >= 1;
		}

		public static function IsPositionOperatorOrAssistant($id)	//Check if users position is operator or assistant
		{
			global $conn;

			$sql = $conn->prepare("SELECT positions.id FROM positions
									JOIN employees_positions
									ON employees_positions.position_id = positions.id
									JOIN employees
									ON employees_positions.employee_id = employees.id
									WHERE employees.id = ? AND
									(positions.name LIKE '%Pakošanas operators%' OR positions.name LIKE '%Zāģēšanas iecirkņa palīgstrādnieks%')");
			$sql->bind_param('s', $id);
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
				$sql->bind_param('s', $this->name);
				$sql->execute();

				$this->id = $this->conn->insert_id;
				$sql->close();
			}
			catch(mysqli_sql_exception $e)
			{
				$_SESSION['error'] = "Radās kļūda ierakstot datus!";
				header("Location: /");
				exit();
			}
		}

		function Update()	//Updates positions data
		{
			try
			{
				$sql = $this->conn->prepare("UPDATE positions SET name = ? WHERE positions.id = ?");
				$sql->bind_param('ss', $this->name, $this->id);
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

		function Delete()	//Deletes position from database
		{
			try
			{
				$sql = $this->conn->prepare("DELETE FROM positions WHERE positions.id = ?");
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