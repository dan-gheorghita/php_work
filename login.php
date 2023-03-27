<?php
session_start();
require 'db_login.php';

$name = $password = '';
if(!empty($_POST)) {
	if(isset($_POST['name']) && $_POST['name']!='' && isset($_POST['password']) && $_POST['password']!='') {
		$name = $_POST['name'];
		$password = $_POST['password'];
		
		$sql = "SELECT * FROM `accounts` WHERE `username` LIKE '$name' AND `password` LIKE '$password'";
		$result = mysqli_query($conn, $sql);

		if(mysqli_num_rows($result) == 1) {
			$_SESSION['logged'] = True;
			
			mysqli_close($conn);
			header('Location: forms.php');
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