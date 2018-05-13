<?php

require_once "C:/wamp64/www/pieraksts/includes/employee_position.class.php";
require_once "C:/wamp64/www/pieraksts/includes/employee.class.php";
require_once "C:/wamp64/www/pieraksts/includes/position.class.php";

use PHPUnit\Framework\TestCase;

//Test is divided in 3 parts:
//1. Arrange - prepares data
//2. Act - sets data
//3. Assert - tests data

class PositionTests extends TestCase
{
	/**
	* @test
	*/
	public function When_Save_New_Position_Then_ExistsName_In_Database()	//Tests ExistsName function
	{
		global $conn;
		$conn->query("DELETE FROM employees");	//Clears DB
		$conn->query("DELETE FROM positions");	//Clears DB
		$conn->query("DELETE FROM employees_positions");	//Clears DB
		//Arrange
		$position = new Position();
		$position->name = "Autokrāvēja vadītājs";

		//Act
		$position->Save();

		//Assert
		$this->assertTrue(Position::ExistsName($position->name));
	}

	/**
	* @test
	*/
	public function When_Save_New_Position_Then_It_Exists_In_Database()	//Tests Exists function
	{
		//Arrange
		$position = new Position();
		$position->name = "Frontālās virpas speciālists";

		//Act
		$position->Save();

		//Assert
		$this->assertTrue(Position::Exists($position->id));
	}

	/**
	* @test
	*/
	public function When_Save_New_Positions_Then_Check_CurrentPositionExists_In_Database()	//Tests CurrentPositionExists function
	{
		//Arrange
		$positionOne = new Position();
		$positionOne->name = "Pakošanas palīgstrādnieks";

		$positionTwo = new Position();
		$positionTwo->name = "Zāģēsanas palīgstrādnieks";

		//Act
		$positionOne->Save();
		$positionTwo->Save();

		//Assert
		$this->assertFalse(Position::CurrentPositionExists($positionOne->name, $positionOne->id));
		$this->assertTrue(Position::CurrentPositionExists($positionOne->name, $positionTwo->id));
	}

	/**
	* @test
	*/
	public function When_Save_New_Positions_Then_Check_IsPositionUsed_In_Database()	//Tests IsPositionUsed function
	{
		//Arrange
		$employee = new Employee();
		$employee->name = "Flossied";
		$employee->last_name = "Gouth";
		$employee->person_id = "810305-81030";
		$employee->place = "Zagetava";
		$employee->shift = "1";
		$employee->capacity_rate = "0.5";
		$employee->hour_rate = "3";
		$employee->working_from = "2010-01-21";
		$employee->working_to = NULL;

		$positionOne = new Position();
		$positionOne->name = "Zāģētājs";

		$positionTwo = new Position();
		$positionTwo->name = "Krāvējs";

		$employeePosition = new EmployeePosition();

		//Act
		$employee->Save();
		$positionOne->Save();
		$positionTwo->Save();
		$employeePosition->employee_id = $employee->id;
		$employeePosition->position_id = $positionOne->id;
		$employeePosition->Save();

		//Assert
		$this->assertTrue(Position::IsPositionUsed($positionOne->id));
		$this->assertFalse(Position::IsPositionUsed($positionTwo->id));
	}

	/**
	* @test
	*/
	public function When_Save_New_Position_Then_Check_IsPositionOperatorOrAssistant_In_Database()	//Tests IsPositionOperatorOrAssistant function
	{
		//Arrange
		$employeeOne = new Employee();
		$employeeOne->name = "Solitary";
		$employeeOne->last_name = "Receptive";
		$employeeOne->person_id = "775202-77520";
		$employeeOne->place = "Zagetava";
		$employeeOne->shift = "1";
		$employeeOne->capacity_rate = "1.1";
		$employeeOne->hour_rate = "3.46";
		$employeeOne->working_from = "2011-08-07";
		$employeeOne->working_to = NULL;

		$employeeTwo = new Employee();
		$employeeTwo->name = "Claws";
		$employeeTwo->last_name = "Wheatear";
		$employeeTwo->person_id = "428996-42899";
		$employeeTwo->place = "Zagetava";
		$employeeTwo->shift = "2";
		$employeeTwo->capacity_rate = "1.2";
		$employeeTwo->hour_rate = "3.47";
		$employeeTwo->working_from = "2011-08-02";
		$employeeTwo->working_to = NULL;

		$positionOne = new Position();
		$positionOne->name = "Pakošanas operators";

		$positionTwo = new Position();
		$positionTwo->name = "Zāģēšanas iecirkņa palīgstrādnieks";

		$employeePosition = new EmployeePosition();

		//Act
		$employeeOne->Save();
		$employeeTwo->Save();
		$positionOne->Save();
		$positionTwo->Save();
		$employeePosition->employee_id = $employeeOne->id;
		$employeePosition->position_id = $positionOne->id;
		$employeePosition->Save();
		$employeePosition->employee_id = $employeeOne->id;
		$employeePosition->position_id = $positionTwo->id;
		$employeePosition->Save();

		//Assert
		$this->assertTrue(Position::IsPositionOperatorOrAssistant($employeeOne->id));
		$this->assertFalse(Position::IsPositionOperatorOrAssistant($employeeTwo->id));
	}

	/**
	* @test
	*/
	public function When_Save_New_Position_Then_Its_Data_Exists_In_Database()	//Tests Save function
	{
		//Arrange
		$position = new Position();
		$position->name = "Frontālās virpas speciālists";

		//Act
		$position->Save();

		//Assert
		global $conn;
		$sql = $conn->prepare("SELECT positions.* FROM positions WHERE id = ?");
		$sql->bind_param('s', $position->id);
		$sql->execute();
		$result = $sql->get_result();
		$result = mysqli_fetch_assoc($result);

		$this->assertEquals($result['name'], $position->name);
	}

	/**
	* @test
	*/
	public function When_Save_New_Position_Then_Its_Data_Can_Be_Updated()	//Tests Update function
	{
		//Arrange
		$position = new Position();
		$position->name = "Maiņas vadītājs";

		$positionUpdate = new Position();
		$positionUpdate->name = "Maiņas vecākais";

		//Act
		$position->Save();
		$positionUpdate->id = $position->id;
		$positionUpdate->Update();

		//Assert
		global $conn;
		$sql = $conn->prepare("SELECT positions.* FROM positions WHERE id = ?");
		$sql->bind_param('s', $position->id);
		$sql->execute();
		$result = $sql->get_result();
		$result = mysqli_fetch_assoc($result);

		$this->assertEquals($result['name'], $positionUpdate->name);
		$this->assertNotEquals($result['name'], $position->name);
	}

	/**
	* @test
	*/
	public function When_Save_New_Position_Then_Its_Data_Can_Be_Deleted()	//Tests Delete function
	{
		//Arrange
		$position = new Position();
		$position->name = "Kokzāģētavas vadītājs";

		$positionDelete = new Position();

		//Act
		$position->Save();
		$positionDelete->id = $position->id;
		$positionDelete->Delete();

		//Assert
		global $conn;
		$sql = $conn->prepare("SELECT positions.* FROM positions WHERE id = ?");
		$sql->bind_param('s', $position->id);
		$sql->execute();
		$result = $sql->get_result();
		$result = mysqli_fetch_assoc($result);

		$this->assertEquals(0, count($result));
	}


}