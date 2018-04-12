<?php

	include "config.php";

	class SortingProduction
	{
		private $conn;
		public $id;
		public $date;
		public $time_from;
		public $time_to;
		public $invoice;
		public $thickness;
		public $width;
		public $length;
		public $count;
		public $capacity;
		public $defect_count;
		public $reserved;

		function __construct()
		{
			global $conn;
			$this->conn = $conn;
		}

		function Save()	//Inserts new sorting production data into database
		{
			try
			{
				$sql = $this->conn->prepare("INSERT INTO sorting_productions VALUES (DEFAULT, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
				$sql->bind_param('sssiiiiidii', $this->date, $this->time_from, $this->time_to, $this->invoice, $this->thickness, $this->width, $this->length, $this->count, $this->capacity, $this->defect_count, $this->reserved);
				$sql->execute();

				$this->id = $this->conn->insert_id; //Sets object id
			}
			catch(mysqli_sql_exception $e)
			{	
				$_SESSION['error'] = "Radās kļūda ierakstot datus!";
				header("Location: /");
				exit();
			}
		}

		public static function ExistsNonReservedProductionWithID($id) //Checks if not reserved sorting production with such ID exists
		{
			global $conn;

			$sql = $conn->prepare("SELECT id FROM sorting_productions WHERE id = ? AND reserved = 0");
			$sql->bind_param('s', $id);
			$sql->execute();
			$result = $sql->get_result();

			$resultCheck = mysqli_num_rows($result);

			return $resultCheck >= 1;
		}

		public static function GetSortingProductionData($id)	//Returns all productions data with ID
		{
			global $conn;

			$sql = $conn->prepare("SELECT sorting_productions.*
								FROM sorting_productions
								WHERE sorting_productions.id = ?");
			$sql->bind_param('s', $id);
			$sql->execute();
			$result = $sql->get_result();

			return mysqli_fetch_assoc($result);
		}

		public static function GetAllSortedProductionData($id)	//Returns all sorting productions sorted production data
		{
			global $conn;

			$sql = $conn->prepare("SELECT sorted_productions.*
								FROM sorted_productions
								WHERE sorted_productions.sorting_id = ?");
			$sql->bind_param('s', $id);
			$sql->execute();
			$result = $sql->get_result();

			return mysqli_fetch_all($result, MYSQLI_ASSOC);
		}
	}

?>