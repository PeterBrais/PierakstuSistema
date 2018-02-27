<?php
	
	include "config.php";

	class SawmillMaintenance
	{
		private $conn;
		public $id;
		public $time;
		public $note;
		public $sawmill_production_id;


		function __construct()
		{
			global $conn;
			$this->conn = $conn;
		}

		function Save()	//Inserts new sawmill production data into database
		{
			try
			{
				$sql = $this->conn->prepare("INSERT INTO sawmill_maintenance VALUES (DEFAULT, ?, ?, ?)");
				$sql->bind_param('isi', $this->time, $this->note, $this->sawmill_production_id);
				$sql->execute();

				$this->id = $this->conn->insert_id;
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