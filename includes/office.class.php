<?php

	include "config.php";

	class Office
	{
		private $conn;

		function __construct()
		{
			global $conn;
			$this->conn = $conn;
		}

		public static function AllOfficePeriods($month)	//Returns all office periods (from times and working_times tables with UNION)
		{
			global $conn;

			$sql = $conn->prepare("SELECT DISTINCT DATE_FORMAT(date, '%Y-%m') AS date,
									DATE_FORMAT(date, '%M %Y') AS month_year
									FROM working_times
									WHERE working_times.invoice IS NULL AND 
									DATE_FORMAT(working_times.date, '%Y-%m') < ?
									UNION
									SELECT DISTINCT DATE_FORMAT(date, '%Y-%m') AS date,
									DATE_FORMAT(date, '%M %Y') AS month_year
									FROM times
									WHERE times.invoice IS NULL AND 
									DATE_FORMAT(times.date, '%Y-%m') < ?
									ORDER BY date DESC");

			$sql->bind_param('ss', $month, $month);
			$sql->execute();
			$result = $sql->get_result();

			return mysqli_fetch_all($result, MYSQLI_ASSOC);
		}

		public static function GetOfficeEmployeesByDate($date_string)	//Returns all office employees
		{
			global $conn;

			$sql = $conn->prepare("SELECT DISTINCT employees.* FROM employees
									WHERE employees.place = 'Birojs' AND
									DATE_FORMAT(employees.working_from, '%Y-%m') <= ? AND
									(DATE_FORMAT(employees.working_to, '%Y-%m') >= ? 
									OR employees.working_to IS NULL)
									ORDER BY employees.shift ASC");
			$sql->bind_param('ss', $date_string, $date_string);
			$sql->execute();
			$result = $sql->get_result();

			return mysqli_fetch_all($result, MYSQLI_ASSOC);
		}

		public static function ExistsBureauEmployeeWithID($id) //Checks if employee works in office
		{
			global $conn;

			$sql = $conn->prepare("SELECT id FROM employees WHERE id = ? AND place = 'Birojs'");
			$sql->bind_param('s', $id);
			$sql->execute();
			$result = $sql->get_result();

			$resultCheck = mysqli_num_rows($result);

			return $resultCheck >= 1;
		}

		public static function BureauEmployeeWorkingTimes($id, $date)	//Gets all bureau employee working times from database
		{
			global $conn;

			$sql = $conn->prepare("SELECT working_times.working_hours, working_times.overtime_hours 
									FROM working_times WHERE working_times.employee_id = ? AND
									 working_times.date = ? AND working_times.invoice IS NULL");
			$sql->bind_param('ss', $id, $date);
			$sql->execute();
			$result = $sql->get_result();

			return mysqli_fetch_assoc($result);
		}

		public static function BureauEmployeeNonWorkingTimes($id, $date)	//Gets all bureau employee nonworking times from database
		{
			global $conn;

			$sql = $conn->prepare("SELECT CONCAT_WS('', times.vacation, times.sick_leave, times.nonattendace,
									times.pregnancy) as nonworking
									FROM times WHERE times.employee_id = ? AND times.date = ? 
									AND times.invoice IS NULL");
			$sql->bind_param('ss', $id, $date);
			$sql->execute();
			$result = $sql->get_result();

			return mysqli_fetch_assoc($result);
		}

		public static function BureauEmployeeWorkingStatistic($id, $date_string)	//Returns all bureau employees days worked statistic
		{
			global $conn;

			$sql = $conn->prepare("SELECT COUNT(DISTINCT working_times.date) as working_days, 
									SUM(working_times.working_hours) as working_hours, 
									SUM(working_times.overtime_hours) as overtime_hours
									FROM working_times
									WHERE working_times.employee_id = ? AND 
									DATE_FORMAT(working_times.date, '%Y-%m') = ? 
									AND working_times.invoice IS NULL");
			$sql->bind_param('ss', $id, $date_string);
			$sql->execute();
			$result = $sql->get_result();

			return mysqli_fetch_assoc($result);
		}

		public static function BureauEmployeeNonWorkingStatistic($id, $date_string)	//Returns all bureau employees days nonworked statistic
		{
			global $conn;

			$sql = $conn->prepare("SELECT COUNT(DISTINCT times.date) as nonworking_days, 
									COUNT(times.vacation) as vacation, 
									COUNT(times.sick_leave) as sick_leave,
									COUNT(times.nonattendace) as nonattendance,
									COUNT(times.pregnancy) as pregnancy
									FROM times
									WHERE times.employee_id = ? AND 
									DATE_FORMAT(times.date, '%Y-%m') = ?
									AND times.invoice IS NULL");
			$sql->bind_param('ss', $id, $date_string);
			$sql->execute();
			$result = $sql->get_result();

			return mysqli_fetch_assoc($result);
		}

		public static function Weekdays($month_index, $year_index)	//Get count of week days
		{
			$lastday = date("t", mktime(0, 0, 0, $month_index, 1, $year_index));	//Count of days in month
			$weekdays = 0;
			for($d = 29;$d <= $lastday; $d++)
			{
				$wd = date("w",mktime(0, 0, 0, $month_index, $d, $year_index));
				if($wd > 0 && $wd < 6)
				{
					$weekdays++;
				}
			}

			return $weekdays + 20;
		}
	}
?>