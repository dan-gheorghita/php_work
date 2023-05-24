<?php require "db.php"; ?>
<html data-bs-theme="light" lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">

    <!-- Custom styles for this template -->
    <link href="sign-in.css" rel="stylesheet">
</head>

<body>
    <table class="table table-striped table-sm">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Name</th>
                <th scope="col">Highscore</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sql = "SELECT id,user_id, MAX(attempt_ids),current_word,current_attempt,guessed_cnt FROM user_games GROUP BY user_id;";
            $result = $conn->query($sql);
            $placement = 1;
            while ($row = $result->fetch_assoc()) {
                $userid = $row['user_id'];
                $sql = "SELECT id,user_name FROM `users` WHERE `id` LIKE '$userid'";
                $resultat = $conn->query($sql);
                $user = $resultat->fetch_assoc();
                echo "<tr>
                                <td>" . $placement . "</td>
                                <td>" . $user["user_name"] . "</td>
                                <td>" . $row["guessed_cnt"] . "</td>
                                </tr>";
                $placement++;
            }
            ?>
        </tbody>
    </table>
</body>

</html>