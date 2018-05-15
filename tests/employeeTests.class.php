<?php

require_once "C:/wamp64/www/pieraksts/includes/employee.class.php";
require_once "C:/wamp64/www/pieraksts/includes/position.class.php";
require_once "C:/wamp64/www/pieraksts/includes/employee_position.class.php";

use PHPUnit\Framework\TestCase;

class EmployeeTests extends TestCase
{
	/**
	* @test
	*/
	public function When_Save_New_Employee_Then_It_Exists_In_Database()	//Tests Save function
	{
		global $conn;
		$conn->query("DELETE FROM employees");	//Clears DB
		$conn->query("DELETE FROM positions");	//Clears DB
		$conn->query("DELETE FROM employees_positions");	//Clears DB
		//Arrange
		$employee = new Employee();
		$employee->name = "Mouldy";
		$employee->last_name = "Lagged";
		$employee->person_id = "123456-12345";
		$employee->place = "Zagetava";
		$employee->shift = "1";
		$employee->capacity_rate = "1";
		$employee->hour_rate = "1";
		$employee->working_from = "2018-04-20";
		$employee->working_to = NULL;

		//Act
		$employee->Save();

		//Assert
		global $conn;
		$sql = $conn->prepare("SELECT employees.* FROM employees WHERE id = ?");
		$sql->bind_param('s', $employee->id);
		$sql->execute();
		$result = $sql->get_result();
		$result = mysqli_fetch_assoc($result);

		$this->assertEquals($result['name'], $employee->name);
	}

	/**
	* @test
	*/
	public function When_Save_New_Employee_Then_Its_Data_Can_Be_Updated()	//Tests Update function
	{
		//Arrange
		$employee = new Employee();
		$employee->name = "Amber";
		$employee->last_name = "Acesible";
		$employee->person_id = "823442-53553";
		$employee->place = "Skirotava";
		$employee->shift = NULL;
		$employee->capacity_rate = NULL;
		$employee->hour_rate = NULL;
		$employee->working_from = "2000-01-01";
		$employee->working_to = NULL;

		$employeeUpdate = new Employee();
		$employeeUpdate->name = "Amber";
		$employeeUpdate->last_name = "Unshod";
		$employeeUpdate->person_id = "823442-53553";
		$employeeUpdate->place = "Birojs";
		$employeeUpdate->shift = NULL;
		$employeeUpdate->capacity_rate = NULL;
		$employeeUpdate->hour_rate = NULL;
		$employeeUpdate->working_from = "2001-02-14";
		$employeeUpdate->working_to = NULL;

		//Act
		$employee->Save();
		$employeeUpdate->id = $employee->id;
		$employeeUpdate->Update();

		//Assert
		$result = Employee::GetEmployeesData($employee->id);
		$this->assertEquals($result['name'], $employee->name);
		$this->assertEquals($result['name'], $employeeUpdate->name);
		$this->assertNotEquals($result['last_name'], $employee->last_name);
	}

	/**
	* @test
	*/
	public function When_Save_New_Employee_Then_ExistsEmployeeWithID_In_DB()	//Tests ExistsEmployeeWithID function
	{
		//Arrange
		$employee = new Employee();
		$employee->name = "Slimy";
		$employee->last_name = "Bottles";
		$employee->person_id = "872980-32899";
		$employee->place = "Birojs";
		$employee->shift = NULL;
		$employee->capacity_rate = NULL;
		$employee->hour_rate = NULL;
		$employee->working_from = "2000-01-02";
		$employee->working_to = NULL;

		//Act
		$employee->Save();

		//Assert
		$this->assertTrue(Employee::ExistsEmployeeWithID($employee->id));
	}

	/**
	* @test
	*/
	public function When_Save_New_Employees_Then_ExistsEmployeesWorkplaceSawmillWithID_In_DB()	//Tests ExistsEmployeesWorkplaceSawmillWithID function
	{
		//Arrange
		$employeeOne = new Employee();
		$employeeOne->name = "Quantock";
		$employeeOne->last_name = "Average";
		$employeeOne->person_id = "093293-54353";
		$employeeOne->place = "Skirotava";
		$employeeOne->shift = NULL;
		$employeeOne->capacity_rate = NULL;
		$employeeOne->hour_rate = NULL;
		$employeeOne->working_from = "2000-01-03";
		$employeeOne->working_to = NULL;

		$employeeTwo = new Employee();
		$employeeTwo->name = "Crunchy";
		$employeeTwo->last_name = "Bitcoin";
		$employeeTwo->person_id = "020432-98234";
		$employeeTwo->place = "Zagetava";
		$employeeTwo->shift = "2";
		$employeeTwo->capacity_rate = "1.23";
		$employeeTwo->hour_rate = "2.45";
		$employeeTwo->working_from = "2000-01-04";
		$employeeTwo->working_to = NULL;

		//Act
		$employeeOne->Save();
		$employeeTwo->Save();

		//Assert
		$this->assertFalse(Employee::ExistsEmployeesWorkplaceSawmillWithID($employeeOne->id));
		$this->assertTrue(Employee::ExistsEmployeesWorkplaceSawmillWithID($employeeTwo->id));
	}

