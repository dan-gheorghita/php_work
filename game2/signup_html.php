<?php session_start(); ?>
<html data-bs-theme="light" lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">

    <!-- Custom styles for this template -->
    <link href="sign-in.css" rel="stylesheet">
</head>

<div class="modal modal-sheet position-static d-block bg-body-secondary p-4 py-md-5" tabindex="-1" role="dialog" id="modalSignin">
  <div class="modal-dialog" role="document">
    <div class="modal-content rounded-4 shadow">
      <div class="modal-header p-5 pb-4 border-bottom-0">
        <h1 class="fw-bold mb-0 fs-2">Sign up for free</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body p-5 pt-0">
        <form method="post" action="signup.php" class="">
          <div class="form-floating mb-3">
            <input type="text" class="form-control rounded-3" id="name" name="name" placeholder="name@example.com">
            <label for="name">Username</label>
          </div>
          <div class="form-floating mb-3">
            <input type="password" class="form-control rounded-3" id="password" name="password" placeholder="Password">
            <label for="password">Password</label>
          </div>
          <?php
            if (!empty($_SESSION['error'])) {
                echo "<div style='padding-bottom:15px;color:red;'>" . $_SESSION['error'] . "</div>";
            }
            unset($_SESSION['error']);
            ?>
          <button class="w-100 mb-2 btn btn-lg rounded-3 btn-primary" type="submit">Sign up</button>
          <small class="text-body-secondary">By clicking Sign up, you agree to the terms of use.</small>
          
        </form>
      </div>
    </div>
  </div>
</div>

</html>
