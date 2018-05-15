<?php

require_once "C:/wamp64/www/pieraksts/includes/manager.class.php";
require_once "C:/wamp64/www/pieraksts/includes/position.class.php";
require_once "C:/wamp64/www/pieraksts/includes/beam_size.class.php";
require_once "C:/wamp64/www/pieraksts/includes/employee.class.php";
require_once "C:/wamp64/www/pieraksts/includes/employee_position.class.php";
require_once "C:/wamp64/www/pieraksts/includes/sawmill_production.class.php";
require_once "C:/wamp64/www/pieraksts/includes/sorting_production.class.php";
require_once "C:/wamp64/www/pieraksts/includes/sorted_production.class.php";
require_once "C:/wamp64/www/pieraksts/includes/employees_sawmill_productions.class.php";
require_once "C:/wamp64/www/pieraksts/includes/employees_sorted_productions.class.php";
require_once "C:/wamp64/www/pieraksts/includes/sawmill_maintenance.class.php";
require_once "C:/wamp64/www/pieraksts/includes/working_times.class.php";
require_once "C:/wamp64/www/pieraksts/includes/times.class.php";

use PHPUnit\Framework\TestCase;

//Test is divided in 3 parts:
//1. Arrange - prepares data
//2. Act - sets data
//3. Assert - tests data

class ManagerTests extends TestCase
{
	public static function ClearDatabase()
	{
		global $conn;
		$conn->query("DELETE FROM positions");	//Clears DB
		$conn->query("DELETE FROM employees");	//Clears DB
		$conn->query("DELETE FROM beam_sizes");	//Clears DB
		$conn->query("DELETE FROM sawmill_productions");	//Clears DB
		$conn->query("DELETE FROM sawmill_maintenance");	//Clears DB
		$conn->query("DELETE FROM employees_sawmill_productions");	//Clears DB
		$conn->query("DELETE FROM working_times");	//Clears DB
		$conn->query("DELETE FROM times");	//Clears DB
		$conn->query("DELETE FROM sorting_productions");	//Clears DB
		$conn->query("DELETE FROM sorted_productions");	//Clears DB
		$conn->query("DELETE FROM employee_sorted_productions");	//Clears DB
	}

	/**
	* @test
	*/
	public function When_Save_New_Position_Then_List_All_Positions()	//Tests Positions function
	{
		ManagerTests::ClearDatabase();
		//Arrange
		$positionOne = new Position();
		$positionOne->name = "Vietnieks";

		$positionTwo = new Position();
		$positionTwo->name = "Valdes loceklis";

		//Act
		$positionOne->Save();
		$positionTwo->Save();

		//Assert
		$allPositions = Manager::Positions();
		$this->assertEquals(2, count($allPositions));

		$positions = array($positionOne->name, $positionTwo->name);
		$i = 0;
		foreach($allPositions as $position)
		{
			$this->assertEquals($positions[$i], $position['name']);
			$i++;
		}
	}

	/**
	* @test
	*/
	public function When_Save_New_Position_Then_Its_Data_Can_Be_Loaded_From_Database()	//Tests GetPositionData function
	{
		//Arrange
		$position = new Position();
		$position->name = "Sargs";

		//Act
		$position->Save();

		//Assert
		$positionData = Manager::GetPositionData($position->id);
		$this->assertEquals($position->name, $positionData['name']);
	}

	/**
	* @test
	*/
	public function When_Save_New_BeamSizes_Then_List_All_BeamSizes()	//Tests BeamSizes function
	{
		//Arrange
		$sizeOne = new BeamSize();
		$sizeOne->size = "3.140";

		$sizeTwo = new BeamSize();
		$sizeTwo->size = "2.154";

		//Act
		$sizeOne->Save();
		$sizeTwo->Save();

		//Assert
		$allSizes = Manager::BeamSizes();
		$this->assertEquals(2, count($allSizes));

		$sizes = array($sizeOne->size, $sizeTwo->size);
		$i = 0;
		foreach($allSizes as $size)
		{
			$this->assertEquals($sizes[$i], $size['size']);
			$i++;
		}
	}

	/**
	* @test
	*/
	public function When_Save_New_BeamSize_Then_Its_Data_Can_Be_Loaded_From_Database()	//Tests GetBeamSizeData function
	{
		//Arrange
		$size = new BeamSize();
		$size->size = "4.529";

		//Act
		$size->Save();

		//Assert
		$beamSizeData = Manager::GetBeamSizeData($size->id);
		$this->assertEquals($size->size, $beamSizeData['size']);
	}

	/**
	* @test
	*/
	public function When_Save_New_Employees_Then_List_All_Employees()	//Tests Employees function
	{
		//Arrange
		$employeeOne = new Employee();
		$employeeOne->name = "Biddy";
		$employeeOne->last_name = "Catheter";
		$employeeOne->person_id = "418294-41829";
		$employeeOne->place = "Zagetava";
		$employeeOne->shift = "1";
		$employeeOne->capacity_rate = "3.24";
		$employeeOne->hour_rate = "0.91";
		$employeeOne->working_from = "2014-08-24";
		$employeeOne->working_to = NULL;

		$employeeTwo = new Employee();
		$employeeTwo->name = "Hyena";
		$employeeTwo->last_name = "Pans";
		$employeeTwo->person_id = "393231-39323";
		$employeeTwo->place = "Skirotava";
		$employeeTwo->shift = NULL;
		$employeeTwo->capacity_rate = NULL;
		$employeeTwo->hour_rate = NULL;
		$employeeTwo->working_from = "2003-03-31";
		$employeeTwo->working_to = NULL;

		//Act
		$employeeOne->Save();
		$employeeTwo->Save();

		//Assert
		$allEmployees = Manager::Employees();
		$this->assertEquals(2, count($allEmployees));

		foreach($allEmployees as $employee)
		{
			if($employee['place'] == "Zagetava")
			{
				$this->assertEquals($employeeOne->name, $employee['name']);
			}
			else if($employee['place'] == "Skirotava")
			{
				$this->assertEquals($employeeTwo->name, $employee['name']);
			}
		}
	}

	/**
	* @test
	*/
	public function When_Save_New_Employees_And_Positions_Then_List_OF_EmployeePositions()	//Tests EmployeePositions function
	{
		//Arrange
		$employee = new Employee();
		$employee->name = "Delicious";
		$employee->last_name = "Procesor";
		$employee->person_id = "277526-27752";
		$employee->place = "Birojs";
		$employee->shift = NULL;
		$employee->capacity_rate = NULL;
		$employee->hour_rate = NULL;
		$employee->working_from = "2017-06-27";
		$employee->working_to = NULL;

		$positionOne = new Position();
		$positionOne->name = "Kozāģētavas vadītājs";

		$positionTwo = new Position();
		$positionTwo->name = "Vadītāja vietnieks";

		$positionThree = new Position();
		$positionThree->name = "Zāģētavas uzraugs";

		$employeePosition = new EmployeePosition();

		//Act
		$employee->Save();
		$positionOne->Save();
		$positionTwo->Save();
		$positionThree->Save();
		$employeePosition->employee_id = $employee->id;
		$employeePosition->position_id = $positionOne->id;
		$employeePosition->Save();
		$employeePosition->position_id = $positionTwo->id;
		$employeePosition->Save();

		//Assert
		$employeesPositions = Manager::EmployeePositions($employee->id);
		$this->assertEquals(2, count($employeesPositions));

		$positions = array($positionOne->name, $positionTwo->name);
		$i = 0;
		foreach($employeesPositions as $position)
		{
			$this->assertEquals($positions[$i], $position['name']);
			$i++;
		}
	}

	/**
	* @test
	*/
	public function When_Save_New_Employees_Then_List_AllShifts()	//Tests AllShifts function
	{
		//Arrange
		$employeeOne = new Employee();
		$employeeOne->name = "Cygnus";
		$employeeOne->last_name = "Rounding";
		$employeeOne->person_id = "508073-50807";
		$employeeOne->place = "Zagetava";
		$employeeOne->shift = "1";
		$employeeOne->capacity_rate = "3";
		$employeeOne->hour_rate = "0.93";
		$employeeOne->working_from = "2005-08-07";
		$employeeOne->working_to = NULL;

		$employeeTwo = new Employee();
		$employeeTwo->name = "Painted";
		$employeeTwo->last_name = "Bewitched";
		$employeeTwo->person_id = "641551-64155";
		$employeeTwo->place = "Zagetava";
		$employeeTwo->shift = "2";
		$employeeTwo->capacity_rate = "3.42";
		$employeeTwo->hour_rate = "0.92";
		$employeeTwo->working_from = "2006-01-15";
		$employeeTwo->working_to = NULL;

		$employeeThree = new Employee();
		$employeeThree->name = "Specified";
		$employeeThree->last_name = "Smiling";
		$employeeThree->person_id = "736818-73681";
		$employeeThree->place = "Skirotava";
		$employeeThree->shift = NULL;
		$employeeThree->capacity_rate = NULL;
		$employeeThree->hour_rate = NULL;
		$employeeThree->working_from = "2007-06-18";
		$employeeThree->working_to = NULL;

		//Act
		$employeeOne->Save();
		$employeeTwo->Save();
		$employeeThree->Save();

		//Assert
		$allShifts = Manager::AllShifts();
		$this->assertEquals(2, count($allShifts));

		$shifts = array("1", "2");
		$i = 0;
		foreach($allShifts as $shift)
		{
			$this->assertEquals($shifts[$i], $shift['shift']);
			$i++;
		}
	}

