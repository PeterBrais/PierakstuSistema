<?php

require_once "C:/wamp64/www/pieraksts/includes/beam_size.class.php";
require_once "C:/wamp64/www/pieraksts/includes/sawmill_production.class.php";
require_once "C:/wamp64/www/pieraksts/includes/sawmill_maintenance.class.php";


use PHPUnit\Framework\TestCase;

//Test is divided in 3 parts:
//1. Arrange - prepares data
//2. Act - sets data
//3. Assert - tests data

class SawmillMaintenanceTests extends TestCase
{
	public static function ClearDatabase()
	{
		global $conn;
		$conn->query("DELETE FROM beam_sizes");	//Clears DB
		$conn->query("DELETE FROM sawmill_productions");	//Clears DB
		$conn->query("DELETE FROM sawmill_maintenance");	//Clears DB
	}

	/**
	* @test
	*/
	public function When_Save_New_SawmillProductionMaintenances_Then_It_Exists_In_Database()	//Tests Save function
	{
		SawmillMaintenanceTests::ClearDatabase();
		//Arrange
		$size = new BeamSize();
		$size->size = "5.308";

		$sawmillProduction = new SawmillProduction();
		$sawmillProduction->date = "2005-03-08";
		$sawmillProduction->datetime = "2005-03-08 14:30:00";
		$sawmillProduction->time_from = "06:00";
		$sawmillProduction->time_to = "14:30";
		$sawmillProduction->invoice = "1000";
		$sawmillProduction->beam_count = "506";
		$sawmillProduction->beam_capacity = "506.54";
		$sawmillProduction->lumber_count = "99";
		$sawmillProduction->lumber_capacity = "99.3";
		$sawmillProduction->percentage = "50.1";
		$sawmillProduction->note = NULL;

		$sawmillMaintenanceOne = new SawmillMaintenance;
		$sawmillMaintenanceOne->time = "20";
		$sawmillMaintenanceOne->note = "Mizotāja apkope";

		$sawmillMaintenanceTwo = new SawmillMaintenance;
		$sawmillMaintenanceTwo->time = "5";
		$sawmillMaintenanceTwo->note = NULL;

		//Act
		$size->Save();
		$sawmillProduction->beam_size_id = $size->id;
		$sawmillProduction->Save();
		$sawmillMaintenanceOne->sawmill_production_id = $sawmillProduction->id;
		$sawmillMaintenanceOne->Save();
		$sawmillMaintenanceTwo->sawmill_production_id = $sawmillProduction->id;
		$sawmillMaintenanceTwo->Save();

		//Assert
		global $conn;
		$sql = $conn->prepare("SELECT sawmill_maintenance.* FROM sawmill_maintenance WHERE sawmill_production_id = ?");
		$sql->bind_param('s', $sawmillProduction->id);
		$sql->execute();
		$result = $sql->get_result();
		$result = mysqli_fetch_all($result, MYSQLI_ASSOC);
		$this->assertEquals(2, count($result));

		$maintenances = array($sawmillMaintenanceOne->time, $sawmillMaintenanceTwo->time);
		$i = 0;
		foreach($result as $maintenance)
		{
			$this->assertEquals($maintenances[$i], $maintenance['time']);
			$i++;
		}
	}

	/**
	* @test
	*/
	public function When_Save_New_SawmillProductionMaintenances_Then_It_Can_Be_Deleted()	//Tests Delete function
	{
		SawmillMaintenanceTests::ClearDatabase();
		//Arrange
		$size = new BeamSize();
		$size->size = "3.427";

		$sawmillProduction = new SawmillProduction();
		$sawmillProduction->date = "2005-03-10";
		$sawmillProduction->datetime = "2005-03-10 23:00:00";
		$sawmillProduction->time_from = "06:00";
		$sawmillProduction->time_to = "14:30";
		$sawmillProduction->invoice = "1001";
		$sawmillProduction->beam_count = "506";
		$sawmillProduction->beam_capacity = "506.54";
		$sawmillProduction->lumber_count = "99";
		$sawmillProduction->lumber_capacity = "99.3";
		$sawmillProduction->percentage = "50.1";
		$sawmillProduction->note = NULL;

		$sawmillMaintenanceOne = new SawmillMaintenance;
		$sawmillMaintenanceOne->time = "20";
		$sawmillMaintenanceOne->note = "Cepurotāja tīrīšana";

		$sawmillMaintenanceTwo = new SawmillMaintenance;
		$sawmillMaintenanceTwo->time = "25";
		$sawmillMaintenanceTwo->note = "Posmu tīrīšana";

		//Act
		$size->Save();
		$sawmillProduction->beam_size_id = $size->id;
		$sawmillProduction->Save();
		$sawmillMaintenanceOne->sawmill_production_id = $sawmillProduction->id;
		$sawmillMaintenanceOne->Save();
		$sawmillMaintenanceTwo->sawmill_production_id = $sawmillProduction->id;
		$sawmillMaintenanceTwo->Save();

		$sawmillMaintenance = new SawmillMaintenance;
		$sawmillMaintenance->DeleteAllSawmillProductionMaintenances($sawmillProduction->id);

		//Assert
		global $conn;
		$sql = $conn->prepare("SELECT sawmill_maintenance.* FROM sawmill_maintenance WHERE sawmill_production_id = ?");
		$sql->bind_param('s', $sawmillProduction->id);
		$sql->execute();
		$result = $sql->get_result();
		$result = mysqli_fetch_all($result, MYSQLI_ASSOC);
		$this->assertEquals(0, count($result));
	}
}

?>