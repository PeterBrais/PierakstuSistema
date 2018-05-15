<?php

require_once "C:/wamp64/www/pieraksts/includes/times.class.php";
require_once "C:/wamp64/www/pieraksts/includes/employee.class.php";

use PHPUnit\Framework\TestCase;

//Test is divided in 3 parts:
//1. Arrange - prepares data
//2. Act - sets data
//3. Assert - tests data

class TimesTests extends TestCase
{
	static function GetEmployeesNonWorkingTime($invoice, $date, $datetime)	//Returns one shifts nonworking times
	{
		global $conn;
		$sql = $conn->prepare("SELECT times.* FROM employees JOIN times 
								ON times.employee_id = employees.id
								WHERE times.date = ? AND times.invoice = ? AND times.datetime = ?");
		$sql->bind_param('sss', $date, $invoice, $datetime);
		$sql->execute();
		$result = $sql->get_result();
		return mysqli_fetch_assoc($result);
	}

	static function GetEmployeeNonWorkingTime($id, $date, $datetime)	//Returns employees nonworking times
	{
		global $conn;
		$sql = $conn->prepare("SELECT times.* FROM employees
								JOIN times ON times.employee_id = employees.id
								WHERE times.date = ? AND employees.id = ? AND times.invoice
								IS NULL AND times.datetime = ?");
		$sql->bind_param('sss', $date, $id, $datetime);
		$sql->execute();
		$result = $sql->get_result();
		return mysqli_fetch_assoc($result);
	}

	/**
	* @test
	*/
	public function When_Save_New_Times_Then_Its_Data_Exists_In_Database()	//Tests Save function
	{
		global $conn;
		$conn->query("DELETE FROM employees");	//Clears DB
		$conn->query("DELETE FROM times");	//Clears DB
		//Arrange
		$employee = new Employee();
		$employee->name = "Segno";
		$employee->last_name = "Cracking";
		$employee->person_id = "402580-40258";
		$employee->place = "Skirotava";
		$employee->shift = NULL;
		$employee->capacity_rate = NULL;
		$employee->hour_rate = NULL;
		$employee->working_from = "2017-12-25";
		$employee->working_to = NULL;

		$times = new Times();
		$times->vacation = "A";
		$times->sick_leave = NULL;
		$times->nonattendance = NULL;
		$times->date = "2018-01-03";
		$times->datetime = "2018-01-03 06:00:00";
		$times->invoice = NULL;
		$times->pregnancy = NULL;

		//Act
		$employee->Save();
		$times->employee_id = $employee->id;
		$times->Save();

		//Assert
		$sql = $conn->prepare("SELECT times.* FROM times WHERE employee_id = ? AND date = ? AND datetime = ?");
		$sql->bind_param('sss', $employee->id, $times->date, $times->datetime);
		$sql->execute();
		$result = $sql->get_result();
		$result = mysqli_fetch_assoc($result);

		$this->assertEquals($result['vacation'], $times->vacation);
		$this->assertEquals($result['nonattendace'], $times->nonattendance);
	}

	/**
	* @test
	*/
	public function When_Save_New_Times_Then_Its_Data_Can_Be_Loaded()	//Tests GetWorkersNonWorkingTime function
	{
		//Arrange
		$employee = new Employee();
		$employee->name = "Twitchy";
		$employee->last_name = "Elf";
		$employee->person_id = "773682-77368";
		$employee->place = "Zagetava";
		$employee->shift = "2";
		$employee->capacity_rate = "3.47";
		$employee->hour_rate = "1.32";
		$employee->working_from = "2007-06-28";
		$employee->working_to = NULL;

		$times = new Times();
		$times->vacation = NULL;
		$times->sick_leave = "S";
		$times->nonattendance = NULL;
		$times->date = "2008-02-10";
		$times->datetime = "2018-02-10 07:22:10";
		$times->invoice = "6";
		$times->pregnancy = NULL;

		//Act
		$employee->Save();
		$times->employee_id = $employee->id;
		$times->Save();

		//Assert
		$result = Times::GetWorkersNonWorkingTime($employee->id, $times->date, $times->invoice, $times->datetime);
		$this->assertEquals($result['date'], $times->date);
	}

	/**
	* @test
	*/
	public function When_Save_New_Employees_Times_Then_Its_Data_Can_Be_Deleted()	//Tests DeleteAllNonWorkingEmployees function
	{
		//Arrange
		$employeeOne = new Employee();
		$employeeOne->name = "Murmur";
		$employeeOne->last_name = "Stairs";
		$employeeOne->person_id = "1344486-34448";
		$employeeOne->place = "Skirotava";
		$employeeOne->shift = NULL;
		$employeeOne->capacity_rate = NULL;
		$employeeOne->hour_rate = NULL;
		$employeeOne->working_from = "2013-04-06";
		$employeeOne->working_to = NULL;

		$employeeTwo = new Employee();
		$employeeTwo->name = "Minister";
		$employeeTwo->last_name = "Brilde";
		$employeeTwo->person_id = "143974-14397";
		$employeeTwo->place = "Skirotava";
		$employeeTwo->shift = NULL;
		$employeeTwo->capacity_rate = NULL;
		$employeeTwo->hour_rate = "NULL";
		$employeeTwo->working_from = "2014-09-27";
		$employeeTwo->working_to = NULL;

		$invoice = "7";
		$date = "2016-06-01";
		$datetime = "2016-06-01 12:10:39";

		$times = new Times();
		$times->vacation = NULL;
		$times->sick_leave = NULL;
		$times->nonattendance = "N";
		$times->date = $date;
		$times->datetime = $datetime;
		$times->invoice = $invoice;
		$times->pregnancy = NULL;

		//Act
		$employeeOne->Save();
		$employeeTwo->Save();
		$times->employee_id = $employeeOne->id;
		$times->Save();
		$times->employee_id = $employeeTwo->id;
		$times->Save();

		$times->DeleteAllNonWorkingEmployees($invoice, $date, $datetime);

		//Assert
		$this->assertNull(TimesTests::GetEmployeesNonWorkingTime($invoice, $date, $datetime));
	}

	/**
	* @test
	*/
	public function When_Save_New_Bureau_Employee_Times_Then_Its_Data_Can_Be_Deleted()	//Tests DeleteAllNonWorkingBureauEmployees function
	{
		//Arrange
		$employee = new Employee();
		$employee->name = "Ischemic";
		$employee->last_name = "Mode";
		$employee->person_id = "861200-86120";
		$employee->place = "Zagetava";
		$employee->shift = "2";
		$employee->capacity_rate = "3.21";
		$employee->hour_rate = "2.78";
		$employee->working_from = "2002-12-01";
		$employee->working_to = NULL;

		$timesOne = new Times();
		$timesOne->vacation = NULL;
		$timesOne->sick_leave = NULL;
		$timesOne->nonattendance = "N";
		$timesOne->date = "2003-02-14";
		$timesOne->datetime = "2003-02-14 10:00:08";
		$timesOne->invoice = NULL;
		$timesOne->pregnancy = NULL;

		$timesTwo = new Times();
		$timesTwo->vacation = NULL;
		$timesTwo->sick_leave = "S";
		$timesTwo->nonattendance = NULL;
		$timesTwo->date = "2003-02-15";
		$timesTwo->datetime = "2003-02-15 10:10:09";
		$timesTwo->invoice = NULL;
		$timesTwo->pregnancy = NULL;

		$timesThree = new Times();
		$timesThree->vacation = "A";
		$timesThree->sick_leave = NULL;
		$timesThree->nonattendance = NULL;
		$timesThree->date = "2003-03-12";
		$timesThree->datetime = "2003-03-12 09:00:00";
		$timesThree->invoice = NULL;
		$timesThree->pregnancy = NULL;

		$times = new Times();

		//Act
		$employee->Save();
		$timesOne->employee_id = $employee->id;
		$timesOne->Save();
		$timesTwo->employee_id = $employee->id;
		$timesTwo->Save();
		$timesThree->employee_id = $employee->id;
		$timesThree->Save();

		$times->DeleteAllNonWorkingBureauEmployees($employee->id, "2003-02");

		//Assert
		$this->assertNull(TimesTests::GetEmployeeNonWorkingTime($employee->id, $timesOne->date, $timesOne->datetime));
		$this->assertNull(TimesTests::GetEmployeeNonWorkingTime($employee->id, $timesTwo->date, $timesTwo->datetime));
		$this->assertNotNull(TimesTests::GetEmployeeNonWorkingTime($employee->id, $timesThree->date, $timesThree->datetime));
	}
}