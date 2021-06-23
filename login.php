<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel ="stylesheet" href = "styles/login.css"/>
    <script src="https://kit.fontawesome.com/accfddd944.js" crossorigin="anonymous"></script>
</head>
<body>
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
                    <form>
                        <div id='emailandpassword'>
                            <label for="email" class='emailpass'>Email:</label>
                            <input id="email" name="email" type="text" placeholder="" required/>
                        </div>
                        <div id='emailandpassword'>
                            <label for="password" class='emailpass'>Password:</label>
                            <input id="password" name="password" type="password" placeholder="" required/>
                        </div>
                        <div>
                            <a href="forgotpassword.php">Forgot Password?</a>
                        </div>
                        <div>
                            <a href="mySignups.php">Login</a>
                            <!--I made it a link for right now just to make the transition from pages easier
                            <button id="login">Login</button>-->
                        </div>
                        <div>
                            <label for="remember">Remember Me:</label>
                            <input type="checkbox" id="remember" name="remember">
                        </div>
                        <div>
                            <span>Dont have an account yet?</span>
                            <a href="CreateAccount.php">Signup</a>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </main>
</body>
</html>