<?php

require_once "C:/wamp64/www/pieraksts/includes/employee_position.class.php";
require_once "C:/wamp64/www/pieraksts/includes/employee.class.php";
require_once "C:/wamp64/www/pieraksts/includes/position.class.php";

use PHPUnit\Framework\TestCase;

//Test is divided in 3 parts:
//1. Arrange - prepares data
//2. Act - sets data
//3. Assert - tests data

class EmployeePositionTests extends TestCase
{
	/**
	* @test
	*/
	public function When_Save_New_EmployeePosition_Then_It_Exists_In_Database()	//Tests Save function
	{
		global $conn;
		$conn->query("DELETE FROM employees");	//Clears DB
		$conn->query("DELETE FROM positions");	//Clears DB
		$conn->query("DELETE FROM employees_positions");	//Clears DB
		//Arrange
		$employee = new Employee();
		$employee->name = "Retwee";
		$employee->last_name = "Bullseye";
		$employee->person_id = "918011-91801";
		$employee->place = "Birojs";
		$employee->shift = NULL;
		$employee->capacity_rate = NULL;
		$employee->hour_rate = NULL;
		$employee->working_from = "2000-01-30";
		$employee->working_to = NULL;

		$position = new Position();
		$position->name = "Vadītājs";

		$employeePosition = new EmployeePosition();

		//Act
		$employee->Save();
		$position->Save();
		$employeePosition->employee_id = $employee->id;
		$employeePosition->position_id = $position->id;
		$employeePosition->Save();

		//Assert
		global $conn;
		$sql = $conn->prepare("SELECT employees_positions.* FROM employees_positions WHERE employee_id = ? AND position_id = ?");
		$sql->bind_param('ss', $employee->id, $position->id);
		$sql->execute();
		$result = $sql->get_result();
		$result = mysqli_fetch_assoc($result);

		$this->assertEquals($result['employee_id'], $employee->id);
		$this->assertEquals($result['position_id'], $position->id);
	}

	/**
	* @test
	*/
	public function When_Save_New_EmployeePosition_Then_It_Can_Be_Deleted()	//Tests Save function
	{
		//Arrange
		$employee = new Employee();
		$employee->name = "Badminton";
		$employee->last_name = "Instinct";
		$employee->person_id = "646846-64684";
		$employee->place = "Skirotava";
		$employee->shift = NULL;
		$employee->capacity_rate = NULL;
		$employee->hour_rate = NULL;
		$employee->working_from = "2000-04-29";
		$employee->working_to = NULL;

		$position = new Position();
		$position->name = "Šķirotājs";

		$employeePosition = new EmployeePosition();

		//Act
		$employee->Save();
		$position->Save();
		$employeePosition->employee_id = $employee->id;
		$employeePosition->position_id = $position->id;
		$employeePosition->Save();

		$employeePosition->DeleteAllUserPositions($employee->id);

		//Assert
		$result = Employee::GetAllPositions($employee->id);
		$this->assertEquals(0, count($result));
	}
}