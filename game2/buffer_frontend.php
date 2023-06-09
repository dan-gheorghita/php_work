<?php
require "buffer_game.php";
?>
<html>
<!-- Bootstrap -->
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
</head>
<!-- Display the game form -->
<body>
    <main class="text-center">
        <div class="container my-5">
            <div class="row p-4 pb-0 pe-lg-0 pt-lg-5 align-items-center rounded-3 border shadow-lg">
                <div class="col-lg-7 p-3 p-lg-5 pt-lg-3">
                    <h1>Guess the word!</h1>
                    <div <?php is_corect();?>>
                        <p>Shuffled word: <?php echo $shuffled_word;?></p>
                    </div>
                    <form method="post">
                        <div class="form-group">
                            <label for="guess">Enter your guess:</label>
                        </div><br>
                        <div class="form-group">
                            <input type="text" id="guess" name="guess" autofocus="">
                        </div><br>
                        <div class="form-group">
                            <button type="submit">Submit</button>
                        </div>
                    </form>
                    <!-- Display the game statistics -->
                    <h2>Statistics:</h2>
                    <p>Word attempt: <?php echo $_SESSION["word_attempts"];?></p>
                    <p>Wins: <?php echo $_SESSION["wins"];?></p>
                </div>
                <div class="col-lg-4 offset-lg-1 p-0 overflow-hidden shadow-lg">
                    <img class="rounded-lg-3" src="abstract-paper.jpg" alt="" width="720">
                </div>
            </div>
        </div>
        <a href="scoreboard_html.php">
                <button type="button" class="btn btn-primary btn-lg px-4 gap-3">Leaderboard</button>
            </a>
    </main>

</body>

</html>