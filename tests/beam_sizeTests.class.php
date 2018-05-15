<?php

require_once "C:/wamp64/www/pieraksts/includes/sawmill_production.class.php";
require_once "C:/wamp64/www/pieraksts/includes/beam_size.class.php";

use PHPUnit\Framework\TestCase;

//Test is divided in 3 parts:
//1. Arrange - prepares data
//2. Act - sets data
//3. Assert - tests data

class BeamSizeTests extends TestCase
{
	public static function GetBeamSize($id)
	{
		global $conn;

		$sql = $conn->prepare("SELECT beam_sizes.* FROM beam_sizes
								WHERE beam_sizes.id = ?");
		$sql->bind_param('s', $id);
		$sql->execute();
		$result = $sql->get_result();

		return mysqli_fetch_assoc($result);
	}

	/**
	* @test
	*/
	public function When_Save_New_Beam_Size_Then_It_Can_Be_Loaded()	//Tests Save function
	{
		global $conn;
		$conn->query("DELETE FROM beam_sizes");	//Clears DB
		$conn->query("DELETE FROM sawmill_productions");	//Clears DB
		//Arrange
		$size = new BeamSize();
		$size->size = "0.165";

		//Act
		$size->Save();

		//Assert
		$result = BeamSizeTests::GetBeamSize($size->id);
		$this->assertEquals($result['size'], $size->size);
	}

	/**
	* @test
	*/
	public function When_Save_New_Beam_Size_Then_It_Can_Be_Updated()	//Tests Update function
	{
		//Arrange
		$size = new BeamSize();
		$size->size = "0.12";

		$sizeUpdate = new BeamSize();
		$sizeUpdate->size = "0.131";

		//Act
		$size->Save();
		$sizeUpdate->id = $size->id;
		$sizeUpdate->Update();

		//Assert
		$result = BeamSizeTests::GetBeamSize($size->id);
		$this->assertEquals($result['size'], $sizeUpdate->size);
		$this->assertNotEquals($result['size'], $size->size);
	}

	/**
	* @test
	*/
	public function When_Save_New_Beam_Size_Then_It_Can_Be_Deleted()	//Tests Delete function
	{
		//Arrange
		$size = new BeamSize();
		$size->size = "0.05";

		$sizeDelete = new BeamSize();

		//Act
		$size->Save();
		$sizeDelete->id = $size->id;
		$sizeDelete->Delete();

		//Assert
		$this->assertNull(BeamSizeTests::GetBeamSize($size->id));
	}

	/**
	* @test
	*/
	public function When_Save_New_Beam_Size_Then_ExistsSize_In_Database()	//Tests ExistsSize function
	{
		//Arrange
		$size = new BeamSize();
		$size->size = "0.07";

		//Act
		$size->Save();

		//Assert
		$this->assertTrue(BeamSize::ExistsSize($size->size));
	}

	/**
	* @test
	*/
	public function When_Save_New_Beam_Size_Then_ExistsId_In_Database()	//Tests ExistsId function
	{
		//Arrange
		$size = new BeamSize();
		$size->size = "0.121";

		//Act
		$size->Save();

		//Assert
		$this->assertTrue(BeamSize::ExistsId($size->id));
	}

	/**
	* @test
	*/
	public function When_Save_New_Beam_Size_Then_Get_Size_In_Database()	//Tests Get function
	{
		//Arrange
		$size = new BeamSize();
		$size->size = "1.012";

		//Act
		$size->Save();

		//Assert
		$this->assertNotNull(BeamSize::Get($size->id));
	}

	/**
	* @test
	*/
	public function When_Save_New_Beam_Size_Then_Check_IsSizeUsed()	//Tests IsSizeUsed function
	{
		//Arrange
		$sizeOne = new BeamSize();
		$sizeOne->size = "3.1";

		$sizeTwo = new BeamSize();
		$sizeTwo->size = "3.2";

		$sawmillProduction = new SawmillProduction();
		$sawmillProduction->date = "2017-03-31";
		$sawmillProduction->datetime = "2017-03-31 14:01:19";
		$sawmillProduction->time_from = "06:00";
		$sawmillProduction->time_to = "12:30";
		$sawmillProduction->invoice = "1000";
		$sawmillProduction->beam_count = "820";
		$sawmillProduction->beam_capacity = "4340.89";
		$sawmillProduction->lumber_count = "21";
		$sawmillProduction->lumber_capacity = "23.4";
		$sawmillProduction->percentage = "47.1";
		$sawmillProduction->note = NULL;

		//Act
		$sizeOne->Save();
		$sizeTwo->Save();
		$sawmillProduction->beam_size_id = $sizeTwo->id;
		$sawmillProduction->Save();

		//Assert
		$this->assertFalse(BeamSize::IsSizeUsed($sizeOne->id));
		$this->assertTrue(BeamSize::IsSizeUsed($sizeTwo->id));
	}

	/**
	* @test
	*/
	public function When_Save_New_Beam_Size_Then_Check_CurrentBeamSizeExists()	//Tests Get function
	{
		//Arrange
		$sizeOne = new BeamSize();
		$sizeOne->size = "2.005";

		$sizeTwo = new BeamSize();
		$sizeTwo->size = "3.451";

		//Act
		$sizeOne->Save();
		$sizeTwo->Save();

		//Assert
		$this->assertFalse(BeamSize::CurrentBeamSizeExists($sizeOne->size, $sizeOne->id));
		$this->assertTrue(BeamSize::CurrentBeamSizeExists($sizeOne->size, $sizeTwo->id));
	}
}