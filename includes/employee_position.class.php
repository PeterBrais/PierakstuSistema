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

		function DeleteAllUserPositions($id)	//Deletes sub table data M:M relations
		{
			try
			{
				$sql = $this->conn->prepare("DELETE FROM employees_positions WHERE employee_id = ?");
				$sql->bind_param('s', $id);
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