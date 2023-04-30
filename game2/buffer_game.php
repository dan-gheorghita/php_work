<?php
// Connect to MySQL
require "db.php";

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the user is logged in
if($_SESSION['logged'] != True){
    header( 'HTTP/1.0 403 Forbidden', TRUE, 403 );

    /* choose the appropriate page to redirect users */
    die( header( 'location: /error.php' ) );
}
$user_id = $_SESSION["user_id"];
$sql = "SELECT * FROM user_games ORDER BY id DESC LIMIT 1 WHERE 'user_id' LIKE '$user_id'";
$result = $conn->query($sql);

if ($result->num_rows == 1) {
    $row = $result->fetch_assoc();
    $_SESSION["attempt_ids"] = $row["attempt_ids"];
    $_SESSION["real_word"] = $row["current_word"];
    $_SESSION["word_attempts"] = $row["current_attempt"];
    $_SESSION["total_attempts"] = $row["total_attempts"];
    $_SESSION["wins"] = $row["guessed_cnt"];

    $attempts_id = explode("','",$row["attempt_ids"]);
    $attempt_ids =$row["attempt_ids"];
    $sql = "SELECT id,word FROM words ORDER BY RAND() LIMIT 1 WHERE id NOT IN ('$attempt_ids')";
    $result = $conn->query($sql);

} else {
    $sql = "SELECT id,word FROM words ORDER BY RAND() LIMIT 1";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $_SESSION["real_word"] = $row["word"];
    $_SESSION["word_id"] = $row["id"];
    $word = $row["word"];

    $sql = "INSERT INTO `user_games` (`id`, `user_id`, `attempt_ids`, `current_word`, `current_attempt`, `guessed_cnt`) VALUES (NULL, '$user_id', '', '$word', '0', '0')";
    if ($conn->query($sql) === TRUE) {
        echo "New game created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
    $_SESSION["attempt_ids"] = '';
    $_SESSION["word_attempts"] = 0;
    $_SESSION["guessed_cnt"] = 0;
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

function new_word($conn){
    //When the word is guessed get the id of it then put it in the string
    $real_word = $_SESSION["real_word"];
    $sql = "SELECT id FROM words WHERE 'words' LIKE '$real_word'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $_SESSION["word_id"] = $row["id"];

    $attempt_ids = $_SESSION["attempt_ids"]."','".$_SESSION["word_id"];
    $sql = "SELECT id,word FROM words ORDER BY RAND() LIMIT 1 WHERE id NOT IN ('$attempt_ids')";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $_SESSION["real_word"] = $row["word"];
    $_SESSION["word_id"] = $row["id"];
    $word = $row["word"];

    $user_id = $_SESSION["user_id"];
    $guessed_cnt = $_SESSION["wins"];
    $sql = "UPDATE `user_games` SET  attempt_ids = '$attempt_ids', current_word = '$word' , current_attempt = 0, guessed_cnt = '$guessed_cnt' WHERE user_id = '$user_id'";
    if ($conn->query($sql) === TRUE) {
        echo "Game changed successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
    $_SESSION["word_attempts"] = 0;

}

// Check if the user has submitted a guess
if (isset($_POST["guess"])) {
    $guess = $_POST["guess"];
    $real_word = $_SESSION["real_word"];

    // Check if the guess is correct
    if ($guess == $real_word) {
        $_SESSION["wins"]++;
        $_SESSION["is_corect_var"] = 1;
        new_word($conn);
        echo "Congratulations! You guessed the word!";
    } else {
        if($_SESSION["word_attempts"] == 3)
            new_word($conn);
            else{
                $_SESSION["is_corect_var"] = 0;
                echo "Try again!";
            }
    }

    // Increment the number of attempts
    //$_SESSION["total_attempts"]++;
}

// Get the shuffled word
$shuffled_word = str_shuffle($_SESSION["real_word"]);

// Close the MySQL connection
$conn->close();
