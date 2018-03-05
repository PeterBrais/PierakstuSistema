<?php
	
	include "config.php";

	class SawmillProduction
	{
		private $conn;
		public $id;
		public $date;
		public $time_from;
		public $time_to;
		public $invoice;
		public $beam_count;
		public $beam_capacity;
		public $lumber_count;
		public $lumber_capacity;
		public $percentage;
		public $note;
		public $beam_size_id;

		function __construct()
		{
			global $conn;
			$this->conn = $conn;
		}

		public static function ExistsInvoice($invoice)	//Finds if position already exists in atabase
		{
			global $conn;

			$sql = $conn->prepare("SELECT invoice FROM sawmill_productions WHERE invoice=?");
			$sql->bind_param('i', $invoice);
			$sql->execute();
			$result = $sql->get_result();

			$resultCheck = mysqli_num_rows($result);

			return $resultCheck >= 1;
		}

		function Save()	//Inserts new sawmill production data into database
		{
			try
			{
				$sql = $this->conn->prepare("INSERT INTO sawmill_productions VALUES (DEFAULT, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
				$sql->bind_param('sssiididdsi', $this->date, $this->time_from, $this->time_to, $this->invoice, $this->beam_count, $this->beam_capacity, $this->lumber_count, $this->lumber_capacity, $this->percentage, $this->note, $this->beam_size_id);
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