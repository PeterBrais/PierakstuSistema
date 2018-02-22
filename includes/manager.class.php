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

		public static function Positions()
		{
			global $conn;

			$sql = $conn->query("SELECT id, position FROM positions");

			return mysqli_fetch_all($sql, MYSQLI_ASSOC);

			// $rows = array();



			// while($row = $sql->fetch_assoc())
			// {
			// 	$rows[] = $row;
			// }

			// return $rows;
		}

		public static function Employees()
		{
			global $conn;

			$sql = $conn->query("SELECT * FROM employees");

			return mysqli_fetch_all($sql, MYSQLI_ASSOC);

			//Metodi employees klasei statiksa metode get kas atgriez employee klases objektu
		}

	}

?>