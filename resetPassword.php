<?php

$errors = array();

//referenced from https://www.allphptricks.com/forgot-password-recovery-reset-using-php-and-mysql/
if (isset($_GET["key"]) && isset($_GET["email"]) && isset($_GET["action"]) && ($_GET["action"]=="reset")){

    $key = filter_var($_GET["key"], FILTER_SANITIZE_STRING);
    $email = filter_var( $_GET["email"], FILTER_SANITIZE_EMAIL);

    
    include 'includes/library.php';
    $pdo = connectDB();
    $query = "SELECT * FROM `timeslot_resetpassword` WHERE `key` =? and email =?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$key,$email]);
    $results = $stmt->fetch();

    if($results != false){  //the reset data exists in the table
        if(intval($results['expiry']) > time()){    //is the expiry greater than the current time

            //if we reach this point, the password reset is valid and will be allowed.
            
            //since the create account forces unique emails, there will only be one result from this.
            $query = "SELECT * FROM `timeslot_users` WHERE email =?";
            $stmt = $pdo->prepare($query);
            $stmt->execute([$email]);
            $results = $stmt->fetch(); 

            $username = $results['username'];
            $text = 'Welcome back ' . $username . '. Please enter your new password below.';
        }
        else{
            $errors['resetnotexist'] = true;
            $text = 'This password reset is not valid';
        }
    }
    else{
        $errors['resetnotexist'] =true;
        $text = 'This password reset is not valid';
    }

    if (isset($_POST['resetpassword'])) {

        $password1 = $_POST['password1'] ?? "";
        $password2 = $_POST['password2'] ?? "";

        $password1 = filter_var($password1, FILTER_SANITIZE_STRING);
        $password2 = filter_var($password2, FILTER_SANITIZE_STRING);

        if ($password1 !== $password2){  //make sure that the both the password fields are the same
            $errors['passwordsmatch'] = true;
        }
        if(count($errors) == 0){

            $hashpass = password_hash($password1, PASSWORD_DEFAULT);

            //update the users password
            $query = "UPDATE `timeslot_users` SET `password`=? WHERE username =?";
            $stmt = $pdo->prepare($query);
            $stmt->execute([$hashpass,$username]);

            //delete any reset keys associated with the user
            $query = "DELETE FROM `timeslot_resetpassword`  WHERE email =?";
            $stmt = $pdo->prepare($query);
            $stmt->execute([$email]); 
            
            header("Location:login.php");
            exit();
        }
        
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel ="stylesheet" href = "styles/master.css"/>
    <script src="https://kit.fontawesome.com/accfddd944.js" crossorigin="anonymous"></script>
</head>
<body>
    <header>
        <h1><i class="fas fa-sign-in-alt"></i> Reset Password</h1>
    </header>
    <main>
        <section id='emailbox'>
            <div>
                <div>
                    <i class="fas fa-user"></i>
                </div>
                <div>
                    <h3><?=$text?></h3>
                </div>
                <div>
                    <form name="email"  method="post">
                    <div>
                        <label for="passwd">Password </label>
                        <input type="password" name="password1" id="passwd1" <?=isset($errors['resetnotexist']) ? 'readonly' : "";?> required/>
                    </div>
                    <div>
                        <label for="passwd">Re-enter Password </label>
                        <input type="password" name="password2" id="passwd2" <?=isset($errors['resetnotexist']) ? 'readonly' : "";?> required/>
                    </div>
                    <div>
                        <button type="submit" name="resetpassword" <?=isset($errors['resetnotexist']) ? 'disabled' : "";?>>Reset Password</button>
                    </div>
                    </form>
                    <span class="<?=!isset($errors['passwordsmatch']) ? 'hidden' : "error";?>">Password Fields Dont Match</span>
                    <a href='index.php'>Home Page</a>
                </div>
            </div>
        </section>
    </main>
</body>
</html>