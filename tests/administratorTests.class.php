<?php

require_once "C:\wamp64\www\pieraksts\includes\administrator.class.php";
require_once "C:\wamp64\www\pieraksts\includes\user.class.php";

use PHPUnit\Framework\TestCase;

//Test is divided in 3 parts:
//1. Arrange - prepares data
//2. Act - sets data
//3. Assert - tests data

class AdministratorTests extends TestCase
{
	/**
	* @test
	*/
	public function When_AllUsers_Then_Result_Is_List_Of_All_Users_From_Database()	//Test AllUsers function
	{
		global $conn;
		$conn->query("DELETE FROM users");	//Clears DB
		//Arrange
		$userOne = new User();
		$userOne->username = "rigel";
		$userOne->role = "a";
		$userOne->SetPassword("tender");

		$userTwo = new User();
		$userTwo->username = "pager";
		$userTwo->role = "p";
		$userTwo->SetPassword("juicy");

		$userThree = new User();
		$userThree->username = "notated";
		$userThree->role = "l";
		$userThree->SetPassword("cooks");

		//Act
		$userOne->Save();
		$userTwo->Save();
		$userThree->Save();

		//Assert
		$allUsersFromDb = Administrator::AllUsers();
		$this->assertEquals(3, count($allUsersFromDb));

		$usernames = array($userOne->username, $userTwo->username, $userThree->username);
		$i = 0;
		foreach($allUsersFromDb as $user)
		{
			$this->assertEquals($usernames[$i], $user['username']);
			$i++;
		}
	}

	/**
	* @test
	*/
	public function When_Save_New_User_Then_Its_Data_With_ID_Exists_In_Database()	//Tests ExistsUserWithID function
	{
		//Arrange
		$user = new User();
		$user->username = "WorsT";
		$user->role = "p";
		$user->SetPassword("weary)");

		//Act
		$user->Save();

		//Assert
		$this->assertTrue(Administrator::ExistsUserWithID($user->id));
	}

	/**
	* @test
	*/
	public function When_Save_New_User_Then_Its_Data_Can_Be_Loaded_From_Database()	//Tests GetUsersData function
	{
		//Arrange
		$user = new User();
		$user->username = "dnaish";
		$user->role = "l";
		$user->SetPassword("noteworthy");

		//Act
		$user->Save();

		//Assert
		$userData = Administrator::GetUsersData($user->id);
		$this->assertEquals($user->username, $userData['username']);
		$this->assertEquals(1, $userData['active']);
	}

	/**
	* @test
	*/
	public function When_Save_New_User_Then_Its_Password_Can_Be_Compared()	//Tests Confirm function
	{
		//Arrange
		$user = new User();
		$user->username = "chomping";
		$user->role = "a";
		$user->SetPassword("porsche");
		$pwdPlain = "porsche";

		//Act
		$user->Save();

		//Assert
		$this->assertTrue(Administrator::Confirm($pwdPlain, $user->id));
		$this->assertFalse(Administrator::Confirm("ferrari", $user->id));
	}
}