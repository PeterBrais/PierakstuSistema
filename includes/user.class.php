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
		$resault = $this->conn->query($sql);
		return $resault;
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