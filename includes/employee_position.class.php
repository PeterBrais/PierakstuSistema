<?php

	include "config.php";

	class EmployeePosition
	{
		private $conn;
		public $id;
		public $employee_id;
		public $position_id;

		function __construct()
		{
			global $conn;
			$this->conn = $conn;
		}

		function Save()	//Inserts data, Many:Many relation from Positions and Employee table
		{
			try
			{
				$sql = $this->conn->prepare("INSERT INTO employees_positions VALUES (DEFAULT, ?, ?)");
				$sql->bind_param('ii', $this->employee_id, $this->position_id);
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