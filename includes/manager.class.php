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

			$sql = $conn->prepare("SELECT * FROM positions
				JOIN employees_positions
				ON positions.id = employees_positions.position_id
				WHERE employees_positions.employee_id = ?");
			$sql->bind_param('i', $employee_id);
			$sql->execute();
			$return = $sql->get_result();

			return mysqli_fetch_all($return, MYSQLI_ASSOC);
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

			$sql = $conn->prepare("SELECT * FROM employees WHERE shift = ?");
			$sql->bind_param('s', $shift);
			$sql->execute();
			$result = $sql->get_result();

			return mysqli_fetch_all($result, MYSQLI_ASSOC);
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

		public static function AllPeriods()
		{
			global $conn;

			$sql = $conn->query("SELECT DISTINCT DATE_FORMAT(date, '%Y-%m') AS date,
									DATE_FORMAT(date, '%M %Y') AS month_year
									FROM sawmill_productions 
									ORDER BY date DESC");

			return mysqli_fetch_all($sql, MYSQLI_ASSOC);
		}

		public static function GetProductionsByDate($date_string) //Returns all chosen month productions
		{
			global $conn;

			$sql = $conn->prepare("SELECT DISTINCT sawmill_productions.*, employees.shift 
									FROM sawmill_productions
									JOIN employees_sawmill_productions
									ON sawmill_productions.id = employees_sawmill_productions.sawmill_id
									JOIN employees
									ON employees_sawmill_productions.employee_id = employees.id
									WHERE DATE_FORMAT(date, '%Y-%m') = ?
									ORDER BY date, time_from, time_to ASC");
			$sql->bind_param('s', $date_string);
			$sql->execute();
			$result = $sql->get_result();

			return mysqli_fetch_all($result, MYSQLI_ASSOC);
		}

		public static function ProductionMaintenances($id) //Returns production with ID all maintenances
		{
			global $conn;

			$sql = $conn->prepare("SELECT * FROM sawmill_maintenance WHERE sawmill_production_id = ?");
			$sql->bind_param('i', $id);
			$sql->execute();
			$result = $sql->get_result();

			return mysqli_fetch_all($result, MYSQLI_ASSOC);
		}

		public static function GetAllProductionSummByDate($date_string) //Returns all summ of monthly production
		{
			global $conn;

			$sql = $conn->prepare("SELECT DISTINCT 
				(SELECT SUM(sawmill_productions.beam_count) FROM sawmill_productions 
				WHERE DATE_FORMAT(sawmill_productions.date, '%Y-%m') = ?) AS beam_count,
				(SELECT SUM(sawmill_productions.beam_capacity) FROM sawmill_productions 
				WHERE DATE_FORMAT(sawmill_productions.date, '%Y-%m') = ?) AS beam_capacity,
				(SELECT SUM(sawmill_productions.lumber_count) FROM sawmill_productions
				WHERE DATE_FORMAT(sawmill_productions.date, '%Y-%m') = ?) AS lumber_count,
				(SELECT SUM(sawmill_productions.lumber_capacity) FROM sawmill_productions
				WHERE DATE_FORMAT(sawmill_productions.date, '%Y-%m') = ?) AS lumber_capacity,
				(SELECT SUM(sawmill_maintenance.time) FROM sawmill_maintenance 
				JOIN sawmill_productions
				ON sawmill_productions.id = sawmill_maintenance.sawmill_production_id
				WHERE DATE_FORMAT(sawmill_productions.date, '%Y-%m') = ?) AS maintenance
				FROM sawmill_productions");
			$sql->bind_param('sssss', $date_string, $date_string, $date_string, $date_string, $date_string);
			$sql->execute();
			$result = $sql->get_result();

			return mysqli_fetch_assoc($result);
		}

		public static function GetEmployeesByDate($date_string) //Returns all chosen month employees and production data 
		{
			global $conn;

			$sql = $conn->prepare("SELECT DISTINCT employees.* FROM employees
									WHERE employees.place = 'Zagetava' AND
									DATE_FORMAT(employees.working_from, '%Y-%m') <= ? AND
									(DATE_FORMAT(employees.working_to, '%Y-%m') >= ? OR employees.working_to IS NULL)
									ORDER BY employees.shift ASC");
			$sql->bind_param('ss', $date_string, $date_string);
			$sql->execute();
			$result = $sql->get_result();

			return mysqli_fetch_all($result, MYSQLI_ASSOC);
		}

		public static function GetEmployeeProductionsMaintenances($date_string, $shift, $id)
		{
			global $conn;

			$sql = $conn->prepare("SELECT employees.id, (SELECT SUM(sawmill_maintenance.time) FROM 							sawmill_maintenance
									JOIN sawmill_productions
									ON sawmill_productions.id = sawmill_maintenance.sawmill_production_id
									JOIN employees_sawmill_productions
									ON employees_sawmill_productions.sawmill_id = sawmill_productions.id
									JOIN employees
									ON employees.id = employees_sawmill_productions.employee_id
									JOIN working_times
									ON employees.id = working_times.employee_id
									WHERE DATE_FORMAT(sawmill_productions.date, '%Y-%m') = ? AND employees.shift = ? AND 
									sawmill_productions.date = working_times.date) AS maintanence
									FROM employees
									WHERE employees.id = ?");
			$sql->bind_param('sss', $date_string, $shift, $id);
			$sql->execute();
			$result = $sql->get_result();

			return mysqli_fetch_all($result, MYSQLI_ASSOC);
		}

	}

?>