	/**
	* @test
	*/
	public function When_Save_New_Employees_Then_GetEmployeesByShift()	//Tests AllShifts function
	{
		//Arrange
		$employeeOne = new Employee();
		$employeeOne->name = "Known";
		$employeeOne->last_name = "Verticles";
		$employeeOne->person_id = "977766-97776";
		$employeeOne->place = "Zagetava";
		$employeeOne->shift = "1";
		$employeeOne->capacity_rate = "1.01";
		$employeeOne->hour_rate = "0.99";
		$employeeOne->working_from = "1995-06-01";
		$employeeOne->working_to = NULL;

		$employeeTwo = new Employee();
		$employeeTwo->name = "Hawking";
		$employeeTwo->last_name = "Producer";
		$employeeTwo->person_id = "419919-41991";
		$employeeTwo->place = "Zagetava";
		$employeeTwo->shift = "1";
		$employeeTwo->capacity_rate = "1.02";
		$employeeTwo->hour_rate = "0.98";
		$employeeTwo->working_from = "1995-06-02";
		$employeeTwo->working_to = "1995-06-30";

		$employeeThree = new Employee();
		$employeeThree->name = "Specified";
		$employeeThree->last_name = "Smiling";
		$employeeThree->person_id = "319794-31979";
		$employeeThree->place = "Zagetava";
		$employeeThree->shift = "2";
		$employeeThree->capacity_rate = "1.3";
		$employeeThree->hour_rate = "0.89";
		$employeeThree->working_from = "1995-05-23";
		$employeeThree->working_to = NULL;

		//Act
		$employeeOne->Save();
		$employeeTwo->Save();
		$employeeThree->Save();

		//Assert
		$allEmployees = Manager::GetEmployeesByShift("1", "1995-07");	//Only employeeOne
		$this->assertEquals(1, count($allEmployees));
		foreach($allEmployees as $employee)
		{
			$this->assertEquals($employeeOne->name, $employee['name']); //Only one row
		}
	}

	/**
	* @test
	*/
	public function When_Save_New_Employee_Then_ExistsShift_In_Database()	//Tests AllShifts function
	{
		//Arrange
		$employee = new Employee();
		$employee->name = "Shattered";
		$employee->last_name = "Flink";
		$employee->person_id = "960667-96066";
		$employee->place = "Zagetava";
		$employee->shift = "1";
		$employee->capacity_rate = "4.23";
		$employee->hour_rate = "9.32";
		$employee->working_from = "2009-06-27";
		$employee->working_to = NULL;

		//Act
		$employee->Save();

		//Assert
		$this->assertTrue(Manager::ExistsShift($employee->shift));
		$this->assertFalse(Manager::ExistsShift("3"));
	}

	/**
	* @test
	*/
	public function When_Save_New_SawmillProduction_Then_GetSawmillProductionsByDate()	//Tests GetSawmillProductionsByDate function
	{
		//Arrange
		$employee = new Employee();
		$employee->name = "Control";
		$employee->last_name = "Cholera";
		$employee->person_id = "823085-82308";
		$employee->place = "Zagetava";
		$employee->shift = "2";
		$employee->capacity_rate = "8.23";
		$employee->hour_rate = "8.03";
		$employee->working_from = "2005-08-23";
		$employee->working_to = NULL;

		$size = new BeamSize();
		$size->size = "1.234";

		$sawmillProductionOne = new SawmillProduction();
		$sawmillProductionOne->date = "1999-06-25";
		$sawmillProductionOne->datetime = "1999-06-25 23:01:50";
		$sawmillProductionOne->time_from = "12:30";
		$sawmillProductionOne->time_to = "23:00";
		$sawmillProductionOne->invoice = "1004";
		$sawmillProductionOne->beam_count = "506";
		$sawmillProductionOne->beam_capacity = "506.54";
		$sawmillProductionOne->lumber_count = "99";
		$sawmillProductionOne->lumber_capacity = "99.3";
		$sawmillProductionOne->percentage = "50.1";
		$sawmillProductionOne->note = NULL;

		$sawmillProductionTwo = new SawmillProduction();
		$sawmillProductionTwo->date = "1999-07-25";
		$sawmillProductionTwo->datetime = "1999-07-25 23:01:50";
		$sawmillProductionTwo->time_from = "12:30";
		$sawmillProductionTwo->time_to = "23:00";
		$sawmillProductionTwo->invoice = "1005";
		$sawmillProductionTwo->beam_count = "572";
		$sawmillProductionTwo->beam_capacity = "572.57";
		$sawmillProductionTwo->lumber_count = "79";
		$sawmillProductionTwo->lumber_capacity = "79.3";
		$sawmillProductionTwo->percentage = "52.7";
		$sawmillProductionTwo->note = NULL;

		//Act
		$employee->Save();
		$size->Save();
		$sawmillProductionOne->beam_size_id = $size->id;
		$sawmillProductionOne->Save();
		$sawmillProductionTwo->beam_size_id = $size->id;
		$sawmillProductionTwo->Save();
		$employeesSawmillProcutions = new EmployeeSawmillProductions();
		$employeesSawmillProcutions->employee_id = $employee->id;
		$employeesSawmillProcutions->sawmill_id = $sawmillProductionOne->id;
		$employeesSawmillProcutions->Save();
		$employeesSawmillProcutions->sawmill_id = $sawmillProductionTwo->id;
		$employeesSawmillProcutions->Save();

		//Assert
		$periodProductions = Manager::GetSawmillProductionsByDate("1999-06");
		$this->assertEquals(1, count($periodProductions));
		foreach($periodProductions as $production)
		{
			$this->assertEquals($sawmillProductionOne->date, $production['date']);
			$this->assertEquals($sawmillProductionOne->invoice, $production['invoice']);
		}
	}

	/**
	* @test
	*/
	public function When_Save_New_SawmillProductionMaintenances_Then_List_ProductionMaintenances()	//Tests ProductionMaintenances function
	{
		//Arrange
		$size = new BeamSize();
		$size->size = "6.256";

		$sawmillProduction = new SawmillProduction();
		$sawmillProduction->date = "1999-06-25";
		$sawmillProduction->datetime = "1999-06-25 23:01:50";
		$sawmillProduction->time_from = "12:30";
		$sawmillProduction->time_to = "23:00";
		$sawmillProduction->invoice = "1006";
		$sawmillProduction->beam_count = "506";
		$sawmillProduction->beam_capacity = "506.54";
		$sawmillProduction->lumber_count = "99";
		$sawmillProduction->lumber_capacity = "99.3";
		$sawmillProduction->percentage = "50.1";
		$sawmillProduction->note = NULL;

		$sawmillMaintenanceOne = new SawmillMaintenance;
		$sawmillMaintenanceOne->time = "10";
		$sawmillMaintenanceOne->note = "Ķēžū ieeļošana";

		$sawmillMaintenanceTwo = new SawmillMaintenance;
		$sawmillMaintenanceTwo->time = "15";
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
		$productionMaintenances = Manager::ProductionMaintenances($sawmillProduction->id);
		$this->assertEquals(2, count($productionMaintenances));
		$maintenances = array($sawmillMaintenanceOne->time, $sawmillMaintenanceTwo->time);
		$i = 0;
		foreach($productionMaintenances as $maintenance)
		{
			$this->assertEquals($maintenances[$i], $maintenance['time']);
			$i++;
		}
	}

