<?php

	include "config.php";

	class EmployeeSawmillProductions
	{
		private $conn;
		public $id;
		public $employee_id;
		public $sawmill_id;

		function __construct()
		{
			global $conn;
			$this->conn = $conn;
		}

		function Save() //Inserts data to Many:Many relation from Sawmill production and Employee table
		{
			try
			{
				$sql = $this->conn->prepare("INSERT INTO employees_sawmill_productions VALUES (DEFAULT, ?, ?)");
				$sql->bind_param('ii', $this->employee_id, $this->sawmill_id);
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

		function DeleteAllSawmillProductionEmployees($id)	//Deletes sub table data M:M relations
		{
			try
			{
				$sql = $this->conn->prepare("DELETE FROM employees_sawmill_productions WHERE sawmill_id = ?");
				$sql->bind_param('s', $id);
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