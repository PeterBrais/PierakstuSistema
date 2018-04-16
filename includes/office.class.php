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
									WHERE working_times.invoice IS NOT NULL AND 
									DATE_FORMAT(working_times.date, '%Y-%m') < ?
									UNION
									SELECT DISTINCT DATE_FORMAT(date, '%Y-%m') AS date,
									DATE_FORMAT(date, '%M %Y') AS month_year
									FROM times
									WHERE times.invoice IS NOT NULL AND 
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

		// function Calendar($month_index, $year_index)
		// {
		// 	//Array of week days
		// 	$daysOfWeek = array('P','O','T','C','P','S','Sv');

		// 	//Get first day of month timestamp
		// 	$firstDayOfMonth = mktime(0, 0, 0, $month_index, 1, $year_index);

		// 	//Number of days in month
		// 	$numberDays = date('t', $firstDayOfMonth);

		// 	//Retrieve information about first day of the month
		// 	$dateComponents = getdate($firstDayOfMonth);

		// 	//Index value of the first day
		// 	$dayOfWeek = $dateComponents['wday'];
		// 	if($dayOfWeek == 0)
		// 	{
		// 		$dayOfWeek = 6;
		// 	}
		// 	else
		// 	{
		// 		$dayOfWeek = $dayOfWeek - 1;
		// 	}

		//     //Creating table with concatenation
		// 	$calendar = "<table class='table table-bordered'>";
		// 	$calendar .= "<thead class='thead-default table-active'><tr>";

		// 	//Calendar header
		// 	foreach($daysOfWeek as $day)
		// 	{
		// 		$calendar .= "<th>$day</th>";
		// 	}

		// 	$calendar .= "</tr></thead><tr>";

		// 	//Current day starts with 1
		// 	$currentDay = 1;

		// 	//Colspan to the starting month day (7 columns)
		// 	if($dayOfWeek > 0)
		// 	{ 
		// 		$calendar .= "<td colspan='$dayOfWeek' class='table-light'></td>"; 
		// 	}

		// 	//Month index with leading zero
		// 	$month = str_pad($month_index, 2, "0", STR_PAD_LEFT);

		// 	//Goes thru all month days
		// 	while($currentDay <= $numberDays)
		// 	{
		// 		//New week starts in new table row
		// 		if($dayOfWeek == 7)
		// 		{
		// 			$dayOfWeek = 0;
		// 			$calendar .= "</tr><tr>";
		// 		}

		// 		$currentDayRel = str_pad($currentDay, 2, "0", STR_PAD_LEFT);

		// 		//Date index
		// 		$date = "$year_index-$month-$currentDayRel";
		// 		$calendar .= "<td id='$date'>$currentDay</td>";

		// 		//Increases variables
		// 		$currentDay++;
		// 		$dayOfWeek++;
		// 	}

		// 	//Colspan to the ending month day
		// 	if($dayOfWeek != 7)
		// 	{ 
		// 		$remainingDays = 7 - $dayOfWeek;
		// 		$calendar .= "<td colspan='$remainingDays' class='table-light'></td>"; 
		// 	}

		// 	$calendar .= "</tr></table>";

		// 	return $calendar;
		// }
	}
?>