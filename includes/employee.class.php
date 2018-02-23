<?php

	include "config.php";

	class Employee
	{
		private $conn;
		public $id;
		public $name;
		public $last_name;
		public $working_from;
		public $working_to;
		public $place;

		function __construct()
		{
			global $conn;
			$this->conn = $conn;
		}

		function Save()	//Inserts new employee data into Database
		{
			try
			{
				$sql = $this->conn->prepare("INSERT INTO employees VALUES (DEFAULT, ?, ?, ?, ?, ?)");
				$sql->bind_param('sssss', $this->name, $this->last_name, $this->working_from, $this->working_to, $this->place);
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

	}

?>