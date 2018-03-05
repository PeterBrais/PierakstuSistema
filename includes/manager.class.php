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
		}

		public static function BeamSizes()	//Returns all entered beam sizes from Database
		{
			global $conn;

			$sql = $conn->query("SELECT id, size FROM beam_sizes");

			return mysqli_fetch_all($sql, MYSQLI_ASSOC);
		}

		public static function Employees()	//Returns all employees from Database
		{
			global $conn;

			$sql = $conn->query("SELECT * FROM employees ORDER BY shift, last_name, name ASC");

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

		public static function AllShifts()	//Returns all shifts from database without repeating
		{
			global $conn;

			$sql = $conn->query("SELECT DISTINCT shift FROM employees WHERE place='Zagetava' ORDER BY shift ASC");

			return mysqli_fetch_all($sql, MYSQLI_ASSOC);
		}

		public static function GetEmployeesByShift($shift)	//Returns all employees with selected shift
		{
			global $conn;

			$sql = $conn->query("SELECT * FROM employees WHERE shift = $shift");

			return mysqli_fetch_all($sql, MYSQLI_ASSOC);
		}

		public static function ExistsShift($shift)	//Finds if shift exists in database
		{
			global $conn;

			$sql = $conn->prepare("SELECT DISTINCT shift FROM employees WHERE shift=?");
			$sql->bind_param('s', $shift);
			$sql->execute();
			$result = $sql->get_result();

			$resultCheck = mysqli_num_rows($result);

			return $resultCheck >= 1;
		}

		public static function Productions()
		{
			global $conn;

			$sql = $conn->query("SELECT DISTINCT sawmill_productions.*, employees.shift 
			FROM sawmill_productions
			JOIN employees_sawmill_productions
			ON sawmill_productions.id = employees_sawmill_productions.sawmill_id
			JOIN employees
			ON employees_sawmill_productions.employee_id = employees.id
			ORDER BY date, time_from, time_to ASC");

			return mysqli_fetch_all($sql, MYSQLI_ASSOC);
		}

		public static function ProductionMaintenances($id)
		{
			global $conn;

			$sql = $conn->query("SELECT * FROM sawmill_maintenance WHERE sawmill_production_id = $id");

			return mysqli_fetch_all($sql, MYSQLI_ASSOC);
		}

	}

?>