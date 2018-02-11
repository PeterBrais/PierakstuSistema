<?php

include "config.php";

class User
{
	private $conn;

	function __construct()
	{
		global $conn;
		$this->conn = $conn;
	}

	function GetAllUsers()
	{
		$sql = "SELECT * FROM users";
		$result = $this->conn->query($sql);
		return $result;
	}

	function Delete()
	{

	}

	function Save()
	{

	}

	function Update()
	{
		
	}

	//autentifikacija

	

}

?>