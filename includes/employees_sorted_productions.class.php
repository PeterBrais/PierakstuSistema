<?php

	include "config.php";

	class EmployeeSortedProductions
	{
		private $conn;
		public $id;
		public $employee_id;
		public $sorted_id;

		function __construct()
		{
			global $conn;
			$this->conn = $conn;
		}

		function Save() //Inserts data to Many:Many relation from Sorting production and Employee table
		{
			try
			{
				$sql = $this->conn->prepare("INSERT INTO employees_sorted_productions VALUES (DEFAULT, ?, ?)");
				$sql->bind_param('ii', $this->employee_id, $this->sorted_id);
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

		function DeleteAllSortedProductionEmployees($id){	//Deletes sub table data M:M relations
			try
			{
				$sql = $this->conn->prepare("DELETE FROM employees_sorted_productions WHERE sorted_id = ?");
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