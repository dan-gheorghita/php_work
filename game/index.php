<?php 
session_start(); 

require "db_game.php";

$sql = "SELECT * FROM words";
$result = mysqli_query($conn, $sql);
$result1;

if (mysqli_num_rows($result) > 0) {
    $result1 = mysqli_fetch_all($result, MYSQLI_ASSOC);
    //debug
    /* foreach($result1 as $result_one) {
        echo "id: " . $result_one["id"]. " - Word: " . $result_one["word"]. "<br>";
    } */
} else {
    echo "0 results";
}

if(!isset($_SESSION["guessed_id"]))
$_SESSION["guessed_id"] = array();

if(!isset($_SESSION["last_word_id"]))
$_SESSION["last_word_id"] = 0;

if(sizeof($_SESSION["guessed_id"]) == sizeof($result1))
header('Location: logout.php');
else 
while( in_array( ($random_w = rand(1,mysqli_num_rows($result))), $_SESSION["guessed_id"] ) && $random_w != $_SESSION["last_word_id"] /* && print_r(" $".$_SESSION["last_word_id"]." ") */);

if($_POST!=NULL){
    if(isset($_SESSION["submit"]))
    $_SESSION["submit"]++;
    else
    $_SESSION["submit"] = 1;

    if($_SESSION["word"]  == $_POST["guess"]){
        $_SESSION["count"]++;
        foreach($result1 as $result_one) {
            if($_SESSION["word"] == $result_one["word"]){
                $_SESSION["guessed_id"][] = $result_one["id"];
            }
        }
        header('Location: won.php');
    }
    
}

foreach($result1 as $result_one) {
    if($random_w == $result_one["id"]){
        $_SESSION["last_word_id"] = $result_one["id"];
    }
}
//while(($random_w = rand(1,mysqli_num_rows($result)))==$_SESSION["last_word_id"]);
//while( in_array( ($random_w = rand(1,mysqli_num_rows($result))), $_SESSION["guessed_id"] ) && $random_w != $_SESSION["last_word_id"]);
//print $random_w;
//debug
//echo $random_w;

if($result1!=NULL)
foreach($result1 as $result_one) {
    if($random_w == $result_one["id"]){
        $_SESSION["word"] = $result_one["word"];
    }
        
}
/* if(isset($_SESSION["guessed_id"]))
print_r($_SESSION["guessed_id"]); */

$shuffled = str_shuffle( $_SESSION["word"]);

mysqli_close($conn);

?>

<!DOCTYPE html>
<html>
<body>

<title>Game</title>

<h2>Number of games: <?php if(isset($_SESSION["submit"])) if($_SESSION["submit"]!=NULL) echo $_SESSION["submit"];?></h2>
<h2>Number of wins: <?php if(isset($_SESSION["count"])) if($_SESSION["count"]!=NULL) echo $_SESSION["count"];?></h2>

<p>Scrambled word: <?php echo $shuffled;?></p>

<form method="post">
  <label for="guess">Your guess:</label><br>
  <input type="text" id="guess" name="guess" value=""><br><br>

  <input type="submit" value="Submit">
</form>

<p>Note that the form itself is not visible.</p>

<p>Also note that the default width of text input fields is 20 characters.</p>


<a href="logout.php">Logout</a>

</body>
</html>
