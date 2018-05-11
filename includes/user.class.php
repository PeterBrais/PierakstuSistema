<?php

include "config.php";

class User
{
	private $conn;
	public $id;
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
		$sql = $this->conn->prepare("SELECT * FROM users WHERE username = ?");
		$sql->bind_param('s', $username); 	//Binds parameter, transforms to string
		$sql->execute();
		$result = $sql->get_result();		//Gets all results

		$resultCheck = mysqli_num_rows($result);	//Counts all rows

		if($resultCheck == 0)				//No such username found
		{
			$_SESSION['error'] = "Nepareizs lietotājvārds vai/un parole!";
			$_SESSION['username_login'] = $username;
			header("Location: /");
			exit();		//Does not continues to read following code
		}

		$row = mysqli_fetch_assoc($result);
		
		//Dehashing password
		$hashedPasswordCheck = password_verify($password, $row['password']);

		if($hashedPasswordCheck == false)
		{
			$_SESSION['error'] = "Nepareizs lietotājvārds vai/un parole!";
			$_SESSION['username_login'] = $username;
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

		$sql = $conn->prepare("SELECT username FROM users WHERE username = ?");
		$sql->bind_param('s', $username); //Binds parameter, transforms to string
		$sql->execute();
		$result = $sql->get_result();

		$resultCheck = mysqli_num_rows($result);

		return $resultCheck >= 1;
	}

	public static function CurrentUserUsernameExists($username, $id)	//Returns true if username from other users in DB already exists
	{
		global $conn;

		$sql = $conn->prepare("SELECT username FROM users WHERE username = ? AND id <> ?");
		$sql->bind_param('ss', $username, $id); //Binds parameter, transforms to string
		$sql->execute();
		$result = $sql->get_result();

		$resultCheck = mysqli_num_rows($result);

		return $resultCheck >= 1;
	}

	function SetPassword($password)		//Hashes passowrd
	{
		$this->password = password_hash($password, PASSWORD_DEFAULT);
	}

	function Save()		//Saves user data into database
	{
		try
		{
			$sql = $this->conn->prepare("INSERT INTO users VALUES (DEFAULT, ?, ?, ?, ?, ?)");
			$sql->bind_param('sssis', $this->username, $this->password, $this->role, $this->active, $this->created);
			$sql->execute();

			$this->id = $this->conn->insert_id;
			$sql->close();
		}
		catch(mysqli_sql_exception $e)
		{	
			$_SESSION['error'] = "Radās kļūda ierakstot datus!";
			header("Location: /");
			exit();
		}
	}

	function Update()	//Updates existing users data
	{
		try
		{
			$sql = $this->conn->prepare("UPDATE users SET username = ?, role = ? WHERE users.id = ?");
			$sql->bind_param('sss', $this->username, $this->role, $this->id);
			$sql->execute();
			$sql->close();
		}
		catch(mysqli_sql_exception $e)
		{	
			$_SESSION['error'] = "Radās kļūda ierakstot datus!";
			header("Location: /");
			exit();
		}
	}

	function Delete()	//Blocks / unblocks user
	{
		try
		{
			$sql = $this->conn->prepare("UPDATE users SET active = ? WHERE users.id = ?");
			$sql->bind_param('is', $this->active, $this->id);
			$sql->execute();
			$sql->close();
		}
		catch(mysqli_sql_exception $e)
		{	
			$_SESSION['error'] = "Radās kļūda ierakstot datus!";
			header("Location: /");
			exit();
		}
	}

	function UpdatePassword()	//Updates password
	{
		try
		{
			$sql = $this->conn->prepare("UPDATE users SET password = ? WHERE users.id = ?");
			$sql->bind_param('ss', $this->password, $this->id);
			$sql->execute();
			$sql->close();
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