	/**
	* @test
	*/
	public function When_Save_New_SawmillProduction_Then_GetAllSawmillProductionSummByDate()	//Tests GetAllSawmillProductionSummByDate function
	{
		//Arrange
		$size = new BeamSize();
		$size->size = "2.111";

		$sawmillProductionOne = new SawmillProduction();
		$sawmillProductionOne->date = "1998-06-15";
		$sawmillProductionOne->datetime = "1998-06-15 14:11:20";
		$sawmillProductionOne->time_from = "06:00";
		$sawmillProductionOne->time_to = "14:30";
		$sawmillProductionOne->invoice = "1007";
		$sawmillProductionOne->beam_count = "506";
		$sawmillProductionOne->beam_capacity = "506.54";
		$sawmillProductionOne->lumber_count = "99";
		$sawmillProductionOne->lumber_capacity = "99.3";
		$sawmillProductionOne->percentage = "50.1";
		$sawmillProductionOne->note = NULL;

		$sawmillProductionTwo = new SawmillProduction();
		$sawmillProductionTwo->date = "1998-06-28";
		$sawmillProductionTwo->datetime = "1998-06-28 23:01:40";
		$sawmillProductionTwo->time_from = "12:30";
		$sawmillProductionTwo->time_to = "23:00";
		$sawmillProductionTwo->invoice = "1008";
		$sawmillProductionTwo->beam_count = "572";
		$sawmillProductionTwo->beam_capacity = "572.57";
		$sawmillProductionTwo->lumber_count = "79";
		$sawmillProductionTwo->lumber_capacity = "79.3";
		$sawmillProductionTwo->percentage = "52.7";
		$sawmillProductionTwo->note = NULL;

		$sawmillMaintenance = new SawmillMaintenance;
		$sawmillMaintenance->time = "10";
		$sawmillMaintenance->note = "Siksnu nomaiņa";

		//Act
		$size->Save();
		$sawmillProductionOne->beam_size_id = $size->id;
		$sawmillProductionOne->Save();
		$sawmillProductionTwo->beam_size_id = $size->id;
		$sawmillProductionTwo->Save();
		$sawmillMaintenance->sawmill_production_id = $sawmillProductionOne->id;
		$sawmillMaintenance->Save();

		$beamCount = $sawmillProductionOne->beam_count + $sawmillProductionTwo->beam_count;
		$baemCapacity = $sawmillProductionOne->beam_capacity + $sawmillProductionTwo->beam_capacity;

		//Assert
		$totals = Manager::GetAllSawmillProductionSummByDate("1998-06");
		$this->assertEquals($beamCount, $totals['beam_count']);
		$this->assertEquals($baemCapacity, $totals['beam_capacity']);
		$this->assertEquals($sawmillMaintenance->time, $totals['maintenance']);
	}

	/**
	* @test
	*/
	public function When_Save_New_Employees_Then_GetSawmillEmployeesByDate()	//Tests GetSawmillEmployeesByDate function
	{
		//Arrange
		$employeeOne = new Employee();
		$employeeOne->name = "Potable";
		$employeeOne->last_name = "Grape";
		$employeeOne->person_id = "929123-92912";
		$employeeOne->place = "Zagetava";
		$employeeOne->shift = "1";
		$employeeOne->capacity_rate = "1.01";
		$employeeOne->hour_rate = "0.99";
		$employeeOne->working_from = "1992-01-01";
		$employeeOne->working_to = NULL;

		$employeeTwo = new Employee();
		$employeeTwo->name = "Cheddats";
		$employeeTwo->last_name = "Unioce";
		$employeeTwo->person_id = "916217-91621";
		$employeeTwo->place = "Zagetava";
		$employeeTwo->shift = "2";
		$employeeTwo->capacity_rate = "1.02";
		$employeeTwo->hour_rate = "0.98";
		$employeeTwo->working_from = "1992-01-02";
		$employeeTwo->working_to = "1992-03-30";

		$employeeThree = new Employee();
		$employeeThree->name = "Juciy";
		$employeeThree->last_name = "Hellix";
		$employeeThree->person_id = "553328-55332";
		$employeeThree->place = "Zagetava";
		$employeeThree->shift = "1";
		$employeeThree->capacity_rate = "1.3";
		$employeeThree->hour_rate = "0.89";
		$employeeThree->working_from = "1992-02-11";
		$employeeThree->working_to = NULL;

		//Act
		$employeeOne->Save();
		$employeeTwo->Save();
		$employeeThree->Save();

		//Assert
		$allEmployees = Manager::GetSawmillEmployeesByDate("1992-04");
		$this->assertEquals(2, count($allEmployees));	//Only employeeOne and Three

		$employees = array($employeeOne->person_id, $employeeThree->person_id);
		$i = 0;
		foreach($allEmployees as $employee)
		{
			$this->assertEquals($employees[$i], $employee['person_id']);
			$i++;
		}
	}

	/**
	* @test
	*/
	public function When_Save_New_SawmillProductions_Then_GetEmployeeProductionsMaintenances()	//Tests GetEmployeeProductionsMaintenances function
	{
		//Arrange
		$employee = new Employee();
		$employee->name = "Pasta";
		$employee->last_name = "Throne";
		$employee->person_id = "149536-14953";
		$employee->place = "Zagetava";
		$employee->shift = "1";
		$employee->capacity_rate = "1.01";
		$employee->hour_rate = "0.99";
		$employee->working_from = "2000-01-01";
		$employee->working_to = NULL;

		$size = new BeamSize();
		$size->size = "2.111";

		$sawmillProductionOne = new SawmillProduction();
		$sawmillProductionOne->date = "2000-01-10";
		$sawmillProductionOne->datetime = "2000-01-10 14:11:20";
		$sawmillProductionOne->time_from = "06:00";
		$sawmillProductionOne->time_to = "14:30";
		$sawmillProductionOne->invoice = "1009";
		$sawmillProductionOne->beam_count = "506";
		$sawmillProductionOne->beam_capacity = "506.54";
		$sawmillProductionOne->lumber_count = "99";
		$sawmillProductionOne->lumber_capacity = "99.3";
		$sawmillProductionOne->percentage = "50.1";
		$sawmillProductionOne->note = NULL;

		$sawmillProductionTwo = new SawmillProduction();
		$sawmillProductionTwo->date = "2000-01-11";
		$sawmillProductionTwo->datetime = "2000-01-11 23:01:40";
		$sawmillProductionTwo->time_from = "12:30";
		$sawmillProductionTwo->time_to = "23:00";
		$sawmillProductionTwo->invoice = "1010";
		$sawmillProductionTwo->beam_count = "572";
		$sawmillProductionTwo->beam_capacity = "572.57";
		$sawmillProductionTwo->lumber_count = "79";
		$sawmillProductionTwo->lumber_capacity = "79.3";
		$sawmillProductionTwo->percentage = "52.7";
		$sawmillProductionTwo->note = NULL;

		$sawmillMaintenanceOne = new SawmillMaintenance;
		$sawmillMaintenanceOne->time = "40";
		$sawmillMaintenanceOne->note = "Brāķu šķirošna";

		$sawmillMaintenanceTwo = new SawmillMaintenance;
		$sawmillMaintenanceTwo->time = "15";
		$sawmillMaintenanceTwo->note = NULL;

		$working_times = new WorkingTimes();
		$working_times->date = $sawmillProductionOne->date;
		$working_times->datetime = $sawmillProductionOne->datetime;
		$working_times->invoice = $sawmillProductionOne->invoice;
		$working_times->working_hours = "8";

		//Act
		$size->Save();
		$employee->Save();
		$sawmillProductionOne->beam_size_id = $size->id;
		$sawmillProductionOne->Save();
		$sawmillProductionTwo->beam_size_id = $size->id;
		$sawmillProductionTwo->Save();
		$sawmillMaintenanceOne->sawmill_production_id = $sawmillProductionOne->id;
		$sawmillMaintenanceOne->Save();
		$sawmillMaintenanceTwo->sawmill_production_id = $sawmillProductionTwo->id;
		$sawmillMaintenanceTwo->Save();
		$working_times->employee_id = $employee->id;
		$working_times->Save();
		$employeesSawmillProcutions = new EmployeeSawmillProductions();
		$employeesSawmillProcutions->employee_id = $employee->id;
		$employeesSawmillProcutions->sawmill_id = $sawmillProductionOne->id;
		$employeesSawmillProcutions->Save();

		//Assert
		$maintenances = Manager::GetEmployeeProductionsMaintenances("2000-01", $employee->id);
		$this->assertEquals($maintenances['maintenance'], $sawmillMaintenanceOne->time);
	}

