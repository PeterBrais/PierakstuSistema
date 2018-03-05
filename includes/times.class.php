<?php

	include "config.php";

	class Times
	{
		private $conn;
		public $id;
		public $date;
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

		function Save()
		{
			try
			{
				$sql = $this->conn->prepare("INSERT INTO times VALUES (DEFAULT, ?, ?, ?, ?, ?, ?)");
				$sql->bind_param('sssssi', $this->date, $this->vacation, $this->sick_leave, $this->nonattendance, $this->pregnancy, $this->employee_id);
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