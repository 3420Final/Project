<?php
  $username = $_POST['username'] ?? "";
  $password = $_POST['password'] ?? "";
  $gender = $_POST['gender'] ?? "";
  $email = $_POST['email'] ?? "";
  $name = $_POST['name'] ?? "";
  

  if (isset($_POST['submit'])) {

    include 'includes/library.php';
    $pdo = connectDB();

    $query = "INSERT INTO `timeslot_users` (username,password,gender,name,email) VALUES (?,?,?,?,?)"; //gender is an enum that goes from 1-4
    $stmt = $pdo->prepare($query);
    $stmt->execute([$username,$password,$gender,$name,$email]);
  }
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Create New User</title>
    <link rel ="stylesheet" href = "styles/ProfilePage.css"/>
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
    <form id="newuser" name="newuser"  method="post"><!--action="results.php" this was removed for testing by Bill-->
      <div>
        <label for="name">Name </label>
        <input type="text" id="name" name="name" pattern="[A-Za-z-0-9]+\s[A-Za-z-'0-9]+" title="firstname lastname" autocorrect="off" required/>
      </div>
      <div>
        <label for="email">Email </label>
        <input type="email" name="email" id="email" placeholder="test@test.com" required/>
      </div>
      <fieldset>
        <legend>Gender</legend>
          <div>
            <input type="radio" name="gender" id="male" value="1" />
            <label for="male">Male</label>
          </div>
          <div>
            <input type="radio" name="gender" id="female" value="2"/>
            <label for="female">Female</label>
          </div>
          <div>
            <input type="radio" name="gender" id="gnc" value="3"/>
            <label for="gnc">Gender Queer/Non-Conforming</label>
          </div>
        <div>
            <input type="radio" name="gender" id="notsay" value="4"/>
            <label for="notsay">Prefer not to say</label>
         </div>
        </fieldset>

      <div>
        <label for="username">Username </label>
        <input type="text" name="username" id="username" required/>
      </div>

      <div>
        <label for="passwd">Password </label>
        <input type="password" name="password" id="passwd" required/>
      </div>
      <div>
        <label for="passwd">Re-enter Password </label>
        <input type="password" name="password" id="passwd" required/>
      </div>
      <div><button type="submit" name="submit">Submit</button></div>
    </form>
    </main>
  </body>
</html>