<?php

	include "config.php";

	class Employee
	{
		private $conn;
		public $id;
		public $name;
		public $last_name;
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
				$sql = $this->conn->prepare("INSERT INTO employees VALUES (DEFAULT, ?, ?, ?, ?, ?, ?, ?, ?)");
				$sql->bind_param('ssssddss', $this->name, $this->last_name, $this->place, $this->shift, $this->capacity_rate, $this->hour_rate, $this->working_from, $this->working_to);
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

		public static function ExistsSortingEmployee($id)	//Finds if shift exists in database
		{
			global $conn;

			$sql = $conn->prepare("SELECT id FROM employees WHERE id=? AND place = 'Skirotava'");
			$sql->bind_param('s', $id);
			$sql->execute();
			$result = $sql->get_result();

			$resultCheck = mysqli_num_rows($result);

			return $resultCheck >= 1;
		}

	}

?>