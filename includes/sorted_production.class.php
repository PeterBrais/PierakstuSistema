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
				$sql = $this->conn->prepare("INSERT INTO sorted_productions VALUES (DEFAULT, ?, ?, ?, ?, ?, ?, ?, ?)");
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

		function DeleteAllSortingProductionSortedProductions($id){	//Deletes 1:M relations table data
			try
			{
				$sql = $this->conn->prepare("DELETE FROM sorted_productions WHERE sorting_id = ?");
				$sql->bind_param('s', $id);
				$sql->execute();
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