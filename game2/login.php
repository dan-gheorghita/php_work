<?php
/* at the top of 'check.php' */
if ($_SERVER['REQUEST_METHOD'] == 'GET' && realpath(__FILE__) == realpath($_SERVER['SCRIPT_FILENAME'])) {
	/* 
       Up to you which header to send, some prefer 404 even if 
       the files does exist for security
    */
	header('HTTP/1.0 403 Forbidden', TRUE, 403);

	/* choose the appropriate page to redirect users */
	die(header('location: /error.php'));
}

// Connect to MySQL
require "db.php";

// Check connection
if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
}

$name = $password = '';
if (!empty($_POST)) {
	if (isset($_POST['name']) && $_POST['name'] != '' && isset($_POST['password']) && $_POST['password'] != '') {
		$name = $_POST['name'];
		$password = $_POST['password'];

		$sql = "SELECT id,user_name,password FROM `users` WHERE `user_name` LIKE '$name'";
		$result = $conn->query($sql);

		if ($result->num_rows == 1) {
			$row = $result->fetch_assoc();
			if(password_verify($password,$row["password"])){
				$_SESSION['logged'] = True;
				//Remember user
				$_SESSION["user_id"] = $row["id"];

				$conn->close();
				header('Location: buffer_frontend.php');
				exit();
			} else {
				$_SESSION['error'] = 'Password inncorrect';

			header('Location: index.php');
			exit();
			}
			
		} else {
			$_SESSION['error'] = 'Username or password are wrong';

			header('Location: index.php');
			exit();
		}
	} else {
		$_SESSION['error'] = 'Username and password are required';

		header('Location: index.php');
		exit();
	}
} else {
	header('Location: index.php');
	exit();
}
