<?php
// Connect to MySQL
require "db.php";

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
} 

$name = $password = '';
if(!empty($_POST)) {
	if(isset($_POST['name']) && $_POST['name']!='' && isset($_POST['password']) && $_POST['password']!='') {
		$name = $_POST['name'];
		$password = $_POST['password'];
		
		$sql = "SELECT * FROM `users` WHERE `user_name` LIKE '$name' AND `password` LIKE '$password'";
		$result = $conn->query($sql);

		if($result->num_rows == 1) {
			$_SESSION['logged'] = True;
			
			$conn->close();
			header('Location: buffer_frontend.php');
			exit();
		}
		else {
			$_SESSION['error'] = 'Username or password are wrong';
			
			header('Location: index.php');
			exit();
		}
	}
	else {
		$_SESSION['error'] = 'Username and password are required';
		
		header('Location: index.php');
		exit();
	}
}
else {
	header('Location: index.php');
	exit();
}