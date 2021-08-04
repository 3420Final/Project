<?php
  $username = $_POST['username'] ?? "";
  $password1 = $_POST['password1'] ?? "";
  $password2 = $_POST['password2'] ?? "";
  $gender = $_POST['gender'] ?? "";
  $email = $_POST['email'] ?? "";
  $name = $_POST['name'] ?? "";

  $errors = array();

  
  
  if (isset($_POST['submit'])) {

    include 'includes/library.php';
    $pdo = connectDB();

    //sanitize all the inputs
    $username = filter_var($username, FILTER_SANITIZE_STRING);
    $password1 = filter_var($password1, FILTER_SANITIZE_STRING);
    $password2 = filter_var($password2, FILTER_SANITIZE_STRING);
    $gender = filter_var($gender, FILTER_SANITIZE_STRING);
    $email = filter_var($email, FILTER_SANITIZE_STRING);
    $name = filter_var($name, FILTER_SANITIZE_STRING);
    
    //check if the username given is already in the DB
    $query = "SELECT username,email FROM timeslot_users WHERE username=? OR email=?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$username,$email]);
    $results = $stmt->fetch();


    if($results == false){ //results should be empty(false) if the user doesnt exist
      if ($password1 !== $password2){  //make sure that the both the password fields are the same
        $errors['passwordsmatch'] = true;
      }
      if(strlen($username) > 20 ){
        $errors['usernametoolong'] = true;
      }
      if (count($errors) === 0){

        //hash the password 
        $hashpass = password_hash($password1, PASSWORD_DEFAULT);

        //insert the new user into the DB
        $query = "INSERT INTO `timeslot_users` (username,password,gender,name,email) VALUES (?,?,?,?,?)"; //gender is an enum that goes from 1-4
        $stmt = $pdo->prepare($query);
        $stmt->execute([$username,$hashpass,$gender,$name,$email]);

        //a function that uploads the photo, if the user uploaded one
        if(isset($_FILES)){
          //get the user ID
          $query = "SELECT `ID` FROM `timeslot_users` WHERE username=?";
          $stmt = $pdo->prepare($query);
          $stmt->execute([$username]);
          $userID = $stmt->fetch();
          
          try{
            $userID = $userID['ID'];
            include 'includes/fileupload.php';
            fileUpload($userID);
          }
          catch (exceptions $e){
            echo 'Something went horribly wrong with the image upload';
          }
        }

        header("Location:login.php");
        exit();
      }
    }
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
    <title>Create New User</title>
    <link rel ="stylesheet" href = "styles/master.css"/>
    <script defer src="plug-ins/checkforce.js-master/dist/checkforce.min.js"></script>
    <script defer src="scripts/createAccount.js"></script>
    <script src="https://kit.fontawesome.com/accfddd944.js" crossorigin="anonymous"></script>
  </head>
  <body>
    
    <header>
    <h1><i class="fas fa-user-plus"></i> Create Account</h1>
  </header>
  <main>
  <?php include 'includes/sidebar.php';?>
    <form id="uploadform"  method="post" enctype="multipart/form-data">
      <div>
        <img id='previewimage' src="images/profileImage.png" alt="Profile Image Icon" width="350" height="350" />
        <!--2MB restriction-->
        <input type="hidden" name="MAX_FILE_SIZE" value="2000000" />
        <label for="imgupload">Upload Profile Image:</label>
        <input type="file" name="imgupload" id="imgupload" onchange="previewFile()" />
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

      <div>
        <label for="password1">Password </label>
        <input type="password" name="password1" id="password1" required/>
        <span class='strength'></span>
      </div>
      <div>
        <label for="password2">Re-enter Password </label>
        <input type="password" name="password2" id="password2" required/>
      </div>
      <span class="<?=!isset($errors['passwordsmatch']) ? 'hidden' : "error";?>">Password Fields Dont Match</span>
      <span class="<?=!isset($errors['usernameexists']) ? 'hidden' : "error";?>">That Username is already taken, or the email is already in use</span>
      <span class="<?=!isset($errors['usernametoolong']) ? 'hidden' : "error";?>">That Username is too long. Less than 20 charachters please.</span>
      <div><button type="submit" name="submit">Submit</button></div>
    </form>
    </main>
  </body>
</html>