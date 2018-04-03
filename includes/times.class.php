<?php

	include "config.php";

	class Times
	{
		private $conn;
		public $id;
		public $date;
		public $invoice;
		public $vacation;
		public $sick_leave;
		public $nonattendance;
		public $pregnancy;
		public $employee_id;

		function __construct()
		{
			global $conn;
			$this->conn = $conn;
		}

		public static function GetWorkersNonWorkingTime($id, $date, $invoice) //Gets all employees nonworked hours
		{
			global $conn;

			$sql = $conn->prepare("SELECT times.*FROM employees
									JOIN times ON times.employee_id = employees.id
									WHERE times.date = ? AND employees.id = ? AND times.invoice = ?");
			$sql->bind_param('sss', $date, $id, $invoice);
			$sql->execute();
			$result = $sql->get_result();

			return mysqli_fetch_assoc($result);
		}

		function Save()	//Saves data into table: times
		{
			try
			{
				$sql = $this->conn->prepare("INSERT INTO times VALUES (DEFAULT, ?, ?, ?, ?, ?, ?, ?)");
				$sql->bind_param('sissssi', $this->date, $this->invoice, $this->vacation, $this->sick_leave, $this->nonattendance, $this->pregnancy, $this->employee_id);
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

		function DeleteAllNonWorkingEmployees($invoice){	//Deletes all employee non working times
			try
			{
				$sql = $this->conn->prepare("DELETE FROM times WHERE invoice = ?");
				$sql->bind_param('i', $invoice);
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