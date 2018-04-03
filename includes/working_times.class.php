<?php

	include "config.php";

	class WorkingTimes
	{
		private $conn;
		public $id;
		public $date;
		public $invoice;
		public $working_hours;
		public $overtime_hours;
		public $holiday_hours;
		public $employee_id;

		function __construct()
		{
			global $conn;
			$this->conn = $conn;
		}

		public static function GetWorkersWorkingTime($id, $date, $invoice) //Gets all employees worked hours
		{
			global $conn;

			$sql = $conn->prepare("SELECT working_times.* FROM employees
								JOIN working_times ON working_times.employee_id = employees.id
								WHERE working_times.date = ? AND employees.id = ? AND working_times.invoice = ?");
			$sql->bind_param('sss', $date, $id, $invoice);
			$sql->execute();
			$result = $sql->get_result();

			return mysqli_fetch_assoc($result);
		}

		function DeleteAllWorkingEmployees($invoice){	//Deletes all employee working times
			try
			{
				$sql = $this->conn->prepare("DELETE FROM working_times WHERE invoice = ?");
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

		function Save()	//Saves data into database working_times
		{
			try
			{
				$sql = $this->conn->prepare("INSERT INTO working_times VALUES (DEFAULT, ?, ?, ?, ?, ?, ?)");
				$sql->bind_param('siiiii', $this->date, $this->invoice, $this->working_hours, $this->overtime_hours, $this->holiday_hours, $this->employee_id);
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