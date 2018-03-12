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

		function __construct()
		{
			global $conn;
			$this->conn = $conn;
		}

		function Save()	//Inserts new sorting production data into database
		{
			try
			{
				$sql = $this->conn->prepare("INSERT INTO sorting_productions VALUES (DEFAULT, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
				$sql->bind_param('sssiiiiidi', $this->date, $this->time_from, $this->time_to, $this->invoice, $this->thickness, $this->width, $this->length, $this->count, $this->capacity, $this->defect_count);
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
	}

?>