	/**
	* @test
	*/
	public function When_Save_New_SawmillProductions_Then_GetEmployeeProductionsCapacity()	//Tests GetEmployeeProductionsCapacity function
	{
		//Arrange
		$employee = new Employee();
		$employee->name = "Trowel";
		$employee->last_name = "Creative";
		$employee->person_id = "399240-39924";
		$employee->place = "Zagetava";
		$employee->shift = "2";
		$employee->capacity_rate = "1.01";
		$employee->hour_rate = "0.99";
		$employee->working_from = "2001-02-13";
		$employee->working_to = NULL;

		$size = new BeamSize();
		$size->size = "0.924";

		$sawmillProductionOne = new SawmillProduction();
		$sawmillProductionOne->date = "2001-03-10";
		$sawmillProductionOne->datetime = "2001-03-10 14:12:00";
		$sawmillProductionOne->time_from = "06:00";
		$sawmillProductionOne->time_to = "14:30";
		$sawmillProductionOne->invoice = "1011";
		$sawmillProductionOne->beam_count = "506";
		$sawmillProductionOne->beam_capacity = "506.54";
		$sawmillProductionOne->lumber_count = "99";
		$sawmillProductionOne->lumber_capacity = "99.3";
		$sawmillProductionOne->percentage = "50.1";
		$sawmillProductionOne->note = NULL;

		$sawmillProductionTwo = new SawmillProduction();
		$sawmillProductionTwo->date = "2001-03-11";
		$sawmillProductionTwo->datetime = "2001-03-11 23:14:00";
		$sawmillProductionTwo->time_from = "12:30";
		$sawmillProductionTwo->time_to = "23:00";
		$sawmillProductionTwo->invoice = "1012";
		$sawmillProductionTwo->beam_count = "572";
		$sawmillProductionTwo->beam_capacity = "572.57";
		$sawmillProductionTwo->lumber_count = "79";
		$sawmillProductionTwo->lumber_capacity = "79.3";
		$sawmillProductionTwo->percentage = "52.7";
		$sawmillProductionTwo->note = NULL;

		$working_times = new WorkingTimes();
		$working_times->working_hours = "8";

		//Act
		$size->Save();
		$employee->Save();
		$sawmillProductionOne->beam_size_id = $size->id;
		$sawmillProductionOne->Save();
		$sawmillProductionTwo->beam_size_id = $size->id;
		$sawmillProductionTwo->Save();
		$working_times->date = $sawmillProductionOne->date;
		$working_times->datetime = $sawmillProductionOne->datetime;
		$working_times->invoice = $sawmillProductionOne->invoice;
		$working_times->employee_id = $employee->id;
		$working_times->Save();
		$working_times->date = $sawmillProductionTwo->date;
		$working_times->datetime = $sawmillProductionTwo->datetime;
		$working_times->invoice = $sawmillProductionTwo->invoice;
		$working_times->employee_id = $employee->id;
		$working_times->Save();
		$employeesSawmillProcutions = new EmployeeSawmillProductions();
		$employeesSawmillProcutions->employee_id = $employee->id;
		$employeesSawmillProcutions->sawmill_id = $sawmillProductionOne->id;
		$employeesSawmillProcutions->Save();
		$employeesSawmillProcutions->sawmill_id = $sawmillProductionTwo->id;
		$employeesSawmillProcutions->Save();

		//Assert
		$capacities = Manager::GetEmployeeProductionsCapacity("2001-03", $employee->id);
		$this->assertEquals(2, count($capacities));

		$productions = array($sawmillProductionOne->lumber_capacity, $sawmillProductionTwo->lumber_capacity);
		$i = 0;
		foreach($capacities as $capacity)
		{
			$this->assertEquals($productions[$i], $capacity['lumber_capacity']);
			$this->assertEquals($working_times->working_hours, $capacity['working_hours']);
			$i++;
		}
	}

	/**
	* @test
	*/
	public function When_Save_New_SawmillProductions_Then_GetOperatorsAndAssistantsFromProduction()	//Tests GetOperatorsAndAssistantsFromProduction function
	{
		//Arrange
		$employeeOne = new Employee();
		$employeeOne->name = "Throne";
		$employeeOne->last_name = "Trovel";
		$employeeOne->person_id = "402025-40202";
		$employeeOne->place = "Zagetava";
		$employeeOne->shift = "1";
		$employeeOne->capacity_rate = "1.01";
		$employeeOne->hour_rate = "0.99";
		$employeeOne->working_from = "2018-05-03";
		$employeeOne->working_to = NULL;

		$employeeTwo = new Employee();
		$employeeTwo->name = "Argand";
		$employeeTwo->last_name = "Atomic";
		$employeeTwo->person_id = "261989-26198";
		$employeeTwo->place = "Zagetava";
		$employeeTwo->shift = "1";
		$employeeTwo->capacity_rate = "1.02";
		$employeeTwo->hour_rate = "0.98";
		$employeeTwo->working_from = "2018-05-03";
		$employeeTwo->working_to = NULL;

		$employeeThree = new Employee();
		$employeeThree->name = "Feminie";
		$employeeThree->last_name = "Handy";
		$employeeThree->person_id = "784043-78404";
		$employeeThree->place = "Zagetava";
		$employeeThree->shift = "1";
		$employeeThree->capacity_rate = "1.3";
		$employeeThree->hour_rate = "0.89";
		$employeeThree->working_from = "2018-05-03";
		$employeeThree->working_to = NULL;

		$size = new BeamSize();
		$size->size = "0.202";

		$sawmillProduction = new SawmillProduction();
		$sawmillProduction->date = "2018-05-10";
		$sawmillProduction->datetime = "2018-05-10 14:30:35";
		$sawmillProduction->time_from = "06:00";
		$sawmillProduction->time_to = "14:30";
		$sawmillProduction->invoice = "1013";
		$sawmillProduction->beam_count = "506";
		$sawmillProduction->beam_capacity = "506.54";
		$sawmillProduction->lumber_count = "99";
		$sawmillProduction->lumber_capacity = "99.3";
		$sawmillProduction->percentage = "50.1";
		$sawmillProduction->note = NULL;

		$working_times = new WorkingTimes();
		$working_times->working_hours = "8";

		$employeesSawmillProcutions = new EmployeeSawmillProductions();

		$positionOne = new Position();
		$positionOne->name = "Pakošanas operators";
		$positionTwo = new Position();
		$positionTwo->name = "Zāģēšanas iecirkņa palīgstrādnieks";
		$positionThree = new Position();
		$positionThree->name = "Zāģētājs";

		$employeePosition = new EmployeePosition();

		//Act
		$size->Save();
		$employeeOne->Save();
		$employeeTwo->Save();
		$employeeThree->Save();
		$sawmillProduction->beam_size_id = $size->id;
		$sawmillProduction->Save();
		$working_times->date = $sawmillProduction->date;
		$working_times->datetime = $sawmillProduction->datetime;
		$working_times->invoice = $sawmillProduction->invoice;
		$working_times->employee_id = $employeeOne->id;
		$working_times->Save();
		$working_times->employee_id = $employeeTwo->id;
		$working_times->Save();
		$working_times->employee_id = $employeeThree->id;
		$working_times->Save();
		$employeesSawmillProcutions->employee_id = $employeeOne->id;
		$employeesSawmillProcutions->sawmill_id = $sawmillProduction->id;
		$employeesSawmillProcutions->Save();
		$employeesSawmillProcutions->employee_id = $employeeTwo->id;
		$employeesSawmillProcutions->Save();
		$employeesSawmillProcutions->employee_id = $employeeThree->id;
		$employeesSawmillProcutions->Save();
		$positionOne->Save();
		$positionTwo->Save();
		$positionThree->Save();
		$employeePosition->employee_id = $employeeOne->id;
		$employeePosition->position_id = $positionOne->id;
		$employeePosition->Save();
		$employeePosition->employee_id = $employeeTwo->id;
		$employeePosition->position_id = $positionTwo->id;
		$employeePosition->Save();
		$employeePosition->employee_id = $employeeThree->id;
		$employeePosition->position_id = $positionThree->id;
		$employeePosition->Save();

		//Assert
		$employees = Manager::GetOperatorsAndAssistantsFromProduction($sawmillProduction->id, $employeeThree->shift, "2018-05");
		$this->assertEquals(2, $employees['emp_count']);
	}

