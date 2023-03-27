<?php
session_start();
echo "You won!";
header( "refresh:5;url=index.php" );