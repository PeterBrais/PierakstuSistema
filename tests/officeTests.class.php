<?php

require_once "C:/wamp64/www/pieraksts/includes/office.class.php";
require_once "C:/wamp64/www/pieraksts/includes/employee.class.php";
require_once "C:/wamp64/www/pieraksts/includes/working_times.class.php";
require_once "C:/wamp64/www/pieraksts/includes/times.class.php";

use PHPUnit\Framework\TestCase;

//Test is divided in 3 parts:
//1. Arrange - prepares data
//2. Act - sets data
//3. Assert - tests data

class OfficeTests extends TestCase
{
	public static function ClearDatabase()
	{
		global $conn;
		$conn->query("DELETE FROM employees");	//Clears DB
		$conn->query("DELETE FROM working_times");	//Clears DB
		$conn->query("DELETE FROM times");	//Clears DB
	}

	/**
	* @test
	*/
	public function When_Save_New_Office_Employees_Then_GetOfficeEmployeesByDate()	//Tests GetOfficeEmployeesByDate function
	{
		OfficeTests::ClearDatabase();
		//Arrange
		$employeeOne = new Employee();
		$employeeOne->name = "Amused";
		$employeeOne->last_name = "Inventory";
		$employeeOne->person_id = "691939-69193";
		$employeeOne->place = "Birojs";
		$employeeOne->shift = NULL;
		$employeeOne->capacity_rate = NULL;
		$employeeOne->hour_rate = NULL;
		$employeeOne->working_from = "2010-01-01";
		$employeeOne->working_to = NULL;

		$employeeTwo = new Employee();
		$employeeTwo->name = "Etheral";
		$employeeTwo->last_name = "Fling";
		$employeeTwo->person_id = "346806-34680";
		$employeeTwo->place = "Birojs";
		$employeeTwo->shift = NULL;
		$employeeTwo->capacity_rate = NULL;
		$employeeTwo->hour_rate = NULL;
		$employeeTwo->working_from = "2010-01-01";
		$employeeTwo->working_to = "2010-02-01";

		//Act
		$employeeOne->Save();
		$employeeTwo->Save();

		//Assert
		$allEmployees = Office::GetOfficeEmployeesByDate("2010-03");
		$this->assertEquals(1, count($allEmployees));	//Only employeeOne

		foreach($allEmployees as $employee)
		{
			$this->assertEquals($employeeOne->person_id, $employee['person_id']);
		}
	}

	/**
	* @test
	*/
	public function When_Save_New_Office_Employee_Then_ExistsBureauEmployeeWithID_In_Database()	//Tests ExistsBureauEmployeeWithID function
	{
		//Arrange
		$employee = new Employee();
		$employee->name = "Cultured";
		$employee->last_name = "Shamrock";
		$employee->person_id = "768293-76829";
		$employee->place = "Birojs";
		$employee->shift = NULL;
		$employee->capacity_rate = NULL;
		$employee->hour_rate = NULL;
		$employee->working_from = "2007-04-13";
		$employee->working_to = NULL;

		//Act
		$employee->Save();

		//Assert
		$this->assertTrue(Office::ExistsBureauEmployeeWithID($employee->id));
	}

	/**
	* @test
	*/
	public function When_Save_New_Office_Employee_Then_Get_BureauEmployeeWorkingTimes()	//Tests BureauEmployeeWorkingTimes function
	{
		$date = "2018-04-10";
		//Arrange
		$employee = new Employee();
		$employee->name = "Jamaican";
		$employee->last_name = "Bullocks";
		$employee->person_id = "262796-26279";
		$employee->place = "Birojs";
		$employee->shift = NULL;
		$employee->capacity_rate = NULL;
		$employee->hour_rate = NULL;
		$employee->working_from = "2015-10-01";
		$employee->working_to = NULL;

		$working_times = new WorkingTimes();
		$working_times->date = $date;
		$working_times->datetime = "2018-04-10 12:30:00";
		$working_times->invoice = NULL;
		$working_times->working_hours = "8";

		//Act
		$employee->Save();
		$working_times->employee_id = $employee->id;
		$working_times->Save();

		//Assert
		$workingHours = Office::BureauEmployeeWorkingTimes($employee->id, $date);
		$this->assertEquals($workingHours['working_hours'], $working_times->working_hours);
	}

