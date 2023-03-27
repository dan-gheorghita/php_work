<?php session_start(); ?>

<!DOCTYPE html>
<html>
<head>
	<title>My PHP</title>
</head>
<body>

<h2>HTML Forms</h2>

<?php
	if(!empty($_SESSION['error'])) {
		echo "<div style='padding-bottom:15px;color:red;'>".$_SESSION['error']."</div>";
	}
	unset($_SESSION['error']);
?>

<form method="post" action="login.php">
  <label for="name">Login name:</label><br>
  <input type="text" id="name" name="name"><br>
  
  <label for="password">Password:</label><br>
  <input type="password" id="password" name="password" value=""><br><br>
  
  <input type="submit" value="Submit">
</form> 

</body>
</html>