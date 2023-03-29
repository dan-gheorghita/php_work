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
    $_SESSION["real_word"] = $row["word"];
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

// Display the game form
echo "<h1>Guess the word!</h1>";
echo "<p>Shuffled word: " . $shuffled_word . "</p>";
echo "<form method='post'>";
echo "<label for='guess'>Enter your guess:</label>";
echo "<input type='text' id='guess' name='guess' autofocus>";
echo "<button type='submit'>Submit</button>";
echo "</form>";

// Display the game statistics
echo "<h2>Statistics:</h2>";
echo "<p>Attempts: " . $_SESSION["attempts"] . "</p>";
echo "<p>Wins: " . $_SESSION["wins"] . "</p>";

// Close the MySQL connection
$conn->close();

?>
