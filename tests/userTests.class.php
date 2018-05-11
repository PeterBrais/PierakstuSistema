<?php

require_once "C:\wamp64\www\pieraksts\includes\user.class.php";

use PHPUnit\Framework\TestCase;

		//Testam ir trīs daļas
		//1. Arrange - sagtavo datus
		//2. Act
		//3. Assert

class UserTests extends TestCase
{
	/**
	* @test
	*/
	public function When_Save_New_User_Then_It_Exists_In_Database()		//Tests Exists function
	{
		//Arrange
		$user = new User();
		$user->username = "Vards";
		$user->role = "a";
		$user->SetPassword("Qwerty1@");

		//Act
		$user->Save();

		//Assert
		$this->assertTrue(User::Exists($user->username));
	}

	/**
	* @test
	*/
	public function When_Save_New_Users_Then_Saved_Usernames_Already_Exists()	//Tests CurrentUserUsernameExists function
	{
		//Arrange
		$userOne = new User();
		$userOne->username = "VardsA";
		$userOne->role = "a";
		$userOne->SetPassword("Qwerty1@");

		$userTwo = new User();
		$userTwo->username = "VardsB";
		$userTwo->role = "p";
		$userTwo->SetPassword("Qwerty@1");

		//Act
		$userOne->Save();
		$userTwo->Save();

		//Assert
		$this->assertFalse(User::CurrentUserUsernameExists($userOne->username, $userOne->id));
		$this->assertTrue(User::CurrentUserUsernameExists($userOne->username, $userTwo->id));
	}

	/**
	* @test
	*/
	public function When_Save_New_User_Then_Its_Data_Can_Be_Updated()	
	{
		$user = new User();
		$user->username = "Lietotajvards";
		$user->role = "l";
		$user->SetPassword("Qwerty1@");

		$userUpdate = new User();
		$userUpdate->username = "LietotajaVards";
		$userUpdate->role = "l";
		$userUpdate->id = $user->id;

		$user->Save();
		$userUpdate->Update();

		$this->assertNotEquals($userUpdate->username, $user->username);
	}

	public function getTearDownOperation() {
		return PHPUnit_Extensions_Database_Operation_Factory::TRUNCATE();
	}
}