<?php

	include "config.php";

	class WorkingTimes
	{
		private $conn;
		public $id;
		public $date;
		public $working_hours;
		public $overtime_hours;
		public $holiday_hours;
		public $employee_id;

		function __construct()
		{
			global $conn;
			$this->conn = $conn;
		}

		function Save()
		{
			try
			{
				$sql = $this->conn->prepare("INSERT INTO working_times VALUES (DEFAULT, ?, ?, ?, ?, ?)");
				$sql->bind_param('siiii', $this->date, $this->working_hours, $this->overtime_hours, $this->holiday_hours, $this->employee_id);
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