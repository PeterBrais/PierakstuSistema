<?php

	include "config.php";

	class Employee
	{
		private $conn;
		public $id;
		public $name;
		public $last_name;
		public $person_id;
		public $place;
		public $shift;
		public $capacity_rate;
		public $hour_rate;
		public $working_from;
		public $working_to;

		function __construct()
		{
			global $conn;
			$this->conn = $conn;
			$this->working_from = date("Y-m-d");
			$this->working_to = NULL;
		}

		function Save()	//Inserts new employee data into Database
		{
			try
			{
				$sql = $this->conn->prepare("INSERT INTO employees VALUES (DEFAULT, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
				$sql->bind_param('sssssddss', $this->name, $this->last_name, $this->person_id, $this->place, $this->shift, $this->capacity_rate, $this->hour_rate, $this->working_from, $this->working_to);
				$sql->execute();

				$this->id = $this->conn->insert_id;
			}
			catch(mysqli_sql_exception $e)
			{	
				$_SESSION['error'] = "Radās kļūda ierakstot datus!";
				header("Location: /");
				exit();
			}
		}

		function Update()	//Updates existing employees data
		{
			try
			{
				$sql = $this->conn->prepare("UPDATE employees SET name = ?, last_name = ?, person_id = ?, shift = ?, capacity_rate = ?, hour_rate = ?, working_from = ?, working_to = ? WHERE employees.id = ?");
				$sql->bind_param('ssssddsss', $this->name, $this->last_name, $this->person_id, $this->shift, $this->capacity_rate, $this->hour_rate, $this->working_from, $this->working_to, $this->id);
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

		public static function ExistsEmployeeWithID($id) //Checks if user with such ID exists
		{
			global $conn;

			$sql = $conn->prepare("SELECT id FROM employees WHERE id = ?");
			$sql->bind_param('s', $id);
			$sql->execute();
			$result = $sql->get_result();

			$resultCheck = mysqli_num_rows($result);

			return $resultCheck >= 1;
		}
		
		public static function ExistsEmployeesWorkplaceSawmillWithID($id) //Check if user works in Sawmill
		{
			global $conn;

			$sql = $conn->prepare("SELECT id FROM employees WHERE id = ? AND place = 'Zagetava' ");
			$sql->bind_param('s', $id);
			$sql->execute();
			$result = $sql->get_result();

			$resultCheck = mysqli_num_rows($result);

			return $resultCheck >= 1;
		}

		public static function GetEmployeesData($id)	//Returns all employee data with ID
		{
			global $conn;

			$sql = $conn->prepare("SELECT employees.* FROM employees 
									WHERE employees.id = ?");
			$sql->bind_param('s', $id);
			$sql->execute();
			$result = $sql->get_result();

			return mysqli_fetch_assoc($result);
		}

		public static function GetAllPositions($id) //Returns all employees positions
		{
			global $conn;

			$sql = $conn->prepare("SELECT positions.*  FROM positions
									JOIN employees_positions
									ON employees_positions.position_id = positions.id
									WHERE employees_positions.employee_id = ?");
			$sql->bind_param('s', $id);
			$sql->execute();
			$result = $sql->get_result();

			return mysqli_fetch_all($result, MYSQLI_ASSOC);
		}

		public static function ExistsSortingEmployee($id)	//Finds if shift exists in database
		{
			global $conn;

			$sql = $conn->prepare("SELECT id FROM employees WHERE id = ? AND place = 'Skirotava'");
			$sql->bind_param('s', $id);
			$sql->execute();
			$result = $sql->get_result();

			$resultCheck = mysqli_num_rows($result);

			return $resultCheck >= 1;
		}

		public static function ExistsPersonNo($person_id)	//Finds if employee with person_id exists
		{
			global $conn;

			$sql = $conn->prepare("SELECT person_id FROM employees WHERE person_id = ?");
			$sql->bind_param('s', $person_id);
			$sql->execute();
			$result = $sql->get_result();

			$resultCheck = mysqli_num_rows($result);

			return $resultCheck >= 1;
		}

		public static function CurrentPersonNoExists($person_id, $id)	//Returns true if position from other user in DB already exists
		{
			global $conn;

			$sql = $conn->prepare("SELECT person_id FROM employees WHERE person_id = ? AND id <> ?");
			$sql->bind_param('ss', $person_id, $id); //Binds parameter, transforms to string
			$sql->execute();
			$result = $sql->get_result();

			$resultCheck = mysqli_num_rows($result);

			return $resultCheck >= 1;
		}

	}

?>