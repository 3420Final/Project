<?php
if (isset($_POST['resetpassword'])) {

    $email = filter_var($_POST['email'], FILTER_SANITIZE_STRING);

    include 'includes/library.php';
    $pdo = connectDB();
    
    //prep and execute query to see if user exists
    $query = "SELECT email FROM `timeslot_users` WHERE email=?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$email]);
    $results = $stmt->fetch();

    if($results != false){  //the email exists in our DB
        include('includes/resetPasswordEmail.php');
        $email = $results['email'];
        resetPassword($email);
    }

    
    header("Location:index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>
    <link rel ="stylesheet" href = "styles/master.css"/>
    <script src="https://kit.fontawesome.com/accfddd944.js" crossorigin="anonymous"></script>
</head>
<body>
    <header>
        <h1><i class="fas fa-sign-in-alt"></i> Change Password</h1>
    </header>
    <main>
        <section id='emailbox'>
            <div>
                <div>
                    <i class="fas fa-user"></i>
                </div>
                <div>
                    <h3>Enter your email below, and a reset link valid for one day will be sent to you!</h3>
                </div>
                <div>
                    <form name="email"  method="post">
                        <div id='emailspot'>
                            <label for="email" class='emailaddress'>Enter your email address:</label>
                            <input id="email" name="email" type="text" placeholder="" required/>
                        </div>
                        <div>
                            <button type="submit" name="resetpassword">Reset Password</button>
                        </div>
                    </form>
                    <a href='login.php'>Back</a>
                </div>
            </div>
        </section>
    </main>
</body>
</html>