	/**
	* @test
	*/
	public function When_Save_New_Employee_Then_GetEmployeesData_From_DB()	//Tests GetEmployeesData function
	{
		//Arrange
		$employee = new Employee();
		$employee->name = "Rbot";
		$employee->last_name = "Exec";
		$employee->person_id = "982435-43242";
		$employee->place = "Zagetava";
		$employee->shift = "1";
		$employee->capacity_rate = "2.00";
		$employee->hour_rate = "2.1";
		$employee->working_from = "2002-01-05";
		$employee->working_to = NULL;

		//Act
		$employee->Save();

		//Assert
		$result = Employee::GetEmployeesData($employee->id);
		$this->assertEquals($employee->name, $result['name']);
		$this->assertEquals($employee->person_id, $result['person_id']);
		$this->assertEquals($employee->working_to, $result['working_to']);
	}

	/**
	* @test
	*/
	public function When_Save_New_Employees_Then_ExistsSortingEmployee_In_DB()	//Tests ExistsSortingEmployee function
	{
		//Arrange
		$employeeOne = new Employee();
		$employeeOne->name = "Pastilles";
		$employeeOne->last_name = "Nangin";
		$employeeOne->person_id = "523044-52304";
		$employeeOne->place = "Skirotava";
		$employeeOne->shift = NULL;
		$employeeOne->capacity_rate = NULL;
		$employeeOne->hour_rate = NULL;
		$employeeOne->working_from = "2000-12-03";
		$employeeOne->working_to = NULL;

		$employeeTwo = new Employee();
		$employeeTwo->name = "Exec";
		$employeeTwo->last_name = "Vice";
		$employeeTwo->person_id = "676569-67656";
		$employeeTwo->place = "Zagetava";
		$employeeTwo->shift = "1";
		$employeeTwo->capacity_rate = "4.01";
		$employeeTwo->hour_rate = "2";
		$employeeTwo->working_from = "2000-9-04";
		$employeeTwo->working_to = NULL;

		//Act
		$employeeOne->Save();
		$employeeTwo->Save();

		//Assert
		$this->assertTrue(Employee::ExistsSortingEmployee($employeeOne->id));
		$this->assertFalse(Employee::ExistsSortingEmployee($employeeTwo->id));
	}

	/**
	* @test
	*/
	public function When_Save_New_Employee_Then_Its_ExistsPersonNo_In_Database()	//Tests ExistsPersonNo function
	{
		//Arrange
		$employee = new Employee();
		$employee->name = "Nitrate";
		$employee->last_name = "Gallo";
		$employee->person_id = "748918-74891";
		$employee->place = "Birojs";
		$employee->shift = NULL;
		$employee->capacity_rate = NULL;
		$employee->hour_rate = NULL;
		$employee->working_from = "2008-02-10";
		$employee->working_to = NULL;

		//Act
		$employee->Save();

		//Assert
		$this->assertTrue(Employee::ExistsPersonNo($employee->person_id));
		$this->assertFalse(Employee::ExistsPersonNo("000000-0"));
	}

	/**
	* @test
	*/
	public function When_Save_New_Employees_Then_Its_CurrentPersonNoExists_In_Database()	//Tests CurrentPersonNoExists function
	{
		//Arrange
		$employeeOne = new Employee();
		$employeeOne->name = "Shellfish";
		$employeeOne->last_name = "Pani";
		$employeeOne->person_id = "516256-51625";
		$employeeOne->place = "Birojs";
		$employeeOne->shift = NULL;
		$employeeOne->capacity_rate = NULL;
		$employeeOne->hour_rate = NULL;
		$employeeOne->working_from = "2018-11-11";
		$employeeOne->working_to = NULL;

		$employeeTwo = new Employee();
		$employeeTwo->name = "Eri";
		$employeeTwo->last_name = "Totterin";
		$employeeTwo->person_id = "498308-49830";
		$employeeTwo->place = "Zagetava";
		$employeeTwo->shift = "2";
		$employeeTwo->capacity_rate = "0.01";
		$employeeTwo->hour_rate = "0.1";
		$employeeTwo->working_from = "2010-2-28";
		$employeeTwo->working_to = NULL;

		//Act
		$employeeOne->Save();
		$employeeTwo->Save();

		//Assert
		$this->assertTrue(Employee::CurrentPersonNoExists($employeeTwo->person_id, $employeeOne->id));
		$this->assertFalse(Employee::CurrentPersonNoExists($employeeOne->person_id, $employeeOne->id));
	}

	/**
	* @test
	*/
	public function When_Save_New_Employee_And_Positions_Then_GetAllPositions_From_Database()	//Tests GetAllPositions function
	{
		//Arrange
		$employee = new Employee();
		$employee->name = "Scorpion";
		$employee->last_name = "Functional";
		$employee->person_id = "575306-57530";
		$employee->place = "Zagetava";
		$employee->shift = "2";
		$employee->capacity_rate = "0.2";
		$employee->hour_rate = "2.99";
		$employee->working_from = "2008-03-20";
		$employee->working_to = NULL;

		$positionOne = new Position();
		$positionOne->name = "Operators";
		$positionTwo = new Position();
		$positionTwo->name = "Krāvējs";
		$positionThree = new Position();
		$positionThree->name = "Palīgstrādnieks";

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
		$employeePosition->position_id = $positionThree->id;
		$employeePosition->Save();

		//Assert
		$allEmployeePositions = Employee::GetAllPositions($employee->id);
		$this->assertEquals(3, count($allEmployeePositions));

		$positions = array($positionOne->name, $positionTwo->name, $positionThree->name);
		$i = 0;
		foreach($allEmployeePositions as $position)
		{
			$this->assertEquals($positions[$i], $position['name']);
			$i++;
		}
		
	}
}

?>