	/**
	* @test
	*/
	public function When_Save_New_SawmillProductions_Then_GetNonAttendedEmployeeCapacityRatesFromProduction()	//Tests GetNonAttendedEmployeeCapacityRatesFromProduction function
	{
		//Arrange
		$employeeOne = new Employee();
		$employeeOne->name = "Troel";
		$employeeOne->last_name = "Creative";
		$employeeOne->person_id = "337350-33735";
		$employeeOne->place = "Zagetava";
		$employeeOne->shift = "2";
		$employeeOne->capacity_rate = "1.01";
		$employeeOne->hour_rate = "0.99";
		$employeeOne->working_from = "2018-03-05";
		$employeeOne->working_to = NULL;

		$employeeTwo = new Employee();
		$employeeTwo->name = "Cambodian";
		$employeeTwo->last_name = "Hezelnuts";
		$employeeTwo->person_id = "247403-24740";
		$employeeTwo->place = "Zagetava";
		$employeeTwo->shift = "2";
		$employeeTwo->capacity_rate = "1.02";
		$employeeTwo->hour_rate = "0.98";
		$employeeTwo->working_from = "2018-03-05";
		$employeeTwo->working_to = NULL;

		$employeeThree = new Employee();
		$employeeThree->name = "Peat";
		$employeeThree->last_name = "Colony";
		$employeeThree->person_id = "418915-41891";
		$employeeThree->place = "Zagetava";
		$employeeThree->shift = "2";
		$employeeThree->capacity_rate = "1.3";
		$employeeThree->hour_rate = "0.89";
		$employeeThree->working_from = "2018-03-05";
		$employeeThree->working_to = NULL;

		$size = new BeamSize();
		$size->size = "7.357";

		$sawmillProduction = new SawmillProduction();
		$sawmillProduction->date = "2018-03-10";
		$sawmillProduction->datetime = "2018-03-10 13:40:54";
		$sawmillProduction->time_from = "06:00";
		$sawmillProduction->time_to = "14:30";
		$sawmillProduction->invoice = "1014";
		$sawmillProduction->beam_count = "506";
		$sawmillProduction->beam_capacity = "506.54";
		$sawmillProduction->lumber_count = "99";
		$sawmillProduction->lumber_capacity = "99.3";
		$sawmillProduction->percentage = "50.1";
		$sawmillProduction->note = NULL;

		$times = new Times();
		$times->vacation = "A";
		$times->sick_leave = NULL;
		$times->nonattendance = NULL;
		$times->pregnancy = NULL;

		$employeesSawmillProcutions = new EmployeeSawmillProductions();

		//Act
		$size->Save();
		$employeeOne->Save();
		$employeeTwo->Save();
		$employeeThree->Save();
		$sawmillProduction->beam_size_id = $size->id;
		$sawmillProduction->Save();
		$times->date = $sawmillProduction->date;
		$times->datetime = $sawmillProduction->datetime;
		$times->invoice = $sawmillProduction->invoice; 
		$times->employee_id = $employeeThree->id;	//Only third employee
		$times->Save();
		$employeesSawmillProcutions->employee_id = $employeeOne->id;
		$employeesSawmillProcutions->sawmill_id = $sawmillProduction->id;
		$employeesSawmillProcutions->Save();
		$employeesSawmillProcutions->employee_id = $employeeTwo->id;
		$employeesSawmillProcutions->Save();
		$employeesSawmillProcutions->employee_id = $employeeThree->id;
		$employeesSawmillProcutions->Save();

		//Assert
		$rates = Manager::GetNonAttendedEmployeeCapacityRatesFromProduction($sawmillProduction->id, $employeeThree->shift, "2018-03");
		$this->assertEquals($employeeThree->capacity_rate, $rates['rates']);
	}

	/**
	* @test
	*/
	public function When_Save_New_SawmillProductions_Then_GetEmployeeProductionsDaysWorked()	//Tests GetEmployeeProductionsDaysWorked function
	{
		//Arrange
		$employee = new Employee();
		$employee->name = "Peat";
		$employee->last_name = "Jay";
		$employee->person_id = "117736-11773";
		$employee->place = "Zagetava";
		$employee->shift = "1";
		$employee->capacity_rate = "1.01";
		$employee->hour_rate = "0.99";
		$employee->working_from = "2000-01-01";
		$employee->working_to = NULL;

		$size = new BeamSize();
		$size->size = "1.773";

		$sawmillProduction = new SawmillProduction();
		$sawmillProduction->date = "2011-07-03";
		$sawmillProduction->datetime = "2011-07-03 14:12:00";
		$sawmillProduction->time_from = "06:00";
		$sawmillProduction->time_to = "14:30";
		$sawmillProduction->invoice = "1015";
		$sawmillProduction->beam_count = "506";
		$sawmillProduction->beam_capacity = "506.54";
		$sawmillProduction->lumber_count = "99";
		$sawmillProduction->lumber_capacity = "99.3";
		$sawmillProduction->percentage = "50.1";
		$sawmillProduction->note = NULL;

		$working_times = new WorkingTimes();
		$working_times->working_hours = "8";

		//Act
		$size->Save();
		$employee->Save();
		$sawmillProduction->beam_size_id = $size->id;
		$sawmillProduction->Save();
		$working_times->date = $sawmillProduction->date;
		$working_times->datetime = $sawmillProduction->datetime;
		$working_times->invoice = $sawmillProduction->invoice;
		$working_times->employee_id = $employee->id;
		$working_times->Save();
		$employeesSawmillProcutions = new EmployeeSawmillProductions();
		$employeesSawmillProcutions->employee_id = $employee->id;
		$employeesSawmillProcutions->sawmill_id = $sawmillProduction->id;
		$employeesSawmillProcutions->Save();

		//Assert
		$days = Manager::GetEmployeeProductionsDaysWorked("2011-07", $employee->id);
		$this->assertEquals(1, $days['working']);
	}

	/**
	* @test
	*/
	public function When_Save_New_SawmillProductions_Then_GetEmployeeProductionsDaysNonWorked()	//Tests GetEmployeeProductionsDaysNonWorked function
	{
		//Arrange
		$employee = new Employee();
		$employee->name = "Waffle";
		$employee->last_name = "Strand";
		$employee->person_id = "303903-30390";
		$employee->place = "Zagetava";
		$employee->shift = "2";
		$employee->capacity_rate = "1.01";
		$employee->hour_rate = "0.99";
		$employee->working_from = "2000-01-01";
		$employee->working_to = NULL;

		$size = new BeamSize();
		$size->size = "1.390";

		$sawmillProduction = new SawmillProduction();
		$sawmillProduction->date = "2009-10-30";
		$sawmillProduction->datetime = "2009-10-30 14:14:14";
		$sawmillProduction->time_from = "06:00";
		$sawmillProduction->time_to = "14:30";
		$sawmillProduction->invoice = "1016";
		$sawmillProduction->beam_count = "506";
		$sawmillProduction->beam_capacity = "506.54";
		$sawmillProduction->lumber_count = "99";
		$sawmillProduction->lumber_capacity = "99.3";
		$sawmillProduction->percentage = "50.1";
		$sawmillProduction->note = NULL;

		$times = new Times();
		$times->vacation = NULL;
		$times->sick_leave = NULL;
		$times->nonattendance = "N";
		$times->pregnancy = NULL;

		//Act
		$size->Save();
		$employee->Save();
		$sawmillProduction->beam_size_id = $size->id;
		$sawmillProduction->Save();
		$times->date = $sawmillProduction->date;
		$times->datetime = $sawmillProduction->datetime;
		$times->invoice = $sawmillProduction->invoice; 
		$times->employee_id = $employee->id;
		$times->Save();
		$employeesSawmillProcutions = new EmployeeSawmillProductions();
		$employeesSawmillProcutions->employee_id = $employee->id;
		$employeesSawmillProcutions->sawmill_id = $sawmillProduction->id;
		$employeesSawmillProcutions->Save();

		//Assert
		$badDays = Manager::GetEmployeeProductionsDaysNonWorked("2009-10", $employee->id);
		foreach($badDays as $day)
		{
			$this->assertEquals($times->nonattendance, $day['nonattendace']);	//Only One
		}
	}

	/**
	* @test
	*/
	public function When_Save_New_Employees_Then_GetSortingEmployees()	//Tests GetSortingEmployees function
	{
		ManagerTests::ClearDatabase();
		//Arrange
		$employeeOne = new Employee();
		$employeeOne->name = "Sad";
		$employeeOne->last_name = "Meosis";
		$employeeOne->person_id = "227017-22701";
		$employeeOne->place = "Skirotava";
		$employeeOne->shift = NULL;
		$employeeOne->capacity_rate = NULL;
		$employeeOne->hour_rate = NULL;
		$employeeOne->working_from = "2010-01-01";
		$employeeOne->working_to = NULL;

		$employeeTwo = new Employee();
		$employeeTwo->name = "Circle";
		$employeeTwo->last_name = "Fixed";
		$employeeTwo->person_id = "171616-17161";
		$employeeTwo->place = "Skirotava";
		$employeeTwo->shift = NULL;
		$employeeTwo->capacity_rate = NULL;
		$employeeTwo->hour_rate = NULL;
		$employeeTwo->working_from = "2010-01-01";
		$employeeTwo->working_to = "2010-02-10";

		$employeeThree = new Employee();
		$employeeThree->name = "Foamy";
		$employeeThree->last_name = "Snow";
		$employeeThree->person_id = "596505-59650";
		$employeeThree->place = "Skirotava";
		$employeeThree->shift = NULL;
		$employeeThree->capacity_rate = NULL;
		$employeeThree->hour_rate = NULL;
		$employeeThree->working_from = "2010-01-01";
		$employeeThree->working_to = NULL;

		//Act
		$employeeOne->Save();
		$employeeTwo->Save();
		$employeeThree->Save();

		//Assert
		$allEmployees = Manager::GetSortingEmployees("2010-03");
		$this->assertEquals(2, count($allEmployees));	//Only employeeOne and Three

		$employees = array($employeeOne->person_id, $employeeThree->person_id);
		$i = 0;
		foreach($allEmployees as $employee)
		{
			$this->assertEquals($employees[$i], $employee['person_id']);
			$i++;
		}
	}

