<?php
//this is a simple php script to check if the email passed by get has already been taken


//get toy request from GET array
$email = $_GET['email'] ?? null;
//make sure it's  valid
if (!$email) {
    echo 'error';
    exit();
}

//sanitize user input
$email = filter_var($email, FILTER_SANITIZE_STRING);

require 'includes/library.php';
$pdo = connectDB();

//check if the email is already inputted into the DB
$statement = $pdo->prepare("SELECT email FROM `timeslot_users` WHERE email = ?");
$statement->execute([$email]);
$row = $statement->fetch();

//if the email doesnt already exist in the DB
if (!$row) {
    echo 'true'; 
} else {
    echo 'false'; 
}
exit();
?>
