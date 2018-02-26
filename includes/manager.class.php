<?php
	
	include "config.php";

	class Manager
	{
		private $conn;

		function __construct()
		{
			global $conn;
			$this->conn = $conn;
		}

		public static function Positions()	//Returns all employee positions from Database
		{
			global $conn;

			$sql = $conn->query("SELECT id, name FROM positions");

			return mysqli_fetch_all($sql, MYSQLI_ASSOC);

			// $rows = array();
			// while($row = $sql->fetch_assoc())
			// {
			// 	$rows[] = $row;
			// }
			// return $rows;
		}

		public static function Employees()	//Returns all employees from Database
		{
			global $conn;

			$sql = $conn->query("SELECT * FROM employees");

			return mysqli_fetch_all($sql, MYSQLI_ASSOC);
		}

		public static function EmployeePositions($employee_id)	//Returns employees with ID all positions
		{
			global $conn;

			$sql = $conn->query("SELECT * FROM positions
				JOIN employees_positions
				ON positions.id = employees_positions.position_id
				WHERE employees_positions.employee_id = $employee_id");

			return mysqli_fetch_all($sql, MYSQLI_ASSOC);
		}

	}

?>