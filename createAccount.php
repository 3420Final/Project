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

        //todo list
        //profile picture
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
    <script src="https://kit.fontawesome.com/accfddd944.js" crossorigin="anonymous"></script>
  </head>
  <body>
    
    <header>
    <h1><i class="fas fa-user-plus"></i> Create Account</h1>
  </header>
  <main>
  <?php include 'includes/sidebar.php';?>
    <form id="uploadform" action="fileupload.php" method="post" enctype="multipart/form-data">
      <div>
        <img src="images/profileImage.png" alt="Profile Image Icon" width="350" height="350" />
        <!--this is required to restrict size of file upload in php-->
        <input type="hidden" name="MAX_FILE_SIZE" value="12400" />
        <label for="imgupload">Upload Profile Image:</label>
        <input type="file" name="imgupload" id="imgupload" />
      </div>
      <input type="submit" name="submit" value="Finished" />
    </form>
    <form id="newuser" name="newuser"  method="post">
      <div>
        <label for="name">Name </label>
        <input type="text" id="name" name="name" pattern="[A-Za-z-0-9]+\s[A-Za-z-'0-9]+" title="firstname lastname" autocorrect="off" value="<?=$name?>" required/>
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
        <input type="text" name="username" id="username" value="<?=$username?>"required/>
      </div>

      <div>
        <label for="passwd">Password </label>
        <input type="password" name="password1" id="passwd" required/>
      </div>
      <div>
        <label for="passwd">Re-enter Password </label>
        <input type="password" name="password2" id="passwd" required/>
      </div>
      <span class="<?=!isset($errors['passwordsmatch']) ? 'hidden' : "error";?>">Password Fields Dont Match</span>
      <span class="<?=!isset($errors['usernameexists']) ? 'hidden' : "error";?>">That Username is already taken, or the email is already in use</span>
      <span class="<?=!isset($errors['usernametoolong']) ? 'hidden' : "error";?>">That Username is too long. Less than 20 charachters please.</span>
      <div><button type="submit" name="submit">Submit</button></div>
    </form>
    </main>
  </body>
</html>