	/**
	* @test
	*/
	public function When_Save_New_SortingProductions_Then_GetSortingProductionsByInvoice()	//Tests GetSortingProductionsByInvoice function
	{
		//Arrange
		$sortingProductionOne = new SortingProduction();
		$sortingProductionOne->date = "2010-01-01";
		$sortingProductionOne->datetime = "2010-01-01 12:30:22";
		$sortingProductionOne->time_from = "06:00";
		$sortingProductionOne->time_to = "14:30";
		$sortingProductionOne->invoice = "1002";
		$sortingProductionOne->thickness = "42";
		$sortingProductionOne->width = "35";
		$sortingProductionOne->length = "3800";
		$sortingProductionOne->count = "735";
		$sortingProductionOne->capacity = "534.13";
		$sortingProductionOne->defect_count = "1";
		$sortingProductionOne->reserved = 0;

		$sortingProductionTwo = new SortingProduction();
		$sortingProductionTwo->date = "2010-01-02";
		$sortingProductionTwo->datetime = "2010-01-02 12:31:20";
		$sortingProductionTwo->time_from = "06:00";
		$sortingProductionTwo->time_to = "14:30";
		$sortingProductionTwo->invoice = "1003";
		$sortingProductionTwo->thickness = "42";
		$sortingProductionTwo->width = "35";
		$sortingProductionTwo->length = "3800";
		$sortingProductionTwo->count = "735";
		$sortingProductionTwo->capacity = "534.13";
		$sortingProductionTwo->defect_count = NULL;
		$sortingProductionTwo->reserved = 0;

		$sortingProductionThree = new SortingProduction();
		$sortingProductionThree->date = "2010-02-01";
		$sortingProductionThree->datetime = "2010-02-01 15:41:22";
		$sortingProductionThree->time_from = "06:00";
		$sortingProductionThree->time_to = "14:30";
		$sortingProductionThree->invoice = "1004";
		$sortingProductionThree->thickness = "42";
		$sortingProductionThree->width = "35";
		$sortingProductionThree->length = "3800";
		$sortingProductionThree->count = "735";
		$sortingProductionThree->capacity = "534.13";
		$sortingProductionThree->defect_count = NULL;
		$sortingProductionThree->reserved = 0;

		//Act
		$sortingProductionOne->Save();
		$sortingProductionTwo->Save();
		$sortingProductionThree->Save();

		//Assert
		$invoices = Manager::GetSortingProductionsByInvoice("2010-01");
		$this->assertEquals(2, count($invoices));	//Only first and second

		$productions = array($sortingProductionOne->invoice, $sortingProductionTwo->invoice);
		$i = 0;
		foreach($invoices as $invoice)
		{
			$this->assertEquals($productions[$i], $invoice['invoice']);
			$i++;
		}
	}

	/**
	* @test
	*/
	public function When_Save_New_SortingProductions_Then_GetSortingProductions()	//Tests GetSortingProductions function
	{
		//Arrange
		$invoice = "1005";

		$sortingProductionOne = new SortingProduction();
		$sortingProductionOne->date = "2010-01-01";
		$sortingProductionOne->datetime = "2010-01-01 12:30:22";
		$sortingProductionOne->time_from = "06:00";
		$sortingProductionOne->time_to = "14:30";
		$sortingProductionOne->invoice = $invoice;
		$sortingProductionOne->thickness = "42";
		$sortingProductionOne->width = "35";
		$sortingProductionOne->length = "3800";
		$sortingProductionOne->count = "735";
		$sortingProductionOne->capacity = "534.13";
		$sortingProductionOne->defect_count = "1";
		$sortingProductionOne->reserved = 0;

		$sortingProductionTwo = new SortingProduction();
		$sortingProductionTwo->date = "2010-01-02";
		$sortingProductionTwo->datetime = "2010-01-02 12:31:20";
		$sortingProductionTwo->time_from = "06:00";
		$sortingProductionTwo->time_to = "14:30";
		$sortingProductionTwo->invoice = $invoice;
		$sortingProductionTwo->thickness = "42";
		$sortingProductionTwo->width = "35";
		$sortingProductionTwo->length = "3800";
		$sortingProductionTwo->count = "735";
		$sortingProductionTwo->capacity = "534.13";
		$sortingProductionTwo->defect_count = NULL;
		$sortingProductionTwo->reserved = 0;

		//Act
		$sortingProductionOne->Save();
		$sortingProductionTwo->Save();

		//Assert
		$allInvoicesProductions = Manager::GetSortingProductions($invoice);
		$this->assertEquals(2, count($allInvoicesProductions));	//Only first and second

		$productions = array($sortingProductionOne->width, $sortingProductionTwo->width);
		$i = 0;
		foreach($allInvoicesProductions as $production)
		{
			$this->assertEquals($productions[$i], $production['width']);
			$i++;
		}
	}

	/**
	* @test
	*/
	public function When_Save_New_Employees_Then_GetSortingEmployeesByDate()	//Tests GetSortingEmployeesByDate function
	{
		ManagerTests::ClearDatabase();
		//Arrange
		$employeeOne = new Employee();
		$employeeOne->name = "Excellent";
		$employeeOne->last_name = "Functions";
		$employeeOne->person_id = "155259-15525";
		$employeeOne->place = "Skirotava";
		$employeeOne->shift = NULL;
		$employeeOne->capacity_rate = NULL;
		$employeeOne->hour_rate = NULL;
		$employeeOne->working_from = "2009-01-01";
		$employeeOne->working_to = NULL;

		$employeeTwo = new Employee();
		$employeeTwo->name = "Gleeful";
		$employeeTwo->last_name = "Posh";
		$employeeTwo->person_id = "686499-68649";
		$employeeTwo->place = "Skirotava";
		$employeeTwo->shift = NULL;
		$employeeTwo->capacity_rate = NULL;
		$employeeTwo->hour_rate = NULL;
		$employeeTwo->working_from = "2009-01-01";
		$employeeTwo->working_to = "2010-02-10";

		$employeeThree = new Employee();
		$employeeThree->name = "Ungentle";
		$employeeThree->last_name = "Cheater";
		$employeeThree->person_id = "576042-57604";
		$employeeThree->place = "Birojs";
		$employeeThree->shift = NULL;
		$employeeThree->capacity_rate = NULL;
		$employeeThree->hour_rate = NULL;
		$employeeThree->working_from = "2009-01-01";
		$employeeThree->working_to = NULL;

		//Act
		$employeeOne->Save();
		$employeeTwo->Save();
		$employeeThree->Save();

		//Assert
		$allEmployees = Manager::GetSortingEmployeesByDate("2011-09");
		$this->assertEquals(1, count($allEmployees));	//Only employeeOne

		foreach($allEmployees as $employee)
		{
			$this->assertEquals($employeeOne->last_name, $employee['last_name']);
		}
	}

	/**
	* @test
	*/
	public function When_Save_New_SortingProduction_Then_GetAllSortingProductionSummByDate()	//Tests GetAllSortingProductionSummByDate function
	{
		//Arrange
		$sortingProduction = new SortingProduction();
		$sortingProduction->date = "2018-02-10";
		$sortingProduction->datetime = "2018-02-10 12:00:00";
		$sortingProduction->time_from = "06:00";
		$sortingProduction->time_to = "14:30";
		$sortingProduction->invoice = "1006";
		$sortingProduction->thickness = "50";
		$sortingProduction->width = "125";
		$sortingProduction->length = "4800";
		$sortingProduction->count = "700";
		$sortingProduction->capacity = "700.255";
		$sortingProduction->defect_count = NULL;
		$sortingProduction->reserved = 0;
		
		$sortedProductionOne = new SortedProduction();
		$sortedProductionOne->type = "G";
		$sortedProductionOne->count = "500";
		$sortedProductionOne->thickness = "42";
		$sortedProductionOne->width = "35";
		$sortedProductionOne->length = "3800";
		$sortedProductionOne->capacity = "501.34";
		$sortedProductionOne->capacity_per_piece = "0.00924";

		$sortedProductionTwo = new SortedProduction();
		$sortedProductionTwo->type = "G";
		$sortedProductionTwo->count = "200";
		$sortedProductionTwo->thickness = "40";
		$sortedProductionTwo->width = "30";
		$sortedProductionTwo->length = "3000";
		$sortedProductionTwo->capacity = "30.458";
		$sortedProductionTwo->capacity_per_piece = "0.00043";

		//Act
		$sortingProduction->Save();
		$sortedProductionOne->sorting_id = $sortingProduction->id;
		$sortedProductionOne->Save();
		$sortedProductionTwo->sorting_id = $sortingProduction->id;
		$sortedProductionTwo->Save();

		$count = $sortingProduction->count;
		$capacity = $sortingProduction->capacity;
		$sortedCapacity = $sortedProductionOne->capacity + $sortedProductionTwo->capacity;

		//Assert
		$totals = Manager::GetAllSortingProductionSummByDate("2018-02");
		$this->assertEquals($count, $totals['count']);
		$this->assertEquals($capacity, $totals['capacity']);
		$this->assertEquals($sortedCapacity, $totals['sorted_capacity']);
	}

	/**
	* @test
	*/
	public function When_Save_New_SortingProduction_Then_GetAllUselessSortingProductionSummByDate()	//Tests GetAllUselessSortingProductionSummByDate function
	{
		//Arrange
		$sortingProductionOne = new SortingProduction();
		$sortingProductionOne->date = "2018-02-10";
		$sortingProductionOne->datetime = "2018-02-10 12:00:00";
		$sortingProductionOne->time_from = "06:00";
		$sortingProductionOne->time_to = "14:30";
		$sortingProductionOne->invoice = "1007";
		$sortingProductionOne->thickness = "50";
		$sortingProductionOne->width = "125";
		$sortingProductionOne->length = "4800";
		$sortingProductionOne->count = "700";
		$sortingProductionOne->capacity = "700.255";
		$sortingProductionOne->defect_count = NULL;
		$sortingProductionOne->reserved = 0;
		
		$sortedProductionOne = new SortedProduction();
		$sortedProductionOne->type = "W";
		$sortedProductionOne->count = "500";
		$sortedProductionOne->thickness = "42";
		$sortedProductionOne->width = "35";
		$sortedProductionOne->length = "3800";
		$sortedProductionOne->capacity = "501.34";
		$sortedProductionOne->capacity_per_piece = "0.00924";

		$sortedProductionTwo = new SortedProduction();
		$sortedProductionTwo->type = "W";
		$sortedProductionTwo->count = "200";
		$sortedProductionTwo->thickness = "40";
		$sortedProductionTwo->width = "30";
		$sortedProductionTwo->length = "3000";
		$sortedProductionTwo->capacity = "30.458";
		$sortedProductionTwo->capacity_per_piece = "0.00043";

		$sortingProductionTwo = new SortingProduction();
		$sortingProductionTwo->date = "2018-02-10";
		$sortingProductionTwo->datetime = "2018-02-10 13:30:42";
		$sortingProductionTwo->time_from = "07:00";
		$sortingProductionTwo->time_to = "14:30";
		$sortingProductionTwo->invoice = "1008";
		$sortingProductionTwo->thickness = "50";
		$sortingProductionTwo->width = "125";
		$sortingProductionTwo->length = "4800";
		$sortingProductionTwo->count = "700";
		$sortingProductionTwo->capacity = "700.255";
		$sortingProductionTwo->defect_count = NULL;
		$sortingProductionTwo->reserved = 1;

		//Act
		$sortingProductionOne->Save();
		$sortingProductionTwo->Save();
		$sortedProductionOne->sorting_id = $sortingProductionOne->id;
		$sortedProductionOne->Save();
		$sortedProductionTwo->sorting_id = $sortingProductionOne->id;
		$sortedProductionTwo->Save();

		$reservedCount = $sortingProductionTwo->count;
		$reservedCapacity = $sortingProductionTwo->capacity;
		$sortedSoakedCapacity = $sortedProductionOne->capacity + $sortedProductionTwo->capacity;

		//Assert
		$totals = Manager::GetAllUselessSortingProductionSummByDate("2018-02");
		$this->assertEquals($reservedCount, $totals['reserved_count']);
		$this->assertEquals($reservedCapacity, $totals['reserved_capacity']);
		$this->assertEquals($sortedSoakedCapacity, $totals['soaked_capacity']);
	}

	/**
	* @test
	*/
	public function When_Save_New_SortingProduction_Then_GetSortedProductionsByID()	//Tests GetAllUselessSortingProductionSummByDate function
	{
		//Arrange
		$sortingProduction = new SortingProduction();
		$sortingProduction->date = "2005-06-12";
		$sortingProduction->datetime = "2005-06-12 09:12:08";
		$sortingProduction->time_from = "06:00";
		$sortingProduction->time_to = "14:30";
		$sortingProduction->invoice = "1009";
		$sortingProduction->thickness = "50";
		$sortingProduction->width = "125";
		$sortingProduction->length = "4800";
		$sortingProduction->count = "700";
		$sortingProduction->capacity = "700.255";
		$sortingProduction->defect_count = NULL;
		$sortingProduction->reserved = 0;
		
		$sortedProductionOne = new SortedProduction();
		$sortedProductionOne->type = "S";
		$sortedProductionOne->count = "500";
		$sortedProductionOne->thickness = "42";
		$sortedProductionOne->width = "35";
		$sortedProductionOne->length = "3800";
		$sortedProductionOne->capacity = "501.34";
		$sortedProductionOne->capacity_per_piece = "0.00924";

		$sortedProductionTwo = new SortedProduction();
		$sortedProductionTwo->type = "W";
		$sortedProductionTwo->count = "200";
		$sortedProductionTwo->thickness = "40";
		$sortedProductionTwo->width = "30";
		$sortedProductionTwo->length = "3000";
		$sortedProductionTwo->capacity = "30.458";
		$sortedProductionTwo->capacity_per_piece = "0.00043";

		//Act
		$sortingProduction->Save();
		$sortedProductionOne->sorting_id = $sortingProduction->id;
		$sortedProductionOne->Save();
		$sortedProductionTwo->sorting_id = $sortingProduction->id;
		$sortedProductionTwo->Save();

		//Assert
		$sortedProductions = Manager::GetSortedProductionsByID($sortingProduction->id);
		$this->assertEquals(2, count($sortedProductions));

		$capacities = array($sortedProductionOne->capacity, $sortedProductionTwo->capacity);
		$i = 0;
		foreach($sortedProductions as $production)
		{
			$this->assertEquals($capacities[$i], $production['capacity']);
			$i++;
		}
	}

	/**
	* @test
	*/
	public function When_Save_New_SortingProduction_Then_GetAllSortingProductionWorkers()	//Tests GetAllSortingProductionWorkers function
	{
		//Arrange
		$employee = new Employee();
		$employee->name = "Speaking";
		$employee->last_name = "Coronary";
		$employee->person_id = "315162-31516";
		$employee->place = "Skirotava";
		$employee->shift = NULL;
		$employee->capacity_rate = NULL;
		$employee->hour_rate = NULL;
		$employee->working_from = "2001-06-22";
		$employee->working_to = NULL;

		$sortingProduction = new SortingProduction();
		$sortingProduction->date = "2003-11-04";
		$sortingProduction->datetime = "2003-11-04 09:12:08";
		$sortingProduction->time_from = "06:00";
		$sortingProduction->time_to = "14:30";
		$sortingProduction->invoice = "1010";
		$sortingProduction->thickness = "50";
		$sortingProduction->width = "125";
		$sortingProduction->length = "4800";
		$sortingProduction->count = "700";
		$sortingProduction->capacity = "700.255";
		$sortingProduction->defect_count = NULL;
		$sortingProduction->reserved = 0;
		
		$sortedProduction = new SortedProduction();
		$sortedProduction->type = "G";
		$sortedProduction->count = "500";
		$sortedProduction->thickness = "42";
		$sortedProduction->width = "35";
		$sortedProduction->length = "3800";
		$sortedProduction->capacity = "501.34";
		$sortedProduction->capacity_per_piece = "0.00924";

		//Act
		$employee->Save();
		$sortingProduction->Save();
		$sortedProduction->sorting_id = $sortingProduction->id;
		$sortedProduction->Save();
		$employeesSortedProcutions = new EmployeeSortedProductions();
		$employeesSortedProcutions->employee_id = $employee->id;
		$employeesSortedProcutions->sorted_id = $sortedProduction->id;
		$employeesSortedProcutions->Save();

		//Assert
		$employees = Manager::GetAllSortingProductionWorkers($sortedProduction->id);
		foreach($employees as $employeeData)
		{
			$this->assertEquals($employee->name, $employeeData['name']);
		}
	}

