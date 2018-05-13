<?php

require_once "C:/wamp64/www/pieraksts/includes/employee.class.php";
require_once "C:/wamp64/www/pieraksts/includes/working_times.class.php";

use PHPUnit\Framework\TestCase;

//Test is divided in 3 parts:
//1. Arrange - prepares data
//2. Act - sets data
//3. Assert - tests data

class WorkingTimesTests extends TestCase
{
	static function GetEmployeeWorkingTime($id, $date, $datetime)	//Returns employees working times
	{
		global $conn;
		$sql = $conn->prepare("SELECT working_times.* FROM employees
								JOIN working_times ON working_times.employee_id = employees.id
								WHERE working_times.date = ? AND employees.id = ? AND working_times.invoice
								IS NULL AND working_times.datetime = ?");
		$sql->bind_param('sss', $date, $id, $datetime);
		$sql->execute();
		$result = $sql->get_result();
		return mysqli_fetch_assoc($result);
	}

	static function GetEmployeesWorkingTime($invoice, $date, $datetime)	//Returns one shifts working_times
	{
		global $conn;
		$sql = $conn->prepare("SELECT working_times.* FROM employees
								JOIN working_times ON working_times.employee_id = employees.id
								WHERE working_times.date = ? AND working_times.invoice = ?
								AND working_times.datetime = ?");
		$sql->bind_param('sss', $date, $invoice, $datetime);
		$sql->execute();
		$result = $sql->get_result();
		return mysqli_fetch_assoc($result);
	}

	/**
	* @test
	*/
	public function When_Save_New_WorkingTimes_Then_Its_Data_Exists_In_Database()	//Tests Save function
	{
		global $conn;
		$conn->query("DELETE FROM employees");	//Clears DB
		$conn->query("DELETE FROM working_times");	//Clears DB
		//Arrange
		$employee = new Employee();
		$employee->name = "Noudle";
		$employee->last_name = "Hooke";
		$employee->person_id = "683966-68396";
		$employee->place = "Skirotava";
		$employee->shift = NULL;
		$employee->capacity_rate = NULL;
		$employee->hour_rate = NULL;
		$employee->working_from = "2017-10-11";
		$employee->working_to = NULL;

		$working_times = new WorkingTimes();
		$working_times->date = "2018-04-10";
		$working_times->datetime = "2018-04-10 12:30:00";
		$working_times->invoice = NULL;
		$working_times->working_hours = "8";

		//Act
		$employee->Save();
		$working_times->employee_id = $employee->id;
		$working_times->Save();

		//Assert
		$sql = $conn->prepare("SELECT working_times.* FROM working_times WHERE employee_id = ? AND date = ? AND datetime = ?");
		$sql->bind_param('sss', $employee->id, $working_times->date, $working_times->datetime);
		$sql->execute();
		$result = $sql->get_result();
		$result = mysqli_fetch_assoc($result);

		$this->assertEquals($result['working_hours'], $working_times->working_hours);
	}

	/**
	* @test
	*/
	public function When_Save_New_Bureau_Employee_WorkingTimes_Then_Its_Data_Can_Be_Deleted()	//Tests DeleteAllWorkingBureauEmployees function
	{
		//Arrange
		$employee = new Employee();
		$employee->name = "Nugge";
		$employee->last_name = "Immerse";
		$employee->person_id = "125144-12514";
		$employee->place = "Birojs";
		$employee->shift = NULL;
		$employee->capacity_rate = NULL;
		$employee->hour_rate = NULL;
		$employee->working_from = "2012-05-14";
		$employee->working_to = NULL;

		$workingTimesOne = new WorkingTimes();
		$workingTimesOne->date = "2017-08-14";
		$workingTimesOne->datetime = "2017-08-14 12:00:00";
		$workingTimesOne->invoice = NULL;
		$workingTimesOne->working_hours = "8";

		$workingTimesTwo = new WorkingTimes();
		$workingTimesTwo->date = "2017-08-15";
		$workingTimesTwo->datetime = "2017-08-15 12:00:00";
		$workingTimesTwo->invoice = NULL;
		$workingTimesTwo->working_hours = "8";

		$workingTimesThree = new WorkingTimes();
		$workingTimesThree->date = "2017-09-15";
		$workingTimesThree->datetime = "2017-09-15 12:00:00";
		$workingTimesThree->invoice = NULL;
		$workingTimesThree->working_hours = "8";

		$workingTimes = new WorkingTimes();

		//Act
		$employee->Save();
		$workingTimesOne->employee_id = $employee->id;
		$workingTimesOne->Save();
		$workingTimesTwo->employee_id = $employee->id;
		$workingTimesTwo->Save();
		$workingTimesThree->employee_id = $employee->id;
		$workingTimesThree->Save();

		$workingTimes->DeleteAllWorkingBureauEmployees($employee->id, "2017-08");

		//Assert
		$this->assertNull(WorkingTimesTests::GetEmployeeWorkingTime($employee->id, $workingTimesOne->date, $workingTimesOne->datetime));
		$this->assertNull(WorkingTimesTests::GetEmployeeWorkingTime($employee->id, $workingTimesTwo->date, $workingTimesTwo->datetime));
		$this->assertNotNull(WorkingTimesTests::GetEmployeeWorkingTime($employee->id, $workingTimesThree->date, $workingTimesThree->datetime));
	}

	/**
	* @test
	*/
	public function When_Save_New_Employees_WorkingTimes_Then_Its_Data_Can_Be_Deleted()	//Tests DeleteAllWorkingEmployees function
	{
		//Arrange
		$employeeOne = new Employee();
		$employeeOne->name = "Divide";
		$employeeOne->last_name = "Thorium";
		$employeeOne->person_id = "157754-15775";
		$employeeOne->place = "Zagetava";
		$employeeOne->shift = "1";
		$employeeOne->capacity_rate = "1.03";
		$employeeOne->hour_rate = "2.47";
		$employeeOne->working_from = "2011-03-14";
		$employeeOne->working_to = NULL;

		$employeeTwo = new Employee();
		$employeeTwo->name = "Accentor";
		$employeeTwo->last_name = "Kuwaiti";
		$employeeTwo->person_id = "472544-47254";
		$employeeTwo->place = "Zagetava";
		$employeeTwo->shift = "1";
		$employeeTwo->capacity_rate = "1.03";
		$employeeTwo->hour_rate = "2.47";
		$employeeTwo->working_from = "2011-03-14";
		$employeeTwo->working_to = NULL;

		$invoice = "1";
		$date = "2017-08-14";
		$timestamp = "2017-08-14 12:00:00";

		$workingTimes = new WorkingTimes();
		$workingTimes->date = $date;
		$workingTimes->datetime = $timestamp;
		$workingTimes->invoice = $invoice;
		$workingTimes->working_hours = "8";

		//Act
		$employeeOne->Save();
		$employeeTwo->Save();
		$workingTimes->employee_id = $employeeOne->id;
		$workingTimes->Save();
		$workingTimes->employee_id = $employeeTwo->id;
		$workingTimes->Save();

		$workingTimes->DeleteAllWorkingEmployees($invoice, $date, $timestamp);

		//Assert
		$this->assertNull(WorkingTimesTests::GetEmployeesWorkingTime($invoice, $date, $timestamp));
	}

	/**
	* @test
	*/
	public function When_Save_New_Employee_WorkingTimes_Then_Its_Data_Can_Loaded()	//Tests GetWorkersWorkingTime function
	{
		//Arrange
		$employee = new Employee();
		$employee->name = "Ordinal";
		$employee->last_name = "Cooke";
		$employee->person_id = "885316-88531";
		$employee->place = "Skirotava";
		$employee->shift = NULL;
		$employee->capacity_rate = NULL;
		$employee->hour_rate = NULL;
		$employee->working_from = "2008-05-16";
		$employee->working_to = NULL;

		$workingTimes = new WorkingTimes();
		$workingTimes->date = "2008-06-16";
		$workingTimes->datetime = "2008-06-16 11:39:00";
		$workingTimes->invoice = "5";
		$workingTimes->working_hours = "8";

		//Act
		$employee->Save();
		$workingTimes->employee_id = $employee->id;
		$workingTimes->Save();

		//Assert
		$result = WorkingTimes::GetWorkersWorkingTime($employee->id, $workingTimes->date, $workingTimes->invoice, $workingTimes->datetime);
		$this->assertEquals($result['date'], $workingTimes->date);
	}
}