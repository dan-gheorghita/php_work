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

//Creates the new game parameters through a function
function new_game($conn){
    $user_id = $_SESSION["user_id"];
    //Select random word from database
    $sql = "SELECT id,word FROM words ORDER BY RAND() LIMIT 1";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $_SESSION["real_word"] = $row["word"];
    $_SESSION["word_id"] = $row["id"];
    $word = $row["word"];

    //Create new entry in user_games database
    $sql = "INSERT INTO `user_games` (`id`, `user_id`, `attempt_ids`, `current_word`, `current_attempt`, `guessed_cnt`) VALUES (NULL, '$user_id', '', '$word', '1', '0')";
    if ($conn->query($sql) === TRUE) {
        echo "New game created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    //Set the sessions parameters according to the db values
    $_SESSION["attempt_ids"] = '';
    $_SESSION["word_attempts"] = 1;
    $_SESSION["guessed_cnt"] = 0;
    $_SESSION["wins"] = 0;
}

//Fetch every time the last game of the user
$user_id = $_SESSION["user_id"];
$sql = "SELECT * FROM user_games WHERE user_id = '$user_id' ORDER BY id DESC LIMIT 1";
$result = $conn->query($sql);

//Store every db value in a session variable
if ($result->num_rows == 1) {
    $row = $result->fetch_assoc();
    $_SESSION["attempt_ids"] = $row["attempt_ids"];
    $_SESSION["real_word"] = $row["current_word"];
    $_SESSION["word_attempts"] =  $row["current_attempt"];
    $_SESSION["wins"] = $row["guessed_cnt"];
    $_SESSION["game_id"] = $row["id"];


} else {
    //or if the game does not exist create new game
   new_game($conn);
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
    //Check if is the firt word in the list and adds the word to the list
    if($_SESSION["attempt_ids"] != NULL)
    $attempt_ids = $_SESSION["attempt_ids"].",".$_SESSION["word_id"];
    else
    $attempt_ids = $_SESSION["word_id"];

    $attempts_id = explode(",",$attempt_ids);
    $attempt_id = implode("','",$attempts_id);

    //Select a word that hasn't been guesed
    $sql = "SELECT id,word FROM words WHERE id NOT IN ('$attempt_id') ORDER BY RAND() LIMIT 1 ";
    $result = $conn->query($sql);
     $row = $result->fetch_assoc();
     //See if the game has more words
    if(isset($row)){
       
        $_SESSION["real_word"] = $row["word"];
        $_SESSION["word_id"] = $row["id"];
        $word = $row["word"];
        print_r($row);
    }
    
    //Update the status of the game
    $game_id = $_SESSION["game_id"];
    $guessed_cnt = $_SESSION["wins"];
    $sql = "UPDATE `user_games` SET  attempt_ids = '$attempt_ids', current_word = '$word' , current_attempt = '1', guessed_cnt = '$guessed_cnt' WHERE id = '$game_id'";
    if ($conn->query($sql) === TRUE) {
        echo "Word changed successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
    $_SESSION["word_attempts"] = 1;

    //Sets 0 if no more words are in the game
    if(isset($row))
    return 1;
    else return 0;
}

// Check if the user has submitted a guess
if (isset($_POST["guess"])) {
    $guess = $_POST["guess"];
    $real_word = $_SESSION["real_word"];

    // Check if the guess is correct
    if ($guess == $real_word) {
        $_SESSION["wins"]++;
        $_SESSION["is_corect_var"] = 1;
        //Creates new game if there are not any more words
        $next_game = new_word($conn);
        if($next_game == 0){
            new_game($conn);

        }
        
        echo "Congratulations! You guessed the word!";
    } else {
        if ($_SESSION["word_attempts"] == 3){
            //Creates new game if there are not any more words
            if(new_word($conn) == 0)
                new_game($conn);
        }
        else {
            //Increase attempt count for the word
            $_SESSION["is_corect_var"] = 0;
            $_SESSION["word_attempts"]++;
            $curent_attempt = $_SESSION["word_attempts"];
            $game_id = $_SESSION["game_id"];
            $sql = "UPDATE `user_games` SET current_attempt = '$curent_attempt' WHERE id = '$game_id'";
            if ($conn->query($sql) === TRUE) {
                echo "Attempt increase successfully <br>";
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
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
