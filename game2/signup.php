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

//Connect to database
require "db.php";

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$name = $password = '';
if (!empty($_POST)) {
    if (isset($_POST['name']) && $_POST['name'] != '' && isset($_POST['password']) && $_POST['password'] != '') {

        //Save the form information in variables
        $name = $_POST["name"];
        $password = $_POST["password"];

        $sql = "SELECT * FROM `users` WHERE `user_name` LIKE '$name'";
        $result = $conn->query($sql);

        if ($result->num_rows == 1) {
            $_SESSION['error'] = 'Username already exist';

            header('Location: signup_html.php');
            exit();
        } else {
            // Hash the password with bcrypt
		    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

            //Insert new account in table
            $sql = "INSERT INTO `users` (`id`, `user_name`, `password`, `is_admin`) VALUES (NULL, '$name', '$hashed_password', '0')";
            if ($conn->query($sql) === TRUE) {
                echo "New account created successfully";
                $sql = "SELECT id,user_name FROM `users` WHERE `user_name` LIKE '$name'";
		        $result = $conn->query($sql);
                $row = $result->fetch_assoc();
                $_SESSION["user_id"] = $row["id"];
                $_SESSION['logged'] = True;
              } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
              }
            $conn->close();

            header('Location: buffer_frontend.php');
            exit();
        }
    } else {
        $_SESSION['error'] = 'Username and password are required';

        header('Location: signup_html.php');
        exit();
    }
} else {
    header('Location: signup_html.php');
    exit();
}
