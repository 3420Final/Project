<?php
//this is a simple php script to check if the username passed by get has already been taken


//get toy request from GET array
$username = $_GET['username'] ?? null;
//make sure it's  valid
if (!$username) {
    echo 'error';
    exit();
}

//sanitize user input
$username = filter_var($username, FILTER_SANITIZE_STRING);

require 'includes/library.php';
$pdo = connectDB();

//check if the username is already inputted into the DB
$statement = $pdo->prepare("SELECT username FROM `timeslot_users` WHERE username = ?");
$statement->execute([$username]);
$row = $statement->fetch();

//if the username doesnt already exist in the DB
if (!$row) {
    echo 'true'; 
} else {
    echo 'false'; 
}
exit();
?>
