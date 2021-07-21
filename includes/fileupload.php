<?php

if(is_uploaded_file($_FILES['imgupload']['tmp_name'])){
    //take the users original filename
    $filename = $_FILES['imgupload']['name'];

    //include library for DOCROOT and pdo
    include "library.php";

    //path to the images folder
    $path = "../www_data/";

    //split string on period
    $extension = explode('.', $filename);
    //just take the extension
    $extension = $extension[1];
    
    //get the username of the students server in order to use the right mySQL DB name
    $config = parse_ini_file(DOCROOT . "pwd/config.ini");
    $DBname = $config['username'];
    
    //get the next auto increment value
    $pdo = connectDB();
    $stmt = $pdo->query('SELECT AUTO_INCREMENT FROM information_schema.TABLES WHERE TABLE_SCHEMA = "' . $DBname . '" AND TABLE_NAME = "timeslot_images"');
    $nextincrement = $stmt->fetch();
    $nextincrement = $nextincrement['AUTO_INCREMENT'];

    //TODO create filename
    //put file into www_data\

    
}
?>

