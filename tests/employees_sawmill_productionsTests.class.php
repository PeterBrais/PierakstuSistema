<?php

require_once "C:/wamp64/www/pieraksts/includes/sawmill_production.class.php";
require_once "C:/wamp64/www/pieraksts/includes/beam_size.class.php";
require_once "C:/wamp64/www/pieraksts/includes/employees_sawmill_productions.class.php";
require_once "C:/wamp64/www/pieraksts/includes/employee.class.php";

use PHPUnit\Framework\TestCase;

//Test is divided in 3 parts:
//1. Arrange - prepares data
//2. Act - sets data
//3. Assert - tests data

class EmployeeSawmillProductionsTests extends TestCase
{
	/**
	* @test
	*/
	public function When_Save_New_EmployeeSawmillProduction_Then_It_Exists_In_Database()	//Tests Save function
	{
		global $conn;
		$conn->query("DELETE FROM employees");	//Clears DB
		$conn->query("DELETE FROM beam_sizes");	//Clears DB
		$conn->query("DELETE FROM sawmill_productions");	//Clears DB
		$conn->query("DELETE FROM employees_sawmill_productions");	//Clears DB
		//Arrange
		$employee = new Employee();
		$employee->name = "Lookup";
		$employee->last_name = "Deter";
		$employee->person_id = "332040-33204";
		$employee->place = "Zagetava";
		$employee->shift = "1";
		$employee->capacity_rate = "2.43";
		$employee->hour_rate = "0.42";
		$employee->working_from = "2005-11-20";
		$employee->working_to = NULL;

		$size = new BeamSize();
		$size->size = "1.72";

		$sawmillProduction = new SawmillProduction();
		$sawmillProduction->date = "2011-05-24";
		$sawmillProduction->datetime = "2011-05-24 22:52:50";
		$sawmillProduction->time_from = "12:30";
		$sawmillProduction->time_to = "23:00";
		$sawmillProduction->invoice = "1003";
		$sawmillProduction->beam_count = "752";
		$sawmillProduction->beam_capacity = "234.64";
		$sawmillProduction->lumber_count = "642";
		$sawmillProduction->lumber_capacity = "822.3";
		$sawmillProduction->percentage = "52.9";
		$sawmillProduction->note = NULL;

		//Act
		$employee->Save();
		$size->Save();
		$sawmillProduction->beam_size_id = $size->id;
		$sawmillProduction->Save();
		$employeesSawmillProcutions = new EmployeeSawmillProductions();
		$employeesSawmillProcutions->employee_id = $employee->id;
		$employeesSawmillProcutions->sawmill_id = $sawmillProduction->id;
		$employeesSawmillProcutions->Save();

		//Assert
		$sql = $conn->prepare("SELECT employees_sawmill_productions.* FROM employees_sawmill_productions WHERE sawmill_id = ?");
		$sql->bind_param('s', $sawmillProduction->id);
		$sql->execute();
		$result = $sql->get_result();
		$result = mysqli_fetch_assoc($result);
		$this->assertEquals($result['employee_id'], $employee->id);
		$this->assertEquals($result['sawmill_id'], $sawmillProduction->id);
	}

	/**
	* @test
	*/
	public function When_Save_New_EmployeeSawmillProduction_Then_It_Can_Be_Deleted()	//Tests Delete function
	{
		//Arrange
		$employeeOne = new Employee();
		$employeeOne->name = "Anode";
		$employeeOne->last_name = "Entire";
		$employeeOne->person_id = "459781-45978";
		$employeeOne->place = "Zagetava";
		$employeeOne->shift = "1";
		$employeeOne->capacity_rate = "1.37";
		$employeeOne->hour_rate = "3.46";
		$employeeOne->working_from = "2001-05-14";
		$employeeOne->working_to = NULL;

		$employeeTwo = new Employee();
		$employeeTwo->name = "Casminir";
		$employeeTwo->last_name = "Kintail";
		$employeeTwo->person_id = "874952-87495";
		$employeeTwo->place = "Zagetava";
		$employeeTwo->shift = "2";
		$employeeTwo->capacity_rate = "1.89";
		$employeeTwo->hour_rate = "3.23";
		$employeeTwo->working_from = "2002-08-22";
		$employeeTwo->working_to = NULL;

		$size = new BeamSize();
		$size->size = "0.83";

		$sawmillProductionOne = new SawmillProduction();
		$sawmillProductionOne->date = "2011-05-30";
		$sawmillProductionOne->datetime = "2011-05-30 14:30:20";
		$sawmillProductionOne->time_from = "06:00";
		$sawmillProductionOne->time_to = "12:30";
		$sawmillProductionOne->invoice = "1001";
		$sawmillProductionOne->beam_count = "324";
		$sawmillProductionOne->beam_capacity = "8442.53";
		$sawmillProductionOne->lumber_count = "89";
		$sawmillProductionOne->lumber_capacity = "984.5";
		$sawmillProductionOne->percentage = "50.0";
		$sawmillProductionOne->note = NULL;

		$sawmillProductionTwo = new SawmillProduction();
		$sawmillProductionTwo->date = "2011-05-31";
		$sawmillProductionTwo->datetime = "2011-05-31 23:12:59";
		$sawmillProductionTwo->time_from = "12:30";
		$sawmillProductionTwo->time_to = "23:00";
		$sawmillProductionTwo->invoice = "1002";
		$sawmillProductionTwo->beam_count = "724";
		$sawmillProductionTwo->beam_capacity = "963.42";
		$sawmillProductionTwo->lumber_count = "63";
		$sawmillProductionTwo->lumber_capacity = "482.3";
		$sawmillProductionTwo->percentage = "53.2";
		$sawmillProductionTwo->note = NULL;

		//Act
		$employeeOne->Save();
		$employeeTwo->Save();
		$size->Save();
		$sawmillProductionOne->beam_size_id = $size->id;
		$sawmillProductionOne->Save();
		$sawmillProductionTwo->beam_size_id = $size->id;
		$sawmillProductionTwo->Save();
		$employeesSawmillProcutionsOne = new EmployeeSawmillProductions();
		$employeesSawmillProcutionsOne->employee_id = $employeeOne->id;
		$employeesSawmillProcutionsOne->sawmill_id = $sawmillProductionOne->id;
		$employeesSawmillProcutionsOne->Save();
		$employeesSawmillProcutionsTwo = new EmployeeSawmillProductions();
		$employeesSawmillProcutionsTwo->employee_id = $employeeTwo->id;
		$employeesSawmillProcutionsTwo->sawmill_id = $sawmillProductionTwo->id;
		$employeesSawmillProcutionsTwo->Save();

		$production = new EmployeeSawmillProductions;
		$production->DeleteAllSawmillProductionEmployees($sawmillProductionOne->id);

		//Assert
		global $conn;
		$sql = $conn->prepare("SELECT employees_sawmill_productions.* FROM employees_sawmill_productions WHERE sawmill_id = ?");
		$sql->bind_param('s', $sawmillProductionOne->id);
		$sql->execute();
		$result = $sql->get_result();
		$result = mysqli_fetch_assoc($result);
		$this->assertEquals(0, count($result));

		$sql = $conn->prepare("SELECT employees_sawmill_productions.* FROM employees_sawmill_productions WHERE sawmill_id = ?");
		$sql->bind_param('s', $sawmillProductionTwo->id);
		$sql->execute();
		$result = $sql->get_result();
		$result = mysqli_fetch_assoc($result);
		$this->assertEquals($employeeTwo->id, $result['employee_id']);
	}
}