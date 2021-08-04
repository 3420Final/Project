<?php
session_start();
//if user not logged in, send them to login
if (!isset($_SESSION['username'])){
  header("Location:login.php");
}


/*SHOWING STUFF ON THE PAGE SECTION */
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

//some logic for converting the gender string values to the radio button numbers
if($gender == 'male'){
  $gender = 1;
}
else if($gender == 'female'){
  $gender = 2;
}
else if($gender == 'gqnc'){
  $gender = 3;
}
else{
  $gender = 4;
}


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

/*EDITING USER INFORMATION SECTION */

if (isset($_POST['submit'])) {
  $errors = array();

  //get the user ID before the username is changed
  $query = "SELECT `ID` FROM `timeslot_users` WHERE username=?";
  $stmt = $pdo->prepare($query);
  $stmt->execute([$username]);
  $userID = $stmt->fetch();
  $userID = $userID['ID'];
  
  //check if the username given is already in the DB
  $query = "SELECT ID, username,email FROM timeslot_users WHERE username=? OR email=?";
  $stmt = $pdo->prepare($query);
  $stmt->execute([$username,$email]);
  $results = $stmt->fetch();


  //results should be empty/false, else the username is already in use.
  //BUT if the user decides not to update their email/username, it WOULD already be under their username, but the UserID would be the same
  if($results == false || $results['ID'] == $userID){

    //get user input
    $username = $_POST['username'] ?? "";
    $gender = $_POST['gender'] ?? "";
    $email = $_POST['email'] ?? "";
    $name = $_POST['name'] ?? "";

    //sanitize user input
    $username = filter_var($username, FILTER_SANITIZE_STRING);
    $gender = filter_var($gender, FILTER_SANITIZE_STRING);
    $email = filter_var($email, FILTER_SANITIZE_STRING);
    $name = filter_var($name, FILTER_SANITIZE_STRING);

    if(strlen($username) > 20 ){
      $errors['usernametoolong'] = true;
    }
    if (count($errors) === 0){
      //update the SQL table
      $query = "UPDATE `timeslot_users` SET username=?, name=?, gender=?, email=? WHERE ID=?";
      $stmt = $pdo->prepare($query);
      $stmt->execute([$username, $name, $gender, $email, $userID]);
    
      $_SESSION['username'] = $username;

      //a function that uploads the photo, if the user uploaded one
      //also deletes their old photo
      if(isset($_FILES)){
        
        $query = "DELETE FROM `timeslot_images` WHERE userID = ?";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$userID]);

        try{
          require 'includes/fileupload.php';
          fileUpload($userID);
        }
        catch (RuntimeException $e){
          echo $e->getMessage();
          
        }

        //re-get their profile picture 
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
      }

    }
    
  }
    //error if username or email already exists
    else{
      $errors['usernameexists'] = true;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Edit Profile</title>
    <link rel ="stylesheet" href = "styles/master.css"/>
    <script defer src="scripts/createAccount.js"></script>
    <script src="https://kit.fontawesome.com/accfddd944.js" crossorigin="anonymous"></script>
  </head>
  <body>
    <header>
    <h1><i class="fas fa-user-plus"></i> Edit Account</h1>
  </header>
  <main>
  <?php include 'includes/editAccountSideBar.php';?>
    <form id="uploadform"  method="post" enctype="multipart/form-data">
      <div>
        <img src=<?=$path?> alt="Profile Image Icon" width="350" height="350" />
        <!--2MB restriction-->
        <input type="hidden" name="MAX_FILE_SIZE" value="2000000" />
        <label for="imgupload">Upload Profile Image:</label>
        <input type="file" name="imgupload" id="imgupload" />
      </div>
      <div>
        <label for="name">Name </label>
        <input type="text" id="name" name="name" pattern="[A-Za-z-0-9]+\s[A-Za-z-'0-9]+" title="firstname lastname" value="<?=$name?>" required/>
      </div>
      <div>
        <label for="email">Email </label>
        <input type="email" name="email" id="email" placeholder="test@test.com" value="<?=$email?>" required/>
      </div>
      <fieldset>
        <legend>Gender</legend>
          <div>
            <input type="radio" name="gender" id="male" value="1" <?=$gender == 1 ? 'checked' : ''?>/>
            <label for="male">Male</label>
          </div>
          <div>
            <input type="radio" name="gender" id="female" value="2" <?=$gender == 2 ? 'checked' : ''?>/>
            <label for="female">Female</label>
          </div>
          <div>
            <input type="radio" name="gender" id="gnc" value="3" <?=$gender == 3 ? 'checked' : ''?>/>
            <label for="gnc">Gender Queer/Non-Conforming</label>
          </div>
        <div>
            <input type="radio" name="gender" id="notsay" value="4" <?=$gender == 4 ? 'checked' : ''?>/>
            <label for="notsay">Prefer not to say</label>
         </div>
        </fieldset>
      <div>
        <label for="username">Username </label>
        <input type="text" name="username" id="username" value="<?=$username?>" required/>
      </div>
      <span class="<?=!isset($errors['usernameexists']) ? 'hidden' : "error";?>">That Username is already taken, or the email is already in use</span>
      <span class="<?=!isset($errors['usernametoolong']) ? 'hidden' : "error";?>">That Username is too long. Less than 20 charachters please.</span>
      <div><button type="submit" name="submit">Submit</button></div>
    </form>
    </main>
  </body>
</html>