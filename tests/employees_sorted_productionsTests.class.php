<?php

require_once "C:/wamp64/www/pieraksts/includes/sorted_production.class.php";
require_once "C:/wamp64/www/pieraksts/includes/sorting_production.class.php";
require_once "C:/wamp64/www/pieraksts/includes/employees_sorted_productions.class.php";
require_once "C:/wamp64/www/pieraksts/includes/employee.class.php";

use PHPUnit\Framework\TestCase;

//Test is divided in 3 parts:
//1. Arrange - prepares data
//2. Act - sets data
//3. Assert - tests data

class EmployeeSortedProductionsTests extends TestCase
{
	public static function GetSortedProductionData($id)
	{
		global $conn;
		$sql = $conn->prepare("SELECT employees_sorted_productions.* FROM employees_sorted_productions 
								WHERE sorted_id = ?");
		$sql->bind_param('s', $id);
		$sql->execute();
		$result = $sql->get_result();
		return mysqli_fetch_assoc($result);
	}

	/**
	* @test
	*/
	public function When_Save_New_EmployeeSortedProduction_Then_It_Exists_In_Database()	//Tests Save function
	{
		global $conn;
		$conn->query("DELETE FROM employees");	//Clears DB
		$conn->query("DELETE FROM sorted_productions");	//Clears DB
		$conn->query("DELETE FROM sorting_productions");	//Clears DB
		$conn->query("DELETE FROM employees_sorted_productions");	//Clears DB
		//Arrange
		$employee = new Employee();
		$employee->name = "Tugofwar";
		$employee->last_name = "Agitated";
		$employee->person_id = "570377-57037";
		$employee->place = "Skirotava";
		$employee->shift = NULL;
		$employee->capacity_rate = NULL;
		$employee->hour_rate = NULL;
		$employee->working_from = "2013-10-31";
		$employee->working_to = NULL;

		$sortingProduction = new SortingProduction();
		$sortingProduction->date = "2017-03-17";
		$sortingProduction->datetime = "2017-03-17 16:12:32";
		$sortingProduction->time_from = "14:30";
		$sortingProduction->time_to = "23:00";
		$sortingProduction->invoice = "1000";
		$sortingProduction->thickness = "40";
		$sortingProduction->width = "30";
		$sortingProduction->length = "3000";
		$sortingProduction->count = "328";
		$sortingProduction->capacity = "452.23";
		$sortingProduction->defect_count = NULL;
		$sortingProduction->reserved = 0;
		
		$sortedProduction = new SortedProduction();
		$sortedProduction->type = "S";
		$sortedProduction->count = "328";
		$sortedProduction->thickness = "40";
		$sortedProduction->width = "30";
		$sortedProduction->length = "3000";
		$sortedProduction->capacity = "452.23";
		$sortedProduction->capacity_per_piece = "0.31278";

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
		$result = EmployeeSortedProductionsTests::GetSortedProductionData($sortedProduction->id);
		$this->assertEquals($result['employee_id'], $employee->id);
		$this->assertEquals($result['sorted_id'], $sortedProduction->id);
	}

	/**
	* @test
	*/
	public function When_Save_New_EmployeeSortedProduction_Then_It_Can_Be_Deleted()	//Tests Delete function
	{
		//Arrange
		$employee = new Employee();
		$employee->name = "Tricky";
		$employee->last_name = "Biddy";
		$employee->person_id = "420129-42012";
		$employee->place = "Skirotava";
		$employee->shift = NULL;
		$employee->capacity_rate = NULL;
		$employee->hour_rate = NULL;
		$employee->working_from = "2004-12-29";
		$employee->working_to = NULL;

		$sortingProduction = new SortingProduction();
		$sortingProduction->date = "2016-03-24";
		$sortingProduction->datetime = "2016-03-24 15:41:22";
		$sortingProduction->time_from = "06:00";
		$sortingProduction->time_to = "14:30";
		$sortingProduction->invoice = "1001";
		$sortingProduction->thickness = "42";
		$sortingProduction->width = "35";
		$sortingProduction->length = "3800";
		$sortingProduction->count = "735";
		$sortingProduction->capacity = "534.13";
		$sortingProduction->defect_count = "1";
		$sortingProduction->reserved = 0;
		
		$sortedProductionOne = new SortedProduction();
		$sortedProductionOne->type = "S";
		$sortedProductionOne->count = "700";
		$sortedProductionOne->thickness = "42";
		$sortedProductionOne->width = "35";
		$sortedProductionOne->length = "3800";
		$sortedProductionOne->capacity = "501.34";
		$sortedProductionOne->capacity_per_piece = "0.00924";

		$sortedProductionTwo = new SortedProduction();
		$sortedProductionTwo->type = "G";
		$sortedProductionTwo->count = "35";
		$sortedProductionTwo->thickness = "40";
		$sortedProductionTwo->width = "30";
		$sortedProductionTwo->length = "3000";
		$sortedProductionTwo->capacity = "30.458";
		$sortedProductionTwo->capacity_per_piece = "0.00043";

		//Act
		$employee->Save();
		$sortingProduction->Save();
		$sortedProductionOne->sorting_id = $sortingProduction->id;
		$sortedProductionOne->Save();
		$sortedProductionTwo->sorting_id = $sortingProduction->id;
		$sortedProductionTwo->Save();
		$employeesSortedProcutions = new EmployeeSortedProductions();
		$employeesSortedProcutions->employee_id = $employee->id;
		$employeesSortedProcutions->sorted_id = $sortedProductionOne->id;
		$employeesSortedProcutions->Save();
		$employeesSortedProcutions->sorted_id = $sortedProductionTwo->id;
		$employeesSortedProcutions->Save();

		$employeesSortedProcutions->DeleteAllSortedProductionEmployees($sortedProductionOne->id);

		//Assert
		$resultOne = EmployeeSortedProductionsTests::GetSortedProductionData($sortedProductionOne->id);
		$this->assertEquals(count($resultOne), 0);
		$resultTwo = EmployeeSortedProductionsTests::GetSortedProductionData($sortedProductionTwo->id);
		$this->assertEquals($resultTwo['sorted_id'], $sortedProductionTwo->id);
	}
}