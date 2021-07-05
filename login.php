<?php
    $username = $_POST['username'] ?? "";
    $password = $_POST['password'] ?? "";
    $errors = array();

    if (isset($_POST['login'])) {

        $username = filter_var($username, FILTER_SANITIZE_STRING);
        $password = filter_var($password, FILTER_SANITIZE_STRING);

        include 'includes/library.php';
        $pdo = connectDB();
        
        //prep and execute query to find username/password
        $query = "SELECT * FROM `timeslot_users` WHERE username=?";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$username]);
        $results = $stmt->fetch();

        if($results == false){  //does the user exist?
            $errors['loginfail'] = true;
        }
        else{
            if (password_verify($password, $results['password'])){  //if their password is valid

                //put the user into the session
                session_start();
                $_SESSION['username'] = $username;
                //removed the storing of ID here for simplicity. Bill (July 3rd 2021).
                
                if($_POST['remember']){
                    setcookie("logincookie",$username,time()+60*60*24); //expires after one day
                }


                header("Location:mySignups.php");
                exit();
            }
    
            else{
                $errors['loginfail'] = true;
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
    <title>Login</title>
    <link rel ="stylesheet" href = "styles/master.css"/>
    <script src="https://kit.fontawesome.com/accfddd944.js" crossorigin="anonymous"></script>
</head>
<body>
    <?php include 'includes/navbar.php';?>
    <header>
        <h1><i class="fas fa-sign-in-alt"></i> Login</h1>
    </header>
    <main>
        <section id='loginbox'>
            <div>
                <div>
                    <i class="fas fa-user"></i>
                </div>
                <div>
                    <form id="login" name="login"  method="post">
                        <div id='usernamepassword'>
                            <label for="username" class='usernamepass'>Username:</label>
                            <input id="username" name="username" type="text" placeholder="" required/>
                        </div>
                        <div id='usernamepassword'>
                            <label for="password" class='usernamepass'>Password:</label>
                            <input id="password" name="password" type="password" placeholder="" required/>
                        </div>
                        <div>
                            <a href="forgotPassword.php">Forgot Password?</a>
                        </div>
                        <div>
                            <button type="submit" name="login">Login</button>
                        </div>
                        <span class="<?=!isset($errors['loginfail']) ? 'hidden' : "error";?>">Username or password incorrect.</span>
                        <div>
                            <label for="remember">Remember Me:</label>
                            <input type="checkbox" id="remember" name="remember">
                        </div>
                        <div>
                            <span>Dont have an account yet?</span>
                            <a href="createAccount.php">Signup</a>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </main>
</body>
</html>