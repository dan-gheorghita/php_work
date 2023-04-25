<?php
// Start a session to keep track of game statistics
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "game";

$conn = new mysqli($servername, $username, $password, $dbname);