<?php
session_start();
echo "You won!";
header( "refresh:3;url=index.php" );