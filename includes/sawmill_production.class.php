<?php
	
	include "config.php";

	class SawmillProduction
	{
		private $conn;
		public $id;
		public $date;
		public $datetime;
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

			$sql = $conn->prepare("SELECT invoice FROM sawmill_productions WHERE invoice = ?");
			$sql->bind_param('i', $invoice);
			$sql->execute();
			$result = $sql->get_result();

			$resultCheck = mysqli_num_rows($result);

			return $resultCheck >= 1;
		}

		public static function ExistsProductionWithID($id) //Checks if sawmill production with such ID exists
		{
			global $conn;

			$sql = $conn->prepare("SELECT id FROM sawmill_productions WHERE id = ?");
			$sql->bind_param('s', $id);
			$sql->execute();
			$result = $sql->get_result();

			$resultCheck = mysqli_num_rows($result);

			return $resultCheck >= 1;
		}

		public static function ExistsProductionWithInvoiceAndID($id, $invoice) //Checks if sawmill production with such invoice exists
		{
			global $conn;

			$sql = $conn->prepare("SELECT id FROM sawmill_productions WHERE id = ? AND invoice = ?");
			$sql->bind_param('si', $id, $invoice);
			$sql->execute();
			$result = $sql->get_result();

			$resultCheck = mysqli_num_rows($result);

			return $resultCheck >= 1;
		}

		public static function GetSawmillProductionData($id)	//Returns all productions data with ID
		{
			global $conn;

			$sql = $conn->prepare("SELECT sawmill_productions.*, 
								COUNT(sawmill_maintenance.sawmill_production_id) AS count 
								FROM sawmill_productions
								JOIN sawmill_maintenance
								ON sawmill_maintenance.sawmill_production_id = sawmill_productions.id
								WHERE sawmill_productions.id = ?");
			$sql->bind_param('s', $id);
			$sql->execute();
			$result = $sql->get_result();

			return mysqli_fetch_assoc($result);
		}

		public static function GetAllMaintenances($id) //Returns all sawmill production maintenances
		{
			global $conn;

			$sql = $conn->prepare("SELECT sawmill_maintenance.*  FROM sawmill_maintenance
									WHERE sawmill_maintenance.sawmill_production_id = ?");
			$sql->bind_param('s', $id);
			$sql->execute();
			$result = $sql->get_result();

			return mysqli_fetch_all($result, MYSQLI_ASSOC);
		}

		public static function GetSawmillProductionWorkersShift($id) //Gets sawmill productions workers shift
		{
			global $conn;

			$sql = $conn->prepare("SELECT DISTINCT employees.shift
									FROM sawmill_productions
									JOIN employees_sawmill_productions
									ON sawmill_productions.id = employees_sawmill_productions.sawmill_id
									JOIN employees
									ON employees_sawmill_productions.employee_id = employees.id
									WHERE sawmill_productions.id = ?");
			$sql->bind_param('s', $id);
			$sql->execute();
			$result = $sql->get_result();

			return mysqli_fetch_assoc($result);
		}

		public static function CurrentInvoiceExists($invoice, $id)	//Returns true if invoice from other production in DB already exists
		{
			global $conn;

			$sql = $conn->prepare("SELECT invoice FROM sawmill_productions WHERE invoice = ? AND id <> ?");
			$sql->bind_param('ss', $invoice, $id); //Binds parameter, transforms to string
			$sql->execute();
			$result = $sql->get_result();

			$resultCheck = mysqli_num_rows($result);

			return $resultCheck >= 1;
		}

		function Save()	//Inserts new sawmill production data into database
		{
			try
			{
				$sql = $this->conn->prepare("INSERT INTO sawmill_productions VALUES (DEFAULT, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
				$sql->bind_param('ssssiididdsi', $this->date, $this->datetime, $this->time_from, $this->time_to, $this->invoice, $this->beam_count, $this->beam_capacity, $this->lumber_count, $this->lumber_capacity, $this->percentage, $this->note, $this->beam_size_id);
				$sql->execute();

				$this->id = $this->conn->insert_id; //Sets object id
				$sql->close();
			}
			catch(mysqli_sql_exception $e)
			{	
				$_SESSION['error'] = "Radās kļūda ierakstot datus!";
				header("Location: /");
				exit();
			}
		}

		function Update()	//Updates existing sawmill production data
		{
			try
			{
				$sql = $this->conn->prepare("UPDATE sawmill_productions SET date = ?, time_from = ?, time_to = ?, invoice = ?, beam_count = ?, beam_capacity = ?, lumber_count = ?, lumber_capacity = ?, percentage = ?, note = ?, beam_size_id = ? WHERE sawmill_productions.id = ?");
				$sql->bind_param('sssiididdsis', $this->date, $this->time_from, $this->time_to, $this->invoice, $this->beam_count, $this->beam_capacity, $this->lumber_count, $this->lumber_capacity, $this->percentage, $this->note, $this->beam_size_id, $this->id);
				$sql->execute();
				$sql->close();
			}
			catch(mysqli_sql_exception $e)
			{	
				$_SESSION['error'] = "Radās kļūda ierakstot datus!";
				header("Location: /");
				exit();
			}
		}

		function Delete()	//Deletes sawmill production from database
		{
			try
			{
				$sql = $this->conn->prepare("DELETE FROM sawmill_productions WHERE sawmill_productions.id = ?");
				$sql->bind_param('s', $this->id);
				$sql->execute();
				$sql->close();
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