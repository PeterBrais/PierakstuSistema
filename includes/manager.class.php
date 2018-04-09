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

		public static function GetPositionData($id)	//Returns all position data with ID
		{
			global $conn;

			$sql = $conn->prepare("SELECT positions.* FROM positions 
									WHERE positions.id = ?");
			$sql->bind_param('s', $id);
			$sql->execute();
			$result = $sql->get_result();

			return mysqli_fetch_assoc($result);
		}

		public static function BeamSizes()	//Returns all entered beam sizes from Database
		{
			global $conn;

			$sql = $conn->query("SELECT id, size FROM beam_sizes");

			return mysqli_fetch_all($sql, MYSQLI_ASSOC);
		}

		public static function GetBeamSizeData($id)	//Returns all beam size data with ID
		{
			global $conn;

			$sql = $conn->prepare("SELECT beam_sizes.* FROM beam_sizes 
									WHERE beam_sizes.id = ?");
			$sql->bind_param('s', $id);
			$sql->execute();
			$result = $sql->get_result();

			return mysqli_fetch_assoc($result);
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

		public static function AllSawmillPeriods()
		{
			global $conn;

			$sql = $conn->query("SELECT DISTINCT DATE_FORMAT(date, '%Y-%m') AS date,
									DATE_FORMAT(date, '%M %Y') AS month_year
									FROM sawmill_productions 
									ORDER BY date DESC");

			return mysqli_fetch_all($sql, MYSQLI_ASSOC);
		}

		public static function GetSawmillProductionsByDate($date_string) //Returns all chosen month sawmill productions
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

		public static function GetAllSawmillProductionSummByDate($date_string) //Returns all summ of monthly production
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

		public static function GetSawmillEmployeesByDate($date_string) //Returns all chosen month employees and production data 
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

		public static function GetEmployeeProductionsMaintenances($date_string, $id)	//Returns all employees maintenances
		{
			global $conn;

			$sql = $conn->prepare("SELECT SUM(sawmill_maintenance.time) as maintenance
									FROM employees
									JOIN employees_sawmill_productions
									ON employees_sawmill_productions.employee_id = employees.id
									JOIN sawmill_productions
									ON sawmill_productions.id = employees_sawmill_productions.sawmill_id
									JOIN sawmill_maintenance
									ON sawmill_maintenance.sawmill_production_id = sawmill_productions.id
									WHERE employees.id = ? AND 
									DATE_FORMAT(sawmill_productions.date, '%Y-%m') = ? AND 
									EXISTS (SELECT * FROM working_times WHERE employees.id = working_times.employee_id
									AND sawmill_productions.date = working_times.date 
									AND sawmill_productions.invoice = working_times.invoice)");
			$sql->bind_param('ss', $id, $date_string);
			$sql->execute();
			$result = $sql->get_result();

			return mysqli_fetch_assoc($result);
		}

		public static function GetEmployeeProductionsCapacity($date_string, $id)	//Returns all employees amount of work done
		{
			global $conn;

			$sql = $conn->prepare("SELECT SUM(sawmill_productions.lumber_capacity) as capacity
									FROM employees
									JOIN employees_sawmill_productions
									ON employees_sawmill_productions.employee_id = employees.id
									JOIN sawmill_productions
									ON sawmill_productions.id = employees_sawmill_productions.sawmill_id
									WHERE employees.id = ? AND 
									DATE_FORMAT(sawmill_productions.date, '%Y-%m') = ? AND
									EXISTS (SELECT * FROM working_times WHERE employees.id = working_times.employee_id
									AND sawmill_productions.date = working_times.date 
									AND sawmill_productions.invoice = working_times.invoice)");
			$sql->bind_param('ss', $id, $date_string);
			$sql->execute();
			$result = $sql->get_result();

			return mysqli_fetch_assoc($result);
		}

		public static function GetEmployeeProductionsDaysWorked($date_string, $id)	//Returns all employees days worked and all productions
		{
			global $conn;

			$sql = $conn->prepare("SELECT COUNT(DISTINCT working_times.date) as working, 
									(SELECT COUNT(DISTINCT sawmill_productions.date) FROM employees
									JOIN employees_sawmill_productions
									ON employees_sawmill_productions.employee_id = employees.id
									JOIN sawmill_productions
									ON sawmill_productions.id = employees_sawmill_productions.sawmill_id
									JOIN working_times
									ON working_times.employee_id = employees.id
									WHERE employees.id = ? AND 
									DATE_FORMAT(sawmill_productions.date, '%Y-%m') = ?) as all_productions
									FROM employees
									JOIN employees_sawmill_productions
									ON employees_sawmill_productions.employee_id = employees.id
									JOIN sawmill_productions
									ON sawmill_productions.id = employees_sawmill_productions.sawmill_id
									JOIN working_times
									ON working_times.employee_id = employees.id
									WHERE employees.id = ? AND 
									DATE_FORMAT(sawmill_productions.date, '%Y-%m') = ? 
									AND employees.id = working_times.employee_id 
									AND sawmill_productions.date = working_times.date 
									AND sawmill_productions.invoice = working_times.invoice");
			$sql->bind_param('ssss', $id, $date_string, $id, $date_string);
			$sql->execute();
			$result = $sql->get_result();

			return mysqli_fetch_assoc($result);
		}

		public static function GetEmployeeProductionsDaysNonWorked($date_string, $id)	//Returns all employees non working days
		{
			global $conn;

			$sql = $conn->prepare("SELECT times.* FROM employees
									JOIN employees_sawmill_productions
									ON employees_sawmill_productions.employee_id = employees.id
									JOIN sawmill_productions
									ON sawmill_productions.id = employees_sawmill_productions.sawmill_id
									JOIN times
									ON times.employee_id = employees.id
									WHERE employees.id = ? AND 
									DATE_FORMAT(sawmill_productions.date, '%Y-%m') = ? 
									AND employees.id = times.employee_id 
									AND sawmill_productions.date = times.date 
									AND sawmill_productions.invoice = times.invoice");
			$sql->bind_param('ss', $id, $date_string);
			$sql->execute();
			$result = $sql->get_result();

			return mysqli_fetch_all($result, MYSQLI_ASSOC);
		}

		public static function GetSortingEmployees($date_string)	//Returns all employees with selected shift
		{
			global $conn;

			$sql = $conn->prepare("SELECT * FROM employees WHERE place = 'Skirotava'
					AND DATE_FORMAT(employees.working_from, '%Y-%m') <= ? AND
					(DATE_FORMAT(employees.working_to, '%Y-%m') >= ? OR employees.working_to IS NULL)");

			$sql->bind_param('ss', $date_string, $date_string);
			$sql->execute();
			$result = $sql->get_result();

			return mysqli_fetch_all($result, MYSQLI_ASSOC);
		}

		public static function AllSortingPeriods()
		{
			global $conn;

			$sql = $conn->query("SELECT DISTINCT DATE_FORMAT(date, '%Y-%m') AS date,
									DATE_FORMAT(date, '%M %Y') AS month_year
									FROM sorting_productions 
									ORDER BY date DESC");

			return mysqli_fetch_all($sql, MYSQLI_ASSOC);
		}

		public static function GetSortingProductionsByInvoice($date_string) //Returns all invoices and their sorting productions
		{
			global $conn;

			$sql = $conn->prepare("SELECT invoice
									FROM sorting_productions
									WHERE DATE_FORMAT(date, '%Y-%m') = ?
									GROUP BY invoice ORDER BY date");
			$sql->bind_param('s', $date_string);
			$sql->execute();
			$result = $sql->get_result();

			return mysqli_fetch_all($result, MYSQLI_ASSOC);
		}

		public static function GetSortingProductions($invoice) //Returns all chosen month sorting productions
		{
			global $conn;

			$sql = $conn->prepare("SELECT sorting_productions.*,
									(SELECT COUNT(sorted_productions.sorting_id) 
									FROM sorted_productions
									WHERE sorting_productions.id = sorted_productions.sorting_id) as total_sorted
									FROM sorting_productions
									WHERE sorting_productions.invoice = ?
									ORDER BY date, time_from, time_to ASC");
			$sql->bind_param('s', $invoice);
			$sql->execute();
			$result = $sql->get_result();

			return mysqli_fetch_all($result, MYSQLI_ASSOC);
		}

		public static function GetSortingEmployeesByDate($date_string) //Returns all chosen month employees and production data 
		{
			global $conn;

			$sql = $conn->prepare("SELECT employees.* FROM employees
									WHERE employees.place = 'Skirotava' AND
									DATE_FORMAT(employees.working_from, '%Y-%m') <= ? AND
									(DATE_FORMAT(employees.working_to, '%Y-%m') >= ? OR 
									employees.working_to IS NULL)");
			$sql->bind_param('ss', $date_string, $date_string);
			$sql->execute();
			$result = $sql->get_result();

			return mysqli_fetch_all($result, MYSQLI_ASSOC);
		}

		public static function GetAllSortingProductionSummByDate($date_string) //Returns all summ of monthly production
		{
			global $conn;

			$sql = $conn->prepare("SELECT DISTINCT 
				(SELECT SUM(sorting_productions.count) FROM sorting_productions
				WHERE DATE_FORMAT(sorting_productions.date, '%Y-%m') = ?) AS count,
				(SELECT SUM(sorting_productions.capacity) FROM sorting_productions 
				WHERE DATE_FORMAT(sorting_productions.date, '%Y-%m') = ?) AS capacity,
				(SELECT SUM(sorting_productions.defect_count) FROM sorting_productions
				WHERE DATE_FORMAT(sorting_productions.date, '%Y-%m') = ?) AS defect_count,
				(SELECT SUM(sorted_productions.count) FROM sorted_productions
				JOIN sorting_productions
				ON sorting_productions.id = sorted_productions.sorting_id
				WHERE DATE_FORMAT(sorting_productions.date, '%Y-%m') = ?) AS sorted_count,
				(SELECT SUM(sorted_productions.capacity) FROM sorted_productions
				JOIN sorting_productions
				ON sorting_productions.id = sorted_productions.sorting_id
				WHERE DATE_FORMAT(sorting_productions.date, '%Y-%m') = ?) AS sorted_capacity
				FROM sorting_productions");
			$sql->bind_param('sssss', $date_string, $date_string, $date_string, $date_string, $date_string);
			$sql->execute();
			$result = $sql->get_result();

			return mysqli_fetch_assoc($result);
		}


		public static function GetSortedProductionsByID($production_id) //Returns all sorted productions
		{
			global $conn;

			$sql = $conn->prepare("SELECT * FROM sorted_productions
									WHERE sorted_productions.sorting_id = ?
									ORDER BY sorted_productions.id");
			$sql->bind_param('s', $production_id);
			$sql->execute();
			$result = $sql->get_result();

			return mysqli_fetch_all($result, MYSQLI_ASSOC);
		}

		public static function GetAllSortingProductionWorkers($production_id) //Returns all workers for sorting production
		{
			global $conn;

			$sql = $conn->prepare("SELECT employees.id, employees.name, employees.last_name
									FROM employees
									JOIN employees_sorted_productions
									ON employees.id = employees_sorted_productions.employee_id
									JOIN sorted_productions
									ON employees_sorted_productions.sorted_id = sorted_productions.id
									WHERE sorted_productions.id = ?
									ORDER BY employees.id");
			$sql->bind_param('s', $production_id);
			$sql->execute();
			$result = $sql->get_result();

			return mysqli_fetch_all($result, MYSQLI_ASSOC);
		}
	}
?>