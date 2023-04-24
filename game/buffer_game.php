<?php
// Start a session to keep track of game statistics
session_start();

// Connect to MySQL
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "game";
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the real word from the session or generate a new one
if (!isset($_SESSION["real_word"])) {
    $sql = "SELECT word FROM words ORDER BY RAND() LIMIT 1";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    print_r($row);
    $_SESSION["real_word"] = $row["word"];
}

function check_var_for_display($name)
{
    if (isset($_SESSION[$name]))
    echo $_SESSION[$name];
    else {
        $_SESSION[$name] = 0;
        echo $_SESSION[$name];
    }
}

// Check if the user has submitted a guess
if (isset($_POST["guess"])) {
    $guess = $_POST["guess"];
    $real_word = $_SESSION["real_word"];

    // Check if the guess is correct
    if ($guess == $real_word) {
        echo "Congratulations! You guessed the word!";
        $_SESSION["wins"]++;
    } else {
        echo "Try again!";
    }

    // Generate a new word and store it in the session
    $sql = "SELECT word FROM words ORDER BY RAND() LIMIT 1";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $_SESSION["real_word"] = $row["word"];

    // Increment the number of attempts
    $_SESSION["attempts"]++;
}

// Get the shuffled word
$shuffled_word = str_shuffle($_SESSION["real_word"]);

// Close the MySQL connection
$conn->close();

?>