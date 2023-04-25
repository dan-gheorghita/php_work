<?php
// Connect to MySQL
require "db.php";

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

//Sets the css text color according to the guess result
if(!isset($_SESSION["is_corect_var"]))
$_SESSION["is_corect_var"] = 1;

function is_corect(){
    if(isset($_SESSION["is_corect_var"]))
    if($_SESSION["is_corect_var"])
        echo 'class="p-3 text-primary-emphasis bg-primary-subtle border border-primary-subtle rounded-3"';
        else 
        echo 'class="p-3 text-danger-emphasis bg-danger-subtle border border-danger-subtle rounded-3"';
}

// Check if the user has submitted a guess
if (isset($_POST["guess"])) {
    $guess = $_POST["guess"];
    $real_word = $_SESSION["real_word"];

    // Check if the guess is correct
    if ($guess == $real_word) {
        echo "Congratulations! You guessed the word!";
        $_SESSION["wins"]++;
        $_SESSION["is_corect_var"] = 1;
    } else {
        echo "Try again!";
        $_SESSION["is_corect_var"] = 0;
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