<?php

	include "config.php";

	class Times
	{
		private $conn;
		public $id;
		public $datetime;
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

		public static function GetWorkersNonWorkingTime($id, $date, $invoice, $timestamp) //Gets all employees nonworked hours
		{
			global $conn;

			$sql = $conn->prepare("SELECT times.* FROM employees
									JOIN times ON times.employee_id = employees.id
									WHERE times.date = ? AND employees.id = ? AND times.invoice = ? AND times.datetime = ?");
			$sql->bind_param('ssss', $date, $id, $invoice, $timestamp);
			$sql->execute();
			$result = $sql->get_result();

			return mysqli_fetch_assoc($result);
		}

		function Save()	//Saves data into table: times
		{
			try
			{
				$sql = $this->conn->prepare("INSERT INTO times VALUES (DEFAULT, ?, ?, ?, ?, ?, ?, ?, ?)");
				$sql->bind_param('ssissssi', $this->date, $this->datetime, $this->invoice, $this->vacation, $this->sick_leave, $this->nonattendance, $this->pregnancy, $this->employee_id);
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

		function DeleteAllNonWorkingEmployees($invoice, $date, $timestamp){	//Deletes all employee non working times
			try
			{
				$sql = $this->conn->prepare("DELETE FROM times WHERE invoice = ? AND date = ? AND datetime = ?");
				$sql->bind_param('iss', $invoice, $date, $timestamp);
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

		function DeleteAllNonWorkingBureauEmployees($id, $period){	//Deletes all bureau employee non working times
			try
			{
				$sql = $this->conn->prepare("DELETE FROM times WHERE invoice IS NULL AND employee_id = ?
												AND DATE_FORMAT(times.date, '%Y-%m') = ?");
				$sql->bind_param('ss', $id, $period);
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