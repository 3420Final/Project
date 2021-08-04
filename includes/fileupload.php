<?php
function fileUpload(int $userID){
    try{
        if(is_uploaded_file($_FILES['imgupload']['tmp_name'])){

            //take the users original filename
            $filename = $_FILES['imgupload']['tmp_name'];


            //code copied from PHP web techniques on https://jamiemitchell.dev/3420/summer2021/notes/php_webtechniques.html
            if (exif_imagetype( $_FILES['imgupload']['tmp_name']) != IMAGETYPE_GIF
            and exif_imagetype( $_FILES['imgupload']['tmp_name']) != IMAGETYPE_JPEG
            and exif_imagetype( $_FILES['imgupload']['tmp_name']) != IMAGETYPE_PNG){

                throw new RuntimeException('Invalid file format.');
            }


            //path to the images folder
            //$path = WEBROOT . "/www_data/";
            $path = "../www_data/";

            //split string on period
            $extension = explode('.', $_FILES['imgupload']['name']);
            //just take the extension
            $extension = $extension[1];
            
            //get the username of the students server in order to use the right mySQL DB name
            $config = parse_ini_file(DOCROOT . "pwd/config.ini");
            $DBname = $config['username'];
            
            //get the next auto increment value
            if(!isset($pdo)){
                $pdo = connectDB();
            }
            $stmt = $pdo->query('SELECT AUTO_INCREMENT FROM information_schema.TABLES WHERE TABLE_SCHEMA = "' . $DBname . '" AND TABLE_NAME = "timeslot_images"');
            $nextincrement = $stmt->fetch();
            $nextincrement = $nextincrement['AUTO_INCREMENT'];

            //TODO create filename
            //name the file whichever increment it is + extension
            $fileDest = ($path . $nextincrement . '.' . $extension);

            if(!move_uploaded_file($_FILES['imgupload']['tmp_name'], $fileDest)){
                throw new RuntimeException('File failed to upload');
            }

            $query = "INSERT INTO `timeslot_images` (filepath,userID) VALUES (?,?)";
            $stmt = $pdo->prepare($query);
            $stmt->execute([$fileDest,$userID]);

        }
    }
    catch (RuntimeException $e) {
        //echo $e->getMessage();
        throw $e;
    }
}

?>