	/**
	* @test
	*/
	public function When_Save_New_SortingProductions_Then_GetSortingEmployeeProductionsDaysWorked()	//Tests GetSortingEmployeeProductionsDaysWorked function
	{
		//Arrange
		$employee = new Employee();
		$employee->name = "Gob";
		$employee->last_name = "Publisher";
		$employee->person_id = "768649-76864";
		$employee->place = "Skirotava";
		$employee->shift = NULL;
		$employee->capacity_rate = NULL;
		$employee->hour_rate = NULL;
		$employee->working_from = "2000-01-01";
		$employee->working_to = NULL;

		$sortingProduction = new SortingProduction();
		$sortingProduction->date = "2003-11-04";
		$sortingProduction->datetime = "2003-11-04 09:00:00";
		$sortingProduction->time_from = "06:00";
		$sortingProduction->time_to = "14:30";
		$sortingProduction->invoice = "1010";
		$sortingProduction->thickness = "50";
		$sortingProduction->width = "125";
		$sortingProduction->length = "4800";
		$sortingProduction->count = "700";
		$sortingProduction->capacity = "700.255";
		$sortingProduction->defect_count = NULL;
		$sortingProduction->reserved = 0;
		
		$sortedProduction = new SortedProduction();
		$sortedProduction->type = "S";
		$sortedProduction->count = "500";
		$sortedProduction->thickness = "42";
		$sortedProduction->width = "35";
		$sortedProduction->length = "3800";
		$sortedProduction->capacity = "501.34";
		$sortedProduction->capacity_per_piece = "0.00924";

		$working_times = new WorkingTimes();
		$working_times->working_hours = "8";

		//Act
		$employee->Save();
		$sortingProduction->Save();
		$sortedProduction->sorting_id = $sortingProduction->id;
		$sortedProduction->Save();
		$employeesSortedProcutions = new EmployeeSortedProductions();
		$employeesSortedProcutions->employee_id = $employee->id;
		$employeesSortedProcutions->sorted_id = $sortedProduction->id;
		$employeesSortedProcutions->Save();
		$working_times->date = $sortingProduction->date;
		$working_times->datetime = $sortingProduction->datetime;
		$working_times->invoice = $sortedProduction->id;
		$working_times->employee_id = $employee->id;
		$working_times->Save();

		//Assert
		$days = Manager::GetSortingEmployeeProductionsDaysWorked("2003-11", $employee->id);
		$this->assertEquals(1, $days['working']);
	}

	/**
	* @test
	*/
	public function When_Save_New_SortingProductions_Then_GetSortingEmployeeProductionsHoursWorked()	//Tests GetSortingEmployeeProductionsHoursWorked function
	{
		//Arrange
		$employee = new Employee();
		$employee->name = "Grin";
		$employee->last_name = "Cracking";
		$employee->person_id = "176823-17682";
		$employee->place = "Skirotava";
		$employee->shift = NULL;
		$employee->capacity_rate = NULL;
		$employee->hour_rate = NULL;
		$employee->working_from = "2000-01-01";
		$employee->working_to = NULL;

		$sortingProduction = new SortingProduction();
		$sortingProduction->date = "2003-11-05";
		$sortingProduction->datetime = "2003-11-05 09:00:00";
		$sortingProduction->time_from = "06:00";
		$sortingProduction->time_to = "14:30";
		$sortingProduction->invoice = "1011";
		$sortingProduction->thickness = "50";
		$sortingProduction->width = "125";
		$sortingProduction->length = "4800";
		$sortingProduction->count = "700";
		$sortingProduction->capacity = "700.255";
		$sortingProduction->defect_count = NULL;
		$sortingProduction->reserved = 0;
		
		$sortedProduction = new SortedProduction();
		$sortedProduction->type = "S";
		$sortedProduction->count = "500";
		$sortedProduction->thickness = "42";
		$sortedProduction->width = "35";
		$sortedProduction->length = "3800";
		$sortedProduction->capacity = "501.34";
		$sortedProduction->capacity_per_piece = "0.00924";

		$working_times = new WorkingTimes();
		$working_times->working_hours = "8";

		//Act
		$employee->Save();
		$sortingProduction->Save();
		$sortedProduction->sorting_id = $sortingProduction->id;
		$sortedProduction->Save();
		$employeesSortedProcutions = new EmployeeSortedProductions();
		$employeesSortedProcutions->employee_id = $employee->id;
		$employeesSortedProcutions->sorted_id = $sortedProduction->id;
		$employeesSortedProcutions->Save();
		$working_times->date = $sortingProduction->date;
		$working_times->datetime = $sortingProduction->datetime;
		$working_times->invoice = $sortedProduction->id;
		$working_times->employee_id = $employee->id;
		$working_times->Save();

		//Assert
		$hours = Manager::GetSortingEmployeeProductionsHoursWorked("2003-11", $employee->id);
		$this->assertEquals($working_times->working_hours, $hours['working_hours']);
	}

	/**
	* @test
	*/
	public function When_Save_New_SortingProductions_Then_GetSortingEmployeeProductionsStretchedHoursWorked()	//Tests GetSortingEmployeeProductionsStretchedHoursWorked function
	{
		//Arrange
		$employee = new Employee();
		$employee->name = "Wheater";
		$employee->last_name = "Shing";
		$employee->person_id = "797205-79720";
		$employee->place = "Skirotava";
		$employee->shift = NULL;
		$employee->capacity_rate = NULL;
		$employee->hour_rate = NULL;
		$employee->working_from = "2000-01-01";
		$employee->working_to = NULL;

		$sortingProduction = new SortingProduction();
		$sortingProduction->date = "2003-11-06";
		$sortingProduction->datetime = "2003-11-06 08:00:00";
		$sortingProduction->time_from = "06:00";
		$sortingProduction->time_to = "14:30";
		$sortingProduction->invoice = "1012";
		$sortingProduction->thickness = "50";
		$sortingProduction->width = "125";
		$sortingProduction->length = "4800";
		$sortingProduction->count = "700";
		$sortingProduction->capacity = "700.255";
		$sortingProduction->defect_count = NULL;
		$sortingProduction->reserved = 0;
		
		$sortedProduction = new SortedProduction();
		$sortedProduction->type = "G";
		$sortedProduction->count = "500";
		$sortedProduction->thickness = "42";
		$sortedProduction->width = "35";
		$sortedProduction->length = "3800";
		$sortedProduction->capacity = "501.34";
		$sortedProduction->capacity_per_piece = "0.00924";

		$working_times = new WorkingTimes();
		$working_times->working_hours = "8";

		//Act
		$employee->Save();
		$sortingProduction->Save();
		$sortedProduction->sorting_id = $sortingProduction->id;
		$sortedProduction->Save();
		$employeesSortedProcutions = new EmployeeSortedProductions();
		$employeesSortedProcutions->employee_id = $employee->id;
		$employeesSortedProcutions->sorted_id = $sortedProduction->id;
		$employeesSortedProcutions->Save();
		$working_times->date = $sortingProduction->date;
		$working_times->datetime = $sortingProduction->datetime;
		$working_times->invoice = $sortedProduction->id;
		$working_times->employee_id = $employee->id;
		$working_times->Save();

		//Assert
		$streachedHours = Manager::GetSortingEmployeeProductionsStretchedHoursWorked("2003-11", $employee->id);
		$this->assertEquals($working_times->working_hours, $streachedHours['working_hours']);
	}

	/**
	* @test
	*/
	public function When_Save_New_SortingProductions_Then_GetSortingEmployeeProductionsSortedCapacity()	//Tests GetSortingEmployeeProductionsSortedCapacity function
	{
		//Arrange
		$employee = new Employee();
		$employee->name = "Cherries";
		$employee->last_name = "Neanderthal";
		$employee->person_id = "481483-48148";
		$employee->place = "Skirotava";
		$employee->shift = NULL;
		$employee->capacity_rate = NULL;
		$employee->hour_rate = NULL;
		$employee->working_from = "2000-01-01";
		$employee->working_to = NULL;

		$sortingProduction = new SortingProduction();
		$sortingProduction->date = "2003-11-07";
		$sortingProduction->datetime = "2003-11-07 07:00:00";
		$sortingProduction->time_from = "06:00";
		$sortingProduction->time_to = "14:30";
		$sortingProduction->invoice = "1012";
		$sortingProduction->thickness = "50";
		$sortingProduction->width = "125";
		$sortingProduction->length = "4800";
		$sortingProduction->count = "700";
		$sortingProduction->capacity = "700.255";
		$sortingProduction->defect_count = NULL;
		$sortingProduction->reserved = 0;
		
		$sortedProduction = new SortedProduction();
		$sortedProduction->type = "S";
		$sortedProduction->count = "500";
		$sortedProduction->thickness = "42";
		$sortedProduction->width = "35";
		$sortedProduction->length = "3800";
		$sortedProduction->capacity = "501.34";
		$sortedProduction->capacity_per_piece = "0.0095";

		$working_times = new WorkingTimes();
		$working_times->working_hours = "8";

		//Act
		$employee->Save();
		$sortingProduction->Save();
		$sortedProduction->sorting_id = $sortingProduction->id;
		$sortedProduction->Save();
		$employeesSortedProcutions = new EmployeeSortedProductions();
		$employeesSortedProcutions->employee_id = $employee->id;
		$employeesSortedProcutions->sorted_id = $sortedProduction->id;
		$employeesSortedProcutions->Save();
		$working_times->date = $sortingProduction->date;
		$working_times->datetime = $sortingProduction->datetime;
		$working_times->invoice = $sortedProduction->id;
		$working_times->employee_id = $employee->id;
		$working_times->Save();

		//Assert
		$sortedCapacities = Manager::GetSortingEmployeeProductionsSortedCapacity("2003-11", $employee->id);
		$this->assertEquals($sortedProduction->capacity, $sortedCapacities['cap_two']);
		$this->assertEquals($sortedProduction->capacity, $sortedCapacities['total_cap']);
	}
	
}