<?php

include "config.php";

class User
{
	private $conn;
	public $username;
	private $password;
	public $role;
	public $active;
	public $created;

	function __construct()
	{
		global $conn;
		$this->conn = $conn;
		$this->active = 1;
		$this->created = date("Y-m-d");
	}

	function Login($username, $password)
	{
		$sql = $this->conn->prepare("SELECT * FROM users WHERE username=?");
		$sql->bind_param('s', $username); 	//Binds parameter, transforms to string
		$sql->execute();
		$result = $sql->get_result();		//Gets all reasults

		$resultCheck = mysqli_num_rows($result);	//Counts all rows

		if($resultCheck == 0)				//No such username found
		{
			$_SESSION['error'] = "Nepareizs lietotājvārds vai/un parole!";
			header("Location: /");
			exit();		//Does not continues to read following code
		}

		$row = mysqli_fetch_assoc($result);
		
		//Dehashing password
		$hashedPasswordCheck = password_verify($password, $row['password']);

		if($hashedPasswordCheck == false)
		{
			$_SESSION['error'] = "Nepareizs lietotājvārds vai/un parole!";
			header("Location: /");
			exit();
		}

		//Log in the user
		$_SESSION['id'] = $row['id'];
		$_SESSION['username'] = $row['username'];
		$_SESSION['role'] = $row['role'];
		$_SESSION['active'] = $row['active'];

		header("Location: /");
		exit();
	}

	public static function Logout()
	{
		session_unset();	//Unseting all SESSION variables
		session_destroy();	//Destroys session
		header("Location: /");
		exit();
	}

	public static function Exists($username)	//Returns true if username in DB already exists
	{
		global $conn;

		$sql = $conn->prepare("SELECT username FROM users WHERE username=?");
		$sql->bind_param('s', $username); //Binds parameter, transforms to string
		$sql->execute();
		$result = $sql->get_result();

		$resultCheck = mysqli_num_rows($result);

		return $resultCheck == 1;
	}

	function SetPassword($password)
	{
		$this->password = password_hash($password, PASSWORD_DEFAULT);
	}

	function Save()
	{
		try
		{
			$sql = $this->conn->prepare("INSERT INTO users VALUES (DEFAULT, ?, ?, ?, ?, ?)");
			$sql->bind_param('sssis', $this->username, $this->password, $this->role, $this->active, $this->created);
			$sql->execute();
		}
		catch(mysqli_sql_exception $e)
		{	
			$_SESSION['error'] = "Radās kļūda ierakstot datus!";
			header("Location: /");
			exit();
		}
	}
}

?>