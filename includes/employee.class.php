<?php

	include "config.php";

	class Employee
	{
		private $conn;

		function __construct()
		{
			global $conn;
			$this->conn = $conn;
		}

		function Save()
		{
			
		}
	}

?>