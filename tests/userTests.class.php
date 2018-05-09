<?php

require_once "C:\wamp64\www\pieraksts\includes\user.class.php";

use PHPUnit\Framework\TestCase;

		//Testam ir trīs daļas
		//1. Arrange
		//2. Act
		//3. Assert

class UserTests extends TestCase
{
	/**
	* @test
	*/
	public function When_Save_New_User_Then_It_Exists_In_Database()
	{
		//Arrange - sagatavo datus
		$user = new User();
		$user->username = "Vards";
		$user->role = "a";
		$user->SetPassword("Qwerty1@");

		//Act
		$user->Save();

		//Assert
		$this->assertTrue(User::Exists($user->username));
	}
}