	/**
	* @test
	*/
	public function When_Save_New_Office_Employee_Then_Get_BureauEmployeeNonWorkingTimes()	//Tests BureauEmployeeNonWorkingTimes function
	{
		$date = "2018-04-10";
		$nonworking = "S";
		//Arrange
		$employee = new Employee();
		$employee->name = "Squelch";
		$employee->last_name = "Crooked";
		$employee->person_id = "287945-28794";
		$employee->place = "Birojs";
		$employee->shift = NULL;
		$employee->capacity_rate = NULL;
		$employee->hour_rate = NULL;
		$employee->working_from = "2015-10-01";
		$employee->working_to = NULL;

		$times = new Times();
		$times->vacation = NULL;
		$times->sick_leave = $nonworking;
		$times->nonattendance = NULL;
		$times->date = $date;
		$times->datetime = "2018-04-10 12:30:00";
		$times->invoice = NULL;
		$times->pregnancy = NULL;

		//Act
		$employee->Save();
		$times->employee_id = $employee->id;
		$times->Save();

		//Assert
		$nonworkingTimes = Office::BureauEmployeeNonWorkingTimes($employee->id, $date);
		$this->assertEquals($nonworkingTimes['nonworking'], $nonworking);
	}

	/**
	* @test
	*/
	public function When_Save_New_Office_Employee_Then_Get_BureauEmployeeWorkingStatistic()	//Tests BureauEmployeeWorkingStatistic function
	{
		$date = "2018-04-12";
		$hours = "8";
		//Arrange
		$employee = new Employee();
		$employee->name = "Debris";
		$employee->last_name = "Fell";
		$employee->person_id = "528337-52833";
		$employee->place = "Birojs";
		$employee->shift = NULL;
		$employee->capacity_rate = NULL;
		$employee->hour_rate = NULL;
		$employee->working_from = "2012-02-11";
		$employee->working_to = NULL;

		$working_times = new WorkingTimes();
		$working_times->date = $date;
		$working_times->datetime = "2018-04-12 12:30:00";
		$working_times->invoice = NULL;
		$working_times->working_hours = $hours;
		$working_times->overtime_hours = $hours;

		//Act
		$employee->Save();
		$working_times->employee_id = $employee->id;
		$working_times->Save();

		//Assert
		$workingStatistic = Office::BureauEmployeeWorkingStatistic($employee->id, "2018-04");
		$this->assertEquals(1, $workingStatistic['working_days']);
		$this->assertEquals($hours, $workingStatistic['working_hours']);
		$this->assertEquals($hours, $workingStatistic['overtime_hours']);
	}

	/**
	* @test
	*/
	public function When_Save_New_Office_Employee_Then_Get_BureauEmployeeNonWorkingStatistic()	//Tests BureauEmployeeNonWorkingStatistic function
	{
		$date = "2018-04-13";
		$nonworking = "P";
		//Arrange
		$employee = new Employee();
		$employee->name = "Moraine";
		$employee->last_name = "Sensors";
		$employee->person_id = "329285-32928";
		$employee->place = "Birojs";
		$employee->shift = NULL;
		$employee->capacity_rate = NULL;
		$employee->hour_rate = NULL;
		$employee->working_from = "2012-02-11";
		$employee->working_to = NULL;

		$times = new Times();
		$times->vacation = NULL;
		$times->sick_leave = NULL;
		$times->nonattendance = NULL;
		$times->date = $date;
		$times->datetime = "2018-04-13 11:40:00";
		$times->invoice = NULL;
		$times->pregnancy = $nonworking;

		//Act
		$employee->Save();
		$times->employee_id = $employee->id;
		$times->Save();

		//Assert
		$nonworkingStatistic = Office::BureauEmployeeNonWorkingStatistic($employee->id, "2018-04");
		$this->assertEquals(1, $nonworkingStatistic['nonworking_days']);
		$this->assertEquals(0, $nonworkingStatistic['vacation']);
		$this->assertEquals(1, $nonworkingStatistic['pregnancy']);
	}

	/**
	* @test
	*/
	public function Get_Number_Of_Month_Weekdays()	//Tests ExistsBureauEmployeeWithID function
	{
		//Arrange
		$period = "2018-04";	//April = 21 weekdays
		$year = date('Y', strtotime($period));
		$month = date('n', strtotime($period));

		//Act
		$weekdayCount = Office::Weekdays($month, $year);

		//Assert
		$this->assertEquals(21, $weekdayCount);
	}
}