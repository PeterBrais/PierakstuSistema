<?php

	include "config.php";

	class SortedProduction
	{
		private $conn;
		public $id;
		public $type;
		public $count;
		public $thickness;
		public $width;
		public $length;
		public $capacity;
		public $capacity_per_piece;
		public $sorting_id;

		function __construct()
		{
			global $conn;
			$this->conn = $conn;
		}

		function Save()	//Inserts new sorting production data into database
		{
			try
			{
				$sql = $this->conn->prepare("INSERT INTO sorted_production VALUES (DEFAULT, ?, ?, ?, ?, ?, ?, ?, ?)");
				$sql->bind_param('siiiiddi', $this->type, $this->count, $this->thickness, $this->width, $this->length, $this->capacity, $this->capacity_per_piece, $this->sorting_id);
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