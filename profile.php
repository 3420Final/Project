<?php
session_start();
//if user not logged in, send them to login
if (!isset($_SESSION['username'])){
  header("Location:login.php");
}
//get username
$username = $_SESSION['username'];

include 'includes/library.php';
$pdo = connectDB();

//get the user Info
$query = "select * from timeslot_users WHERE username = ?";
$stmt = $pdo->prepare($query);
$stmt->execute([$username]);
$userInfo = $stmt->fetch();
$userID = $userInfo['ID'];
$gender = $userInfo['gender'];
$name = $userInfo['name'];
$email = $userInfo['email'];


//get their profile picture 
$query = "select filepath from timeslot_images WHERE userID = ?";
$stmt = $pdo->prepare($query);
$stmt->execute([$userID]);
$path = $stmt->fetch();


//if profile picture doesnt exist, use blank
if(!$path){
  $path = "images/profileImage.png";
}
else{
  $path = $path['filepath'];
}

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Profile</title>
    <link rel ="stylesheet" href = "styles/master.css"/>
    <script src="https://kit.fontawesome.com/accfddd944.js" crossorigin="anonymous"></script>
  </head>
  <body>
    <?php include 'includes/navbar.php';?>
    <header>
    <h1><i class="fas fa-user"></i> My Profile</h1>
  </header>
  <main>
    <nav id='sidebar'>
      <ul>
        <li><a href="mySignups.php">Back</a></li>
        <li><a href="editAccount.php">Edit Profile</a></li>
        <li><a href="deleteAccount.php">DeleteAccount</a></li>
      </ul>
    </nav>
      <div>
        <img src=<?=$path?> alt="Profile Image Icon" width="350" height="350" /> 
      </div>
    <form id="newuser" name="newuser" action="results.php" method="post">
        <div>
            <label for="name">Name </label>
            <input type="text" id="name" name="name" 
              title="firstname lastname"  value = <?=$name?> readonly required/>
          </div>
          <div>
            <label for="email">Email </label>
            <input type="email" name="Email" id="email" value=<?=$email?> readonly />
          </div>

      <fieldset disabled>
        <legend>Gender</legend>
          <div>
            <input type="radio" name="gender" id="male" value="m" <?=($gender == 'male') ? 'checked' : '' ?>/>
            <label for="male">Male</label>
          </div>
          <div>
            <input type="radio" name="gender" id="female" value="f" <?=($gender == 'female') ? 'checked' : ''?>/>
            <label for="female">Female</label>
          </div>
          <div>
            <input type="radio" name="gender" id="gnc" value="gnc" <?=($gender == 'gqnc') ? 'checked' : ''?>/>
            <label for="gnc">Gender Queer/Non-Conforming</label>
          </div>
        <div>
            <input type="radio" name="gender" id="notsay" value="notsay" <?=($gender == 'notsay') ? 'checked' : ''?>/>
            <label for="notsay">Prefer not to say</label>
         </div>
        </fieldset>

      <div>
        <label for="username">Username </label>
        <input type="text" name="username" id="username" value=<?=$username?> readonly/>
      </div>
    </form>
    </main>
  </body>
</html>