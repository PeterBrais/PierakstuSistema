<?php

require_once "C:\wamp64\www\pieraksts\includes\user.class.php";
require_once "C:\wamp64\www\pieraksts\includes\administrator.class.php";

use PHPUnit\Framework\TestCase;

//Test is divided in 3 parts:
//1. Arrange - prepares data
//2. Act - sets data
//3. Assert - tests data


class UserTests extends TestCase
{
	/**
	* @test
	*/
	public function When_Save_New_User_Then_It_Exists_In_Database()		//Tests Exists function
	{
		global $conn;
		$conn->query("DELETE FROM users");	//Clears DB
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
		//Gets data from table
		$resultOne = Administrator::GetUsersData($userOne->id);
		$resultTwo = Administrator::GetUsersData($userTwo->id);
		$this->assertFalse(User::CurrentUserUsernameExists($resultOne['username'], $userOne->id));
		$this->assertTrue(User::CurrentUserUsernameExists($resultOne['username'], $userTwo->id));
	}

	/**
	* @test
	*/
	public function When_Save_New_User_Then_Its_Data_Can_Be_Updated()	//Test Update function
	{
		//Arranga
		$user = new User();
		$user->username = "Lietotajvards";
		$user->role = "l";
		$user->SetPassword("Qwerty1@");

		$userUpdate = new User();
		$userUpdate->username = "LietotajaVards";
		$userUpdate->role = "l";

		//Act
		$user->Save();
		$userUpdate->id = $user->id;
		$userUpdate->Update();

		//Assert
		//Gets data from table
		$result = Administrator::GetUsersData($user->id);

		$this->assertNotEquals($result['username'], $user->username);
	}

	/**
	* @test
	*/
	public function When_Save_New_User_Then_It_Can_Be_Blocked_Or_Unblocked()	//Tests Delete function
	{
		//Arrange
		$user = new User();
		$user->username = "Username1";
		$user->role = "a";
		$user->SetPassword("Password1");

		$userDelete = new User();
		$userDelete->active = 0;

		//Act
		$user->Save();
		$userDelete->id = $user->id;
		$userDelete->Delete();

		//Assert
		//Gets data from table
		$result = Administrator::GetUsersData($user->id);

		$this->assertEquals($userDelete->active, $result['active']);
	}

	/**
	* @test
	*/
	public function When_Save_New_User_Then_Its_Password_Can_Be_Changed()	//Tests UpdatePassword function
	{
		//Arrange
		$user = new User();
		$user->username = "RUserR100";
		$user->role = "p";
		$user->SetPassword("Sg8Hs*alnYhha");

		$userNew = new User();
		$userNew->SetPassword("Ch@n5eDp@ssW046");

		//Act
		$user->Save();
		$userNew->id = $user->id;
		$userNew->UpdatePassword();

		//Assert
		//Gets data from table
		$result = Administrator::GetUsersData($user->id);

		$this->assertNotEquals($user->SetPassword("Sg8Hs*alnYhha"), $result['password']);
	}
}