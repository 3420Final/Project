<?php

//this code references the email example given on Blackboard by Jamie Mitchell, as well as
//https://www.allphptricks.com/forgot-password-recovery-reset-using-php-and-mysql/ written by Javed Ur Rehman on August 13 2018. 


//Bill Van Leeuwen, July 3rd, 2021
//this function will create an entry into a password reset table and send an email to the user to reset their password.
//pass this function a string containing the users email
function resetPassword($email)
{

    // Load configuration as an array. FROM LIBRARY.PHP
    $config = parse_ini_file(DOCROOT . "pwd/config.ini");
    

    //create key
    //generates a 30 length cryptographically secure string of random bytes, and then converts to hexidecimal
    //https://stackoverflow.com/questions/18910814/best-practice-to-generate-random-token-for-forgot-password
    //https://www.php.net/manual/en/function.bin2hex.php
    //https://www.php.net/manual/en/function.random-bytes
    $key = bin2hex(random_bytes(30));

    
    //create an expiry of type integer, one day out
    $expiry = time()+60*60*24;

    //insert the temporary key into the DB
    $pdo = connectDB();
    $query = "INSERT INTO `timeslot_resetpassword` (email, expiry,`key`) VALUES (?,?,?)";  //email:string, epiry:int, key:string
    $stmt = $pdo->prepare($query);
    $stmt->execute([$email,$expiry,$key]);

    $query = "select username from timeslot_users WHERE email = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$email]);
    $username = $stmt->fetch();
    $username = $username['username'];

    //send the email containing the link
    require_once "Mail.php";  //this includes the pear SMTP mail library
    $from = "Password System Reset <noreply@loki.trentu.ca>";
    $to = "BillAndJamiesTimeSheetsUser <" . $email . ">";  //put user's email here
    $subject = "Password Reset";
    $body = "Hello, " . $username . ". follow this link to reset your password. https://loki.trentu.ca/~". 
    $config['username'] ."/Project/resetPassword.php?key=$key&email=$email&action=reset
    \nIf you recieved this message by mistake, you can safely ignore this email.";   //uses the config file from the server to get the correct user, jamie or william
    $host = "smtp.trentu.ca";
    $headers = array ('From' => $from,
      'To' => $to,
      'Subject' => $subject);
    $smtp = Mail::factory('smtp',
      array ('host' => $host));
    
    $mail = $smtp->send($to, $headers, $body);
    if (PEAR::isError($mail)) {
      echo("<p>" . $mail->getMessage() . "</p>");
     } else {
      echo("<p>Message successfully sent!</p>");
     }
}
?>
