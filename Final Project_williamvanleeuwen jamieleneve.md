[TOC]

# COIS 3420 Final Project

Name: Bill Van Leeuwen, Jamie Le Neve

Live Link: https://loki.trentu.ca/~williamvanleeuwen/Project/index.php

Login Credentials:

Feel free to create your own account, that way you can enter your own email to receive password resets.

​	username: williamvanleeuwen

​	password: aReallyEasyPassword

​    email: williamvanleeuwen@trentu.ca



## index.php

```html
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://kit.fontawesome.com/c2cee199ac.js" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="styles/master.css" />
  <title>Main Page</title>
</head>
<body>
  <?php include 'includes/navbar.php';?>
  <section id="mainspace">
    <div class="container">
      <!--Citation for the image below: https://www.flexjobs.com/blog/post/essential-time-management-skills/-->
      <img src="images/MainpageImage.jpg" alt="computer with hour glass filled with blue sand" height="700"/>
      <div class="content">
        <h1>Welcome!</h1>
        <p>Create and Manage Sign-Up Sheets and sign-up for time slots on other peoples sheets!</p>
      </div>
    </div>
  </section>
</body>
</html>
```

### Testing

#### Firefox:

![img](https://lh5.googleusercontent.com/rjQnE1ha_SzB0cvxvNghYvju1wdFutZ-jeJ9yqx-VYuOc83Bt_AYDLxaW15yen33h8g7hxBkyC1YetcjSzazy8J2_4A8EMidwkBHiuH5pDSTNGS_5hPOvpOKhFe2vsO3fRfCSEmu)

#### Chrome:

![image-20210804121102884](C:\Users\billj\Documents\TrentU\Cois-3420\Loki\3420\finalProjSnips\ImagesMD\image-20210804121102884.png)

#### HTML Validator:

![image-20210804120907921](C:\Users\billj\Documents\TrentU\Cois-3420\Loki\3420\finalProjSnips\ImagesMD\image-20210804120907921.png)

## bookslotNonUser.php

```php+HTML
<?php
$name = $_POST['name'] ?? null;
$email = $_POST['email'] ?? null;

$sheetID = $_GET["sheetID"];
$slotID = $_GET["slotID"];

if (isset($_POST['submit'])) {
  
  include 'includes/library.php';
  $pdo = connectDB();
  
  //create a random string as guest username
  $username = bin2hex(random_bytes(5));
  $username = 'guest' . $username;

  //insert a guest account into the usernames
  $query = "INSERT INTO `timeslot_users` (username,name,email) VALUES (?,?,?)"; 
  $stmt = $pdo->prepare($query);
  $stmt->execute([$username,$name,$email]);
  
  //get the user ID
  $query = "SELECT `ID` FROM `timeslot_users` WHERE username=?";
  $stmt = $pdo->prepare($query);
  $stmt->execute([$username]);
  $userID = $stmt->fetch();
  $userID = $userID['ID'];

  try{
    //update the slot
    $query = "UPDATE `timeslot_slots` SET userID=? WHERE ID=?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$userID,$slotID]); 

    //update the sheet table to reflect how many people are booked
    $query = "SELECT COUNT(*) FROM `timeslot_slots` WHERE sheetID=? AND userID IS NOT NULL ";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$sheetID]); 
    $slotsBooked = $stmt->fetch();
    $slotsBooked = $slotsBooked['COUNT(*)'];
    $slotsBooked++;

    $query = "UPDATE `timeslot_sheets` SET numslotsfilled=? WHERE ID=?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$slotsBooked,$sheetID]); 
  }
  //if this exception triggers, it is likely that the guest didnt get inserted correctly
  catch (exceptions $e){
    echo 'Something went wrong with booking';
  }

  
  header("Location:slotThanks.php");
  exit();
}

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Book a Slot as a Guest</title>
    <link rel ="stylesheet" href = "styles/master.css"/>
    <script src="https://kit.fontawesome.com/accfddd944.js" crossorigin="anonymous"></script>
  </head>
  <body>  
    <header>
    <h1><i class="fas fa-user-plus"></i> Book Slot</h1>
    </header>
    <main>
    <?php include 'includes/sidebar.php';?>
      <form id="newuser" name="newuser"  method="post">
        <div>
          <label for="name">Name </label>
          <input type="text" id="name" name="name" pattern="[A-Za-z-0-9]+\s[A-Za-z-'0-9]+" title="firstname lastname" value="<?=$name?>" required/>
        </div>
        <div>
          <label for="email">Email </label>
          <input type="email" name="email" id="email" placeholder="test@test.com" value="<?=$email?>" required/>
          <span class="<?=!isset($errors['validEmail']) ? 'hidden' : "error";?>">Please enter a valid email</span>
        </div>
        <div><button type="submit" name="submit">Submit</button></div>
      </form>
    </main>
  </body>
</html>
```

### Testing

to get here, the user must search the name of the sheet in the search for slot page.

#### Firefox:

![img](https://lh4.googleusercontent.com/4SHwIPFUIxkv_cX9ZLdpMGBmFMigftoiOg3BFnSMeoRda4hob4UI2HsXwPbWbI--ERFwF1L2Q1XHKufK5jaA-6ZgWLBFp8yX5c2Ow2G4a_3hSuHZ504_WOfs4Qn2RLKqkMg05qih)

![bookSlotNonUser](C:\Users\billj\Documents\TrentU\Cois-3420\Loki\3420\finalProjSnips\bookSlotNonUser.JPG)

![bookslotnonuser2](C:\Users\billj\Documents\TrentU\Cois-3420\Loki\3420\finalProjSnips\bookslotnonuser2.JPG)

Proof that Snape got booked in for the slot. We accomplished this buy adding guest users as seen below.

![bookslotnonuser3](C:\Users\billj\Documents\TrentU\Cois-3420\Loki\3420\finalProjSnips\bookslotnonuser3.JPG)

![bookSlotNonUser4](C:\Users\billj\Documents\TrentU\Cois-3420\Loki\3420\finalProjSnips\bookSlotNonUser4.JPG)

#### Chrome:

![image-20210804091833945](C:\Users\billj\Documents\TrentU\Cois-3420\Loki\3420\finalProjSnips\ImagesMD\image-20210804091833945.png)

#### HTML Validator:

![image-20210804092215780](C:\Users\billj\Documents\TrentU\Cois-3420\Loki\3420\finalProjSnips\ImagesMD\image-20210804092215780.png)

## changePassword.php

```php+HTML
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
```

### Testing

#### Firefox:

![img](https://lh3.googleusercontent.com/tX_eiEAsa8ajX1lebg_82JiFasa2PDkuDtQjPfRrQAEQl5CgjcM1MZujoRHUB4MORyWmsjGq2JnTmzeEK6EcrlveF5PWyVxMmmKF29HWJpUfGzvcVdSpZCJmhzVmEiNEBg1IZyIm)

![changePassword1](C:\Users\billj\Documents\TrentU\Cois-3420\Loki\3420\finalProjSnips\changePassword1.JPG)

![changePassword2](C:\Users\billj\Documents\TrentU\Cois-3420\Loki\3420\finalProjSnips\changePassword2.JPG)

after clicking the link

![changePassword3](C:\Users\billj\Documents\TrentU\Cois-3420\Loki\3420\finalProjSnips\changePassword3.JPG)

what happens if you try to go to this page without a valid key??

Disabled text fields and submit button.

![changePassword4](C:\Users\billj\Documents\TrentU\Cois-3420\Loki\3420\finalProjSnips\changePassword4.JPG)

#### Chrome:

![image-20210804092556193](C:\Users\billj\Documents\TrentU\Cois-3420\Loki\3420\finalProjSnips\ImagesMD\image-20210804092556193.png)

#### HTML Validator:

![image-20210804092639410](C:\Users\billj\Documents\TrentU\Cois-3420\Loki\3420\finalProjSnips\ImagesMD\image-20210804092639410.png)

## checkEmail.php

This is just a script used for AJAX

```php
<?php
//this is a simple php script to check if the email passed by get has already been taken


//get toy request from GET array
$email = $_GET['email'] ?? null;
//make sure it's  valid
if (!$email) {
    echo 'error';
    exit();
}

//sanitize user input
$email = filter_var($email, FILTER_SANITIZE_STRING);

require 'includes/library.php';
$pdo = connectDB();

//check if the email is already inputted into the DB
$statement = $pdo->prepare("SELECT email FROM `timeslot_users` WHERE email = ?");
$statement->execute([$email]);
$row = $statement->fetch();

//if the email doesnt already exist in the DB
if (!$row) {
    echo 'true'; 
} else {
    echo 'false'; 
}
exit();
?>
```



## checkUsername.php

This is just a script used for AJAX

```php
<?php
//this is a simple php script to check if the username passed by get has already been taken


//get toy request from GET array
$username = $_GET['username'] ?? null;
//make sure it's  valid
if (!$username) {
    echo 'error';
    exit();
}

//sanitize user input
$username = filter_var($username, FILTER_SANITIZE_STRING);

require 'includes/library.php';
$pdo = connectDB();

//check if the username is already inputted into the DB
$statement = $pdo->prepare("SELECT username FROM `timeslot_users` WHERE username = ?");
$statement->execute([$username]);
$row = $statement->fetch();

//if the username doesnt already exist in the DB
if (!$row) {
    echo 'true'; 
} else {
    echo 'false'; 
}
exit();
?>
```



## copySheet.php

```php+HTML
<?php 
  session_start();
  include 'includes/library.php';
  $pdo = connectDB();

  $dateTime = null;

  $creator = $_SESSION['username'];

  $query = "select * from timeslot_users WHERE username = ?";
  $stmt = $pdo->prepare($query);
  $stmt->execute([$creator]);
  $host = $stmt->fetch();

  if ((isset($_GET["id"])) && (!isset($_POST["submit"]))){
    $query = "select * from timeslot_sheets WHERE ID = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$_GET["id"]]);
    $sheet = $stmt->fetch();
  
    $query = "select * from timeslot_slots WHERE sheetID = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$_GET["id"]]);
    $slots = $stmt->fetchAll();
  
    $query = "select location, notes from timeslot_slots WHERE sheetID = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$_GET["id"]]);
    $slotInfo = $stmt->fetch();
  
    $query = "INSERT INTO timeslot_sheets VALUES (NULL, ?,?,?,?,?,?, NOW())";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$sheet["numslots"], $sheet["name"], $sheet["numslotsfilled"], $sheet["description"], $sheet["privacy"], $host["ID"]]);
    $id = $pdo->lastInsertID();
    foreach ($slots as $slot){
      $query = "insert into timeslot_slots values (NULL,?,?,?,?,?,NULL)";
      $stmt = $pdo->prepare($query);
      $stmt->execute([$id, $slot["date"], $slot["time"], $slot["location"], $slot["notes"]]);
    }
  }

  $title = $_POST['title'] ?? $sheet["name"];
  $description = $_POST['description'] ?? $sheet["description"];
  $location = $_POST['location'] ?? $slotInfo["location"];
  $privacy = $_POST['status'] ?? $sheet["privacy"];
  $numSlots = $_POST['numSlots'] ?? $sheet["numslots"];
  $notes = $_POST['notes'] ?? $slotInfo["notes"];

  $errors = array();


  if (isset($_POST['submit'])){

    //get the num slots posted
    for($i = 0; $i<$numSlots;$i++){
      if(isset($_POST["dateTime" .  $i ])){
        $dateTime[$i] = $_POST["dateTime" .  $i ];
        if ($dateTime[$i] == "") {
          $errors['dateTime'] = true;
        }
      }
    }
    var_dump($dateTime);
    $description = $_POST['description'];
    $privacy = $_POST['status'];
    $notes = $_POST['notes'];
    $title = $_POST['title'];
    $location = $_POST['location'];
    
    //sanitize all the textbox inputs
    $description = filter_var($description, FILTER_SANITIZE_STRING);
    $notes = filter_var($notes, FILTER_SANITIZE_STRING);
    //validate user has entered a title
    if (!isset($title) || strlen($title) === 0) {
      $errors['title'] = true;
    }


    //validate user has entered a desc
    if (!isset($description)) {
      $errors['description'] = true;
    }

    //validate user has entered a location
    if (!isset($location) || strlen($location) === 0) {
      $errors['location'] = true;
    }

      if (empty($privacy)) {
        $errors['privacy'] = true;
      }

      //only do this if there weren't any errors
      if (count($errors) === 0) {
        //update sheet
        $query = "UPDATE `timeslot_sheets` SET name = ?, description = ?, privacy = ? WHERE ID=?";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$title, $description, $privacy, $sheetID]);

        //update slots
        $query = "UPDATE `timeslot_slots` SET location=?, notes=? WHERE sheetID=?";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$location, $notes, $sheetID]);


        for ($i = 0; $i < $numSlots; $i ++){
          if(isset($date[$i])){

          $date[$i] = substr($dateTime[$i], 0, 10);
          $time[$i] = substr($dateTime[$i], 10);


          $query = "insert into `timeslot_slots` (sheetID, date,time,location,notes) values (?,?,?,?,?)";
          $stmt = $pdo->prepare($query);
          $stmt->execute([$sheetID, $date[$i], $time[$i], $location, $notes]);
        }
      }
        //send the user to the thankyou page.

        header("Location:sheetThanks.php");
        exit();
      }
      
  }

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Copy Sign-Up Sheet</title>
    <link rel ="stylesheet" href = "styles/master.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.2.3/flatpickr.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.2.3/themes/dark.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.2.3/flatpickr.js"></script>
    <script defer src="scripts/editSheet.js"></script>
    <script src="https://kit.fontawesome.com/accfddd944.js" crossorigin="anonymous"></script>
  </head>
  <body id = "signUpSheet">
      <?php include 'includes/navbar.php';?>
      <header>
        <h1><i class="far fa-edit"></i> Finalize Sign-Up Sheet</h1>
      </header>
      <main>
        <nav>
          <ul>
            <li><a href="mySignups.php">Back</a></li>
          </ul>
        </nav>
        <section>
          <h2>Sign-Up Sheet Details</h2>
          <form id="sheet" action="<?=htmlentities($_SERVER['PHP_SELF'])?>" method="POST" autocomplete="off">
            <div>
              <label for="title">Title</label>
              <input id="title" name="title" type="text" placeholder="Project Check-In #1" value="<?=$title?>"/>
              <span class="error <?=!isset($errors['title']) ? 'hidden' : "";?>">Please enter a title</span>
            </div>
            <div>
              <label for="creator">Creator</label>
              <input id="creator" name="creator" type="text" value="<?=$creator?>" disabled/>
              <span class="error <?=!isset($errors['creator']) ? 'hidden' : "";?>">Please enter your username</span>
            </div>
            <div>
              <label for="description">Description</label>
              <textarea name="description" id="description" cols="30" rows="10"><?=$description?></textarea>
              <span class="error <?=!isset($errors['description']) ? 'hidden' : "";?>">Please enter a description</span>
            </div>
            <div>
              <label for="location">Loaction</label>
              <input id="location" name="location" type="text" placeholder="Remote via Zoom" value="<?=$location?>"/>
              <span class="error <?=!isset($errors['location']) ? 'hidden' : "";?>">Please enter a location</span>
            </div>
            <fieldset>
              <legend>Privacy</legend>
              <div>
                <input id="public" name="status" type="radio" value="public" <?=$privacy == "public" ? 'checked' : ''?>/>
                <label for="public">Public</label>
              </div>
              <div>
                <input id="private" name="status" type="radio" value="private" <?=$privacy == "private" ? 'checked' : ''?>/>
                <label for="private">Private</label>
              </div>
              <span class="error <?=!isset($errors['privacy']) ? 'hidden' : "";?>">Please select a privacy setting</span>
            </fieldset>
            <div>
              <label for="notes">Notes</label>
              <textarea name="notes" id="notes" cols="30" rows="10"><?=$notes?></textarea>
            </div>
              <div>
                <label for="numSlots">Number of Time Slots</label>
                <input id="numSlots" name="numSlots" type="number" value="<?=$numSlots?>"/>
                <span class="error <?=!isset($errors['numSlots']) ? 'hidden' : "";?>">Please enter the number of time slots in this sheet</span>
              </div>
              <div class = "table"> 
              <table>
                <thead>
                  <tr>
                    <th>What</th>
                    <th>When</th>
                    <th>Book</th>
                  </tr>
                </thead>
                <tbody>
                  <?php for ($i = 1; $i <= $numSlots; $i++): ?>
                    <?php

                    $slot = $slots[$i-1];

                    $dateTime = $slot['date'] . ' ' . $slot['time'];
                    ?>
                    <tr>
                      <td><?=$title?></td>
                      <td>
                        <div>
                          <label for="basicDate">Date and Time: </label>
                          <input type="text" name="dateTime<?=$i-1?>" id="basicDate" placeholder="Please select Date Time" <?php if($slot["userID"] != null) echo'disabled'?> data-input value="<?= ($dateTime == null) ? null : $dateTime?>"  >
                          <span class="error <?=!isset($errors['dateTime']) ? 'hidden' : "";?>">Please enter a date</span>
                        </div>
                      </td>
                      <?php if ($slot["userID"] == null): ?>
                        <td><button name="submit" disabled>Book Time Slot</button></td>
                      <?php else: ?>
                        <td>
                          <?php
                          $query = "select * from `timeslot_users` WHERE ID= ?";
                          $stmt = $pdo->prepare($query);
                          $stmt->execute([$slot["userID"]]);
                          $slotParticipant = $stmt->fetch();

                          echo "$slotParticipant[name]";
                          ?>
                        </td>
                      <?php endif ?>
                    </tr>
                  <?php endfor ?>
                </tbody>
              </table>
            </div>
            <div>
              <button type="button" name="addSlot" id="addSlot">Add Another Time Slot</button>
            </div>
            <div>
              <button type="submit" name="submit">Change Sheet</button>
            </div>
          </form>
        </section>
      </main>
  </body>
</html>

```

### Testing

#### Firefox:

![copysheet1](C:\Users\billj\Documents\TrentU\Cois-3420\Loki\3420\finalProjSnips\copysheet1.JPG)

![copysheet2](C:\Users\billj\Documents\TrentU\Cois-3420\Loki\3420\finalProjSnips\copysheet2.JPG)

![copysheet3](C:\Users\billj\Documents\TrentU\Cois-3420\Loki\3420\finalProjSnips\copysheet3.JPG)



#### Chrome:

![image-20210804092946025](C:\Users\billj\Documents\TrentU\Cois-3420\Loki\3420\finalProjSnips\ImagesMD\image-20210804092946025.png)

![image-20210804092959816](C:\Users\billj\Documents\TrentU\Cois-3420\Loki\3420\finalProjSnips\ImagesMD\image-20210804092959816.png)

![image-20210804093015636](C:\Users\billj\Documents\TrentU\Cois-3420\Loki\3420\finalProjSnips\ImagesMD\image-20210804093015636.png)

#### HTML Validator:

Fixing the errors shown in the validator prevent the functionality of the plug-in

![image-20210804102217794](C:\Users\billj\Documents\TrentU\Cois-3420\Loki\3420\finalProjSnips\ImagesMD\image-20210804102217794.png)

## createAccount.php

```php+HTML
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
```

### Testing

#### Firefox:

![img](https://lh5.googleusercontent.com/zmusA1ZSTngQ9Z5GAaTzkA3W0Z5IB2uq2m-vjBc8iAaWHqtRBQz5vlwCMdHqq7rSYpqej_hXMTAN5P3a0PwJlBv9CkY_SNGhpF3iMWojUvtTzXU5WUjLTqqLeGPkgANXSHqLwmCZ)

![img](https://lh4.googleusercontent.com/_qex04UHH3C3XXUPoojKuyea0EzVGeega7nNMDj-38XEuJUr8-02Yaf7-PMSQg0e39k2r_SEwdZ3uiIiLcv0zjwR38orxSqNfVDH7aBVN5HoE7BxIsaW4nfK6BVZx_TEzaNpXeMq)



Example 1: entering invalid information



![createFirefox1](C:\Users\billj\Documents\TrentU\Cois-3420\Loki\3420\finalProjSnips\createFirefox1.JPG)

![createFirefox2](C:\Users\billj\Documents\TrentU\Cois-3420\Loki\3420\finalProjSnips\createFirefox2.JPG)



Example 2: Entering Valid information (ps: notice the cool image preview )

![createFirefox3](C:\Users\billj\Documents\TrentU\Cois-3420\Loki\3420\finalProjSnips\createFirefox3.JPG)

![createFirefox4](C:\Users\billj\Documents\TrentU\Cois-3420\Loki\3420\finalProjSnips\createFirefox4.JPG)

proof the user got submitted with a hashed password

![createSQL1](C:\Users\billj\Documents\TrentU\Cois-3420\Loki\3420\finalProjSnips\createSQL1.JPG)

and that the users image got inserted

![createSQL2](C:\Users\billj\Documents\TrentU\Cois-3420\Loki\3420\finalProjSnips\createSQL2.JPG)

#### Chrome:

![image-20210804102730071](C:\Users\billj\Documents\TrentU\Cois-3420\Loki\3420\finalProjSnips\ImagesMD\image-20210804102730071.png)

![image-20210804102742238](C:\Users\billj\Documents\TrentU\Cois-3420\Loki\3420\finalProjSnips\ImagesMD\image-20210804102742238.png)

#### HTML Validator:

![image-20210804102955122](C:\Users\billj\Documents\TrentU\Cois-3420\Loki\3420\finalProjSnips\ImagesMD\image-20210804102955122.png)

## deleteAccount.php

```php+HTML
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
if (isset($_POST['deleteAccount'])){
    $query = "DELETE FROM `timeslot_users` WHERE ID = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$userID]);

    header("Location:thanks.php");
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Delete Profile</title>
    <link rel ="stylesheet" href = "styles/master.css"/>
    <script defer src="scripts/deleteProfile.js"></script>
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
        <li><a href="profile.php">Cancel</a></li>
      </ul>
    </nav>
      <div>
        <img src=<?=$path?> alt="Profile Image Icon" width="350" height="350" /> 
      </div>
    <form id="newuser" name="newuser" method="post">
        <div>
            <label for="name">Name </label>
            <input type="text" id="name" name="name" title="firstname lastname"  value = <?=$name?> readonly/>
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
      <div><button type="submit" name="deleteAccount">Delete Account</button></div>
    </form>
    </main>
  </body>
</html>
```

### Testing

#### Firefox:

We will delete this account for testing

![deleteaccount1](C:\Users\billj\Documents\TrentU\Cois-3420\Loki\3420\finalProjSnips\deleteaccount1.JPG)



![deleteaccount2](C:\Users\billj\Documents\TrentU\Cois-3420\Loki\3420\finalProjSnips\deleteaccount2.JPG)



![deleteaccount3](C:\Users\billj\Documents\TrentU\Cois-3420\Loki\3420\finalProjSnips\deleteaccount3.JPG)

![deleteaccount4](C:\Users\billj\Documents\TrentU\Cois-3420\Loki\3420\finalProjSnips\deleteaccount4.JPG)

Notice below the user is gone!

![deleteaccount5](C:\Users\billj\Documents\TrentU\Cois-3420\Loki\3420\finalProjSnips\deleteaccount5.JPG)



#### Chrome:

![image-20210804103453868](C:\Users\billj\Documents\TrentU\Cois-3420\Loki\3420\finalProjSnips\ImagesMD\image-20210804103453868.png)

![image-20210804103505778](C:\Users\billj\Documents\TrentU\Cois-3420\Loki\3420\finalProjSnips\ImagesMD\image-20210804103505778.png)

![image-20210804103557187](C:\Users\billj\Documents\TrentU\Cois-3420\Loki\3420\finalProjSnips\ImagesMD\image-20210804103557187.png)

#### HTML Validator:

The validator did not like my last name and through an error

![image-20210804103951223](C:\Users\billj\Documents\TrentU\Cois-3420\Loki\3420\finalProjSnips\ImagesMD\image-20210804103951223.png)

## deleteSheet.php

```php+HTML
<?php 
  include 'includes/library.php';
  $pdo = connectDB();

  $query = "delete from timeslot_slots where sheetID = ?";
  $stmt = $pdo->prepare($query);
  $stmt->execute([$_GET["id"]]);

  $query = "delete from timeslot_sheets where ID = ?";
  $stmt = $pdo->prepare($query);
  $stmt->execute([$_GET["id"]]);
?>
<!DOCTYPE html>
<html lang="en">
  <head>
      <meta charset="utf-8" />
      <meta name="viewport" content="width=device-width, initial-scale=1.0" />
      <title>Done!</title>
      <link rel="stylesheet" href="styles/master.css" />
  </head>
  <body id="mySignUps">
      <?php include 'includes/navbar.php';?>
        <header>
          <h1><i class="fas fa-clipboard-check"></i> Sheet Deleted!</h1>
        </header>
        <main>
          <nav>
            <ul>
              <li><a href="mySignups.php">Home</a></li>
            </ul>
          </nav>
          <section>
            <h3>This sheet has now been deleted!</h3>
          </section>
      </main>
  </body>
</html>
```

### Testing

#### Firefox:

![img](https://lh4.googleusercontent.com/hlpFXwd0eugo74BFNVXdehR8wcgJRumu_N87U8ooLeCVqAohr0UUXq0_xlMlyTdUQHofSZjKNBzJkm1cLqtXAb1dbhK9LMOt4ggSqkCGf-jk_QQ7NijZ3X2u6ZbiHUg8BkwcYfjE)

![img](https://lh5.googleusercontent.com/S_brotGkhwX8H7lzNdCsEQS709DV4cbFl8OwXzc91INt0OM8gOZR62VMJELpTj9gg8WK4kiH_S5H64OSC7IXOGNWhd_UaPeIJnt5qotvPaZ5AlRR6sSH16DYdEmtoRR793AAXLHo)

#### Chrome:

![image-20210804112247840](C:\Users\billj\Documents\TrentU\Cois-3420\Loki\3420\finalProjSnips\ImagesMD\image-20210804112247840.png)

![image-20210804112029847](C:\Users\billj\Documents\TrentU\Cois-3420\Loki\3420\finalProjSnips\ImagesMD\image-20210804112029847.png)

#### HTML Validator:

![image-20210804112319660](C:\Users\billj\Documents\TrentU\Cois-3420\Loki\3420\finalProjSnips\ImagesMD\image-20210804112319660.png)

## deleteTimeSlot.php

```php+HTML
<?php 
  include 'includes/library.php';
  $pdo = connectDB();

  $query = "UPDATE timeslot_slots SET userID = NULL WHERE ID = ?";
  $stmt = $pdo->prepare($query);
  $stmt->execute([$_GET["id"]]);
?>
<!DOCTYPE html>
<html lang="en">
  <head>
      <meta charset="utf-8" />
      <meta name="viewport" content="width=device-width, initial-scale=1.0" />
      <title>Done!</title>
      <link rel="stylesheet" href="styles/master.css" />
  </head>
  <body id="mySignUps">
      <?php include 'includes/navbar.php';?>
        <header>
          <h1><i class="fas fa-clipboard-check"></i> Time Slot Cancelled!</h1>
        </header>
        <main>
          <nav>
            <ul>
              <li><a href="mySignups.php">Home</a></li>
            </ul>
          </nav>
          <section>
            <h3>This time slot has now been cancelled!</h3>
          </section>
      </main>
  </body>
</html>
```

### Testing

#### Firefox:

![img](https://lh4.googleusercontent.com/zHo_ekgisC4hiKdfSnigu3un_E6kyoM5-T7IvgBEObm5iTu4ljQtam_066Va867zeAqxd9fAegUgnS_W7z7Rmc3udsOBYXLyVv_yhOvH5ydlc9ceKJZ7RNJMEqWh3fCHBsI6OswX)

![img](https://lh4.googleusercontent.com/Oi6Sh3NqXzapzSULKP98drTy7C2iy0tE6CYpkBxwXBYCXOPHPCbTLeN0QsPFnazwczFMmG38WtBoQIZGApnycC27FRvnCIGeqcPyT0bGEy7_8hpceQc09UiKXlLRS5molYraVSEF)

#### Chrome:

![image-20210804113001265](C:\Users\billj\Documents\TrentU\Cois-3420\Loki\3420\finalProjSnips\ImagesMD\image-20210804113001265.png)

![image-20210804113012721](C:\Users\billj\Documents\TrentU\Cois-3420\Loki\3420\finalProjSnips\ImagesMD\image-20210804113012721.png)

#### HTML Validator:

![image-20210804113034577](C:\Users\billj\Documents\TrentU\Cois-3420\Loki\3420\finalProjSnips\ImagesMD\image-20210804113034577.png)

## editAccount.php

```php+HTML
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
    <script defer src="scripts/editAccount.js"></script>
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
        <img src=<?=$path?> alt="Profile Image Icon" width="350" height="350" id='previewimage'/>
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
      <span class="<?=!isset($errors['usernameexists']) ? 'hidden' : "error";?>">That Username is already taken, or the email is already in use</span>
      <span class="<?=!isset($errors['usernametoolong']) ? 'hidden' : "error";?>">That Username is too long. Less than 20 charachters please.</span>
      <div><button type="submit" name="submit">Submit</button></div>
    </form>
    </main>
  </body>
</html>
```

### Testing

#### Firefox:

![editAccount1](C:\Users\billj\Documents\TrentU\Cois-3420\Loki\3420\finalProjSnips\editAccount1.JPG)



Example 1: changing to invalid things

![editAccount2](C:\Users\billj\Documents\TrentU\Cois-3420\Loki\3420\finalProjSnips\editAccount2.JPG)

okay, now some valid things

![editAccount3](C:\Users\billj\Documents\TrentU\Cois-3420\Loki\3420\finalProjSnips\editAccount3.JPG)

proof that it worked

![editAccount4](C:\Users\billj\Documents\TrentU\Cois-3420\Loki\3420\finalProjSnips\editAccount4.JPG)

#### Chrome:

![image-20210804113221958](C:\Users\billj\Documents\TrentU\Cois-3420\Loki\3420\finalProjSnips\ImagesMD\image-20210804113221958.png)

![image-20210804113237879](C:\Users\billj\Documents\TrentU\Cois-3420\Loki\3420\finalProjSnips\ImagesMD\image-20210804113237879.png)

#### HTML Validator:

![image-20210804113327669](C:\Users\billj\Documents\TrentU\Cois-3420\Loki\3420\finalProjSnips\ImagesMD\image-20210804113327669.png)

## editAccountSideBar.php

```php
<nav id='sidebar'>
    <ul>
        <li><a href="index.php">Home</a></li>
        <li><a href="changePassword.php">Change Password</a></li>
    </ul>
</nav>
```



## editSheet.php

```php+HTML
<?php 

  $dateTime = null; 

  session_start();
  include 'includes/library.php';
  $pdo = connectDB();
  
  $errors = array();
  $_SESSION["sheetID"] = $_GET["id"] ?? $_SESSION["sheetID"];
  $sheetID = $_SESSION["sheetID"];
  //get sheet
  $query = "select * from timeslot_sheets WHERE ID = ?";
  $stmt = $pdo->prepare($query);
  $stmt->execute([$sheetID]);
  $sheet = $stmt->fetch();

  //get slots
  $query = "select * from timeslot_slots WHERE sheetID = ?";
  $stmt = $pdo->prepare($query);
  $stmt->execute([$sheetID]);
  $slots = $stmt->fetchAll();
  
  $creator = $_SESSION['username'];
  $title = $_POST['title'] ?? $sheet["name"];
  $description = $_POST['description'] ?? $sheet["description"];
  $location = $_POST['location'] ?? $slots[0]["location"];
  $privacy = $_POST['status'] ?? $sheet["privacy"];
  $numSlots = $_POST['numSlots'] ?? $sheet["numslots"];
  $notes = $_POST['notes'] ?? $slots[0]["notes"];

  if (isset($_POST['submit'])){

    
    //get the details from all the slots
    for($i = 0; $i<$numSlots;$i++){
      if(isset($slots[$i])){
        $slotIDs[$i] = $slots[$i]['ID'];
      }
        if(isset($_POST["dateTime" .  $i ])){
        $dateTime[$i] = $_POST["dateTime" .  $i ];
        if ($dateTime[$i] == "") {
          $errors['dateTime'] = true;
        }
      }
      if(isset($_POST["delete" .  $i ])){
        $delete[$i] = $_POST["delete" .  $i ];
      }
    }

    

    $description = $_POST['description'];
    $privacy = $_POST['status'];
    $notes = $_POST['notes'];
    $title = $_POST['title'];
    $location = $_POST['location'];
    
    //sanitize all the textbox inputs
    $description = filter_var($description, FILTER_SANITIZE_STRING);
    $notes = filter_var($notes, FILTER_SANITIZE_STRING);
    //validate user has entered a title
    if (!isset($title) || strlen($title) === 0) {
      $errors['title'] = true;
    }


    //validate user has entered a desc
    if (!isset($description)) {
      $errors['description'] = true;
    }

    //validate user has entered a location
    if (!isset($location) || strlen($location) === 0) {
      $errors['location'] = true;
    }

      if (empty($privacy)) {
        $errors['privacy'] = true;
      }

      //only do this if there weren't any errors
      if (count($errors) === 0) {
        //update sheet
        $query = "UPDATE `timeslot_sheets` SET name = ?, numslots=?, description = ?, privacy = ? WHERE ID=?";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$title, $numSlots, $description, $privacy, $sheetID]);

        //update slots information
        $query = "UPDATE `timeslot_slots` SET location=?, notes=? WHERE sheetID=?";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$location, $notes, $sheetID]);

        $tempNumSlots =$numSlots;
        //what if update? what if delete?
        for ($i = 0; $i < $numSlots; $i ++){
          if(isset($dateTime[$i])){
          $date[$i] = substr($dateTime[$i], 0, 10);
          $time[$i] = substr($dateTime[$i], 10);

          //if the slot just needs to get updated
          if(isset($slotIDs[$i])){
            //if the slot is being deleted
            if(isset($delete[$i])){
              $query = "DELETE FROM `timeslot_slots` WHERE ID=?";
              $stmt = $pdo->prepare($query);
              $stmt->execute([$slotIDs[$i]]);

              $tempNumSlots--;
              
            }
            else{
              $query = "UPDATE `timeslot_slots` SET date=?, time=? WHERE ID=?";
              $stmt = $pdo->prepare($query);
              $stmt->execute([$date[$i], $time[$i],$slotIDs[$i]]);
            }
          }
          //if it is a new slot
          else{
            $query = "insert into `timeslot_slots` (sheetID, date,time,location,notes) values (?,?,?,?,?)";
            $stmt = $pdo->prepare($query);
            $stmt->execute([$sheetID, $date[$i], $time[$i], $location, $notes]);
          }

        
        }

        
      }

        $query = "UPDATE `timeslot_sheets` SET numslots=? WHERE ID=?";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$tempNumSlots, $sheetID]);

        unset($_SESSION["sheetID"]);
        
        header("Location:sheetThanks.php");
        exit();
      }
      
  }

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Edit Sign-Up Sheet</title>
    <link rel ="stylesheet" href = "styles/master.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.2.3/flatpickr.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.2.3/themes/dark.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.2.3/flatpickr.js"></script>
    <script defer src="scripts/editSheet.js"></script>
    <script src="https://kit.fontawesome.com/accfddd944.js" crossorigin="anonymous"></script>
  </head>
  <body id = "signUpSheet">
      <?php include 'includes/navbar.php';?>
      <header>
        <h1><i class="far fa-edit"></i> Edit Sign-Up Sheet</h1>
      </header>
      <main>
        <nav>
          <ul>
            <li><a href="mySignups.php">Back</a></li>
          </ul>
        </nav>
        <section>
          <h2>Sign-Up Sheet Details</h2>
          <form id="sheet" action="<?=htmlentities($_SERVER['PHP_SELF'])?>" method="POST" autocomplete="off">
            <div>
              <label for="title">Title</label>
              <input id="title" name="title" type="text" placeholder="Project Check-In #1" value="<?=$title?>"/>
              <span class="error <?=!isset($errors['title']) ? 'hidden' : "";?>">Please enter a title</span>
            </div>
            <div>
              <label for="creator">Creator</label>
              <input id="creator" name="creator" type="text" value="<?=$creator?>" disabled/>
              <span class="error <?=!isset($errors['creator']) ? 'hidden' : "";?>">Please enter your username</span>
            </div>
            <div>
              <label for="description">Description</label>
              <textarea name="description" id="description" cols="30" rows="10"><?=$description?></textarea>
              <span class="error <?=!isset($errors['description']) ? 'hidden' : "";?>">Please enter a description</span>
            </div>
            <div>
              <label for="location">Loaction</label>
              <input id="location" name="location" type="text" placeholder="Remote via Zoom" value="<?=$location?>"/>
              <span class="error <?=!isset($errors['location']) ? 'hidden' : "";?>">Please enter a location</span>
            </div>
            <fieldset>
              <legend>Privacy</legend>
              <div>
                <input id="public" name="status" type="radio" value="public" <?=$privacy == "public" ? 'checked' : ''?>/>
                <label for="public">Public</label>
              </div>
              <div>
                <input id="private" name="status" type="radio" value="private" <?=$privacy == "private" ? 'checked' : ''?>/>
                <label for="private">Private</label>
              </div>
              <span class="error <?=!isset($errors['privacy']) ? 'hidden' : "";?>">Please select a privacy setting</span>
            </fieldset>
            <div>
              <label for="notes">Notes</label>
              <textarea name="notes" id="notes" cols="30" rows="10"><?=$notes?></textarea>
            </div>
              <div>
                <label for="numSlots">Number of Time Slots</label>
                <input id="numSlots" name="numSlots" type="number" value="<?=$numSlots?>"/>
                <span class="error <?=!isset($errors['numSlots']) ? 'hidden' : "";?>">Please enter the number of time slots in this sheet</span>
              </div>
              <div class = "table"> 
              <table id="generateSlots">
                <thead>
                  <tr>
                    <th>What</th>
                    <th>When</th>
                    <th>Book</th>
                  </tr>
                </thead>
                <tbody>
                  <?php for ($i = 1; $i <= $numSlots; $i++): ?>
                    <?php

                    $slot = $slots[$i-1];

                    $dateTime = $slot['date'] . ' ' . $slot['time'];
                    ?>
                    <tr class = 'row'>
                      <td><?=$title?></td>
                      <td>
                        <div>
                          <label for="basicDate">Date and Time: </label>
                          <input type="text" name="dateTime<?=$i-1?>" id="basicDate" placeholder="Please select Date Time" <?php if($slot["userID"] != null) echo'disabled'?> data-input value="<?= ($dateTime == null) ? null : $dateTime?>"  >
                          <span class="error <?=!isset($errors['dateTime']) ? 'hidden' : "";?>">Please enter a date</span>
                        </div>
                      </td>
                      <?php if ($slot["userID"] == null): ?>
                        <td>Delete Slot: <input type="checkbox" name="delete<?=$i-1?>" value="Delete"></td>
                      <?php else: ?>
                        <td>
                          <?php
                          $query = "select * from `timeslot_users` WHERE ID= ?";
                          $stmt = $pdo->prepare($query);
                          $stmt->execute([$slot["userID"]]);
                          $slotParticipant = $stmt->fetch();

                          echo "$slotParticipant[name]";
                          ?>
                        </td>
                      <?php endif ?>
                    </tr>
                  <?php endfor ?>
                </tbody>
              </table>
            </div>
            <div>
              <button type="button" name="addSlot" id="addSlot">Add Another Time Slot</button>
            </div>
            <div>
              <button type="submit" name="submit">Change Sheet</button>
            </div>
          </form>
        </section>
      </main>
  </body>
</html>
```

### Testing

#### Firefox:

Lets edit this sheet

![editsheet1](C:\Users\billj\Documents\TrentU\Cois-3420\Loki\3420\finalProjSnips\editsheet1.JPG)

lets add a slot in, and edit the date for our other one

![editsheet2](C:\Users\billj\Documents\TrentU\Cois-3420\Loki\3420\finalProjSnips\editsheet2.JPG)

![editsheet3](C:\Users\billj\Documents\TrentU\Cois-3420\Loki\3420\finalProjSnips\editsheet3.JPG)

On Second thought, we dont need that one on the 26th

![editsheet4](C:\Users\billj\Documents\TrentU\Cois-3420\Loki\3420\finalProjSnips\editsheet4.JPG)

Viewing after

![editsheet5](C:\Users\billj\Documents\TrentU\Cois-3420\Loki\3420\finalProjSnips\editsheet5.JPG)

#### Chrome:

![image-20210804113606117](C:\Users\billj\Documents\TrentU\Cois-3420\Loki\3420\finalProjSnips\ImagesMD\image-20210804113606117.png)

![image-20210804113622928](C:\Users\billj\Documents\TrentU\Cois-3420\Loki\3420\finalProjSnips\ImagesMD\image-20210804113622928.png)

![image-20210804113637503](C:\Users\billj\Documents\TrentU\Cois-3420\Loki\3420\finalProjSnips\ImagesMD\image-20210804113637503.png)

#### HTML Validator:

Fixing the errors shown in the validator prevent the functionality of the plug-in

![image-20210804113847570](C:\Users\billj\Documents\TrentU\Cois-3420\Loki\3420\finalProjSnips\ImagesMD\image-20210804113847570.png)

## fileupload.php

```php
<?php
function fileUpload(int $userID){
    try{
        if(is_uploaded_file($_FILES['imgupload']['tmp_name'])){

            //take the users original filename
            $filename = $_FILES['imgupload']['tmp_name'];


            //code copied from PHP web techniques on https://jamiemitchell.dev/3420/summer2021/notes/php_webtechniques.html
            if (exif_imagetype( $_FILES['imgupload']['tmp_name']) != IMAGETYPE_GIF
            and exif_imagetype( $_FILES['imgupload']['tmp_name']) != IMAGETYPE_JPEG
            and exif_imagetype( $_FILES['imgupload']['tmp_name']) != IMAGETYPE_PNG){

                throw new RuntimeException('Invalid file format.');
            }


            //path to the images folder
            //$path = WEBROOT . "/www_data/";
            $path = "../www_data/";

            //split string on period
            $extension = explode('.', $_FILES['imgupload']['name']);
            //just take the extension
            $extension = $extension[1];
            
            //get the username of the students server in order to use the right mySQL DB name
            $config = parse_ini_file(DOCROOT . "pwd/config.ini");
            $DBname = $config['username'];
            
            //get the next auto increment value
            if(!isset($pdo)){
                $pdo = connectDB();
            }
            $stmt = $pdo->query('SELECT AUTO_INCREMENT FROM information_schema.TABLES WHERE TABLE_SCHEMA = "' . $DBname . '" AND TABLE_NAME = "timeslot_images"');
            $nextincrement = $stmt->fetch();
            $nextincrement = $nextincrement['AUTO_INCREMENT'];

            //TODO create filename
            //name the file whichever increment it is + extension
            $fileDest = ($path . $nextincrement . '.' . $extension);

            if(!move_uploaded_file($_FILES['imgupload']['tmp_name'], $fileDest)){
                throw new RuntimeException('File failed to upload');
            }

            $query = "INSERT INTO `timeslot_images` (filepath,userID) VALUES (?,?)";
            $stmt = $pdo->prepare($query);
            $stmt->execute([$fileDest,$userID]);

        }
    }
    catch (RuntimeException $e) {
        //echo $e->getMessage();
        throw $e;
    }
}

?>
```

#### 

## login.php

```php+HTML
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
    <main id='loginbox'>
        <section>
            <div>
                <div>
                    <i class="fas fa-user"></i>
                </div>
                <div>
                    <form id="login" name="login"  method="post">
                        <div id='usernameField'>
                            <label for="username" class='usernamepass'>Username:</label>
                            <input id="username" name="username" type="text" placeholder="" required/>
                        </div>
                        <div id='passwordField'>
                            <label for="password" class='usernamepass'>Password:</label>
                            <input id="password" name="password" type="password" placeholder="" required/>
                        </div>
                        <div>
                            <a href="changePassword.php">Forgot Password?</a>
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
```

### Testing

#### Firefox:

![loginFirefox1](C:\Users\billj\Documents\TrentU\Cois-3420\Loki\3420\finalProjSnips\loginFirefox1.JPG)



![](C:\Users\billj\Documents\TrentU\Cois-3420\Loki\3420\finalProjSnips\loginFirefox2.JPG)



This below is a correct login with remember me checked to trigger setting a cookie

![loginFirefox3](C:\Users\billj\Documents\TrentU\Cois-3420\Loki\3420\finalProjSnips\loginFirefox3.JPG)





This is the cookie set

![loginFirefox4](C:\Users\billj\Documents\TrentU\Cois-3420\Loki\3420\finalProjSnips\loginFirefox4.JPG)

#### Chrome:

![loginChrome1](C:\Users\billj\Documents\TrentU\Cois-3420\Loki\3420\finalProjSnips\loginChrome1.JPG)

![loginChrome2](C:\Users\billj\Documents\TrentU\Cois-3420\Loki\3420\finalProjSnips\loginChrome2.JPG)



#### HTML Validator:

Lack of heading after section was on purpose

![loginValid](C:\Users\billj\Documents\TrentU\Cois-3420\Loki\3420\finalProjSnips\loginValid.JPG)

## mySignups.php

```php+HTML
<?php
  session_start();
  if(!isset($_SESSION['username'])){
    header("Location:login.php");
    exit();
  }
  $creator = $_SESSION['username'];
  include 'includes/library.php';
  $pdo = connectDB();
  $query = "select * from timeslot_users WHERE username = ?";
  $stmt = $pdo->prepare($query);
  $stmt->execute([$creator]);
  $host = $stmt->fetch();

  $query = "select * from timeslot_sheets WHERE host = ?";
  $stmt = $pdo->prepare($query);
  $stmt->execute([$host["ID"]]);
  $sheets = $stmt->fetchAll();
  //var_dump($sheets);
  $query = "select * from timeslot_slots WHERE userID = ?";
  $stmt = $pdo->prepare($query);
  $stmt->execute([$host["ID"]]);
  $mySlots = $stmt->fetchAll();
  //var_dump($mySlots);

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>User Home</title>
    <link rel ="stylesheet" href = "styles/master.css"/>
    <script defer src="scripts/mySignUps.js"></script>
    <script src="https://kit.fontawesome.com/accfddd944.js" crossorigin="anonymous"></script>
  </head>
  <body id = "mySignUps">
      <?php include 'includes/navbar.php';?>
      <header>
          <img src="images/checklist.png" alt="pencil on a clipboard" />
          <h1>My Sign Ups</h1>
        </header>
        <main>
          <section class = "Sign-upSheets">
            <section class = "h2">
              <h2>My Sign-up Sheets </h2>
              <a href="signUpSheet.php"><abbr title = "Create Sign-up Sheet"><i class="fas fa-plus-square"></i></abbr></a>
            </section>
            <?php foreach ($sheets as $sheet): ?>
              <?php 
              $query = "select * from timeslot_slots WHERE sheetID = ?";
              $stmt = $pdo->prepare($query);
              $stmt->execute([$sheet["ID"]]);
              $slots = $stmt->fetchAll();
              ?>
              <div>
                  <div>
                      <h3><?=$sheet["name"]?></h3>
                      <ul tabindex="0">
                        <li><?="<a href='viewSheet.php?id=".$sheet["ID"]."'><abbr title = 'View Sign-up Sheet'><i class='fab fa-readme'></i></abbr></a>"?></li>
                        <li><?="<a href='editSheet.php?id=".$sheet["ID"]."'><abbr title = 'Edit Sign-up Sheet'><i class='far fa-edit'></i></abbr></a>"?></li>
                        <li><?="<a href='copySheet.php?id=".$sheet["ID"]."'><abbr title = 'Copy Sign-up Sheet'><i class='far fa-copy'></i></abbr></a>"?></li>
                        <li><?="<a href='deleteSheet.php?id=".$sheet["ID"]."'><abbr title = 'Delete Sign-up Sheet'><i class='fas fa-trash-alt'></i></abbr></a>"?></li>
                      </ul>
                  </div>
                  <p><strong>Description: </strong><?=$sheet["description"]?></p>
                  <p><strong>Number of Slots: </strong><?=$sheet["numslots"]?></p>
                  <p><strong>Number of People Signed-Up: </strong><?=$sheet["numslotsfilled"]?></p>
              </div>
            <?php endforeach ?> 
          </section>
          <section class = "Slots">
            <section>
              <h2>My Time Slots</h2>
            </section>
            <?php foreach ($mySlots as $slot): ?>
              <div>
                  <div>
                    <h3>
                      <?php 
                        $query = "select * from `timeslot_sheets` WHERE ID = ?";
                        $stmt = $pdo->prepare($query);
                        $stmt->execute([$slot["sheetID"]]);
                        $slotName = $stmt->fetchAll();

                        echo $slotName[0]['name'];
                      ?>
                    </h3>
                    <ul tabindex="0">
                      <li><?="<a href='viewTimeSlot.php?id=".$slot["ID"]."'><abbr title = 'View Time Slot'><i class='fab fa-readme'></i></abbr></a>"?></li>
                      <li><?="<a href='deleteTimeSlot.php?id=".$slot["ID"]."'><abbr title = 'Delete Time Slot'><i class='fas fa-trash-alt'></i></abbr></a>"?></li>
                    </ul>
                  </div>
                  <p><strong>Date: </strong><?=$slot["date"]?></p>
                  <p><strong>Time: </strong><?=$slot["time"]?></p>
                  <p><strong>Location: </strong><?=$slot["location"]?></p>
              </div>
            <?php endforeach ?>
          </section>
      </main>
  </body>
</html>
```

### Testing

#### Firefox:

![img](https://lh4.googleusercontent.com/MQa04UTDEyzvefvwa6XfsE32unGHrGFfeqq-yRa05QreMxE4fa6agf_129naKWb_nKfnY6clkJVg3vKnicoI-YnpRw_9iXr1OExmVEqVTcvBmJCobujNo1i481a9nTiwGVrKQMpl)

![img](https://lh5.googleusercontent.com/uYSrHXEkRtamxpqOrJjJpXNkQb3F9Z5wehc93J2pRUGW5zgl_7XjGCwVLWry92OUFQgcjIHS-kDB6LkCJYe20fOTEFXDu7M5mJ1rdZd8Rg2Kkb3aUDC9K2FTYCn4AS1rkqSS04ge)

![img](https://lh3.googleusercontent.com/3AIXkQyB6rnIxvR0lmBdA5zjeYw-aXbY6rgKeapWlnE0tKa60b4jYiM05a-b9eEHMuLX8JsLqxumbp61CqTTetqcNVy85MWKYjL1o2BVwcvqDsMLnlC-oev3KPrpgpNFmOvpk1de)

![img](https://lh5.googleusercontent.com/sxJhLbdOFk0rqC6V_ehRcdt-7fBIaF7U4Olz0bihiMaPxITa5TC_gl3CCppxe5_tx9sqYISBecuIbBNEUvzHEbFiM0d-4SyA7rPXlOo5hbvPq1vpXYgWJTMrkJouI8Tcp87tKfa4)

#### Chrome:

![image-20210804123943480](C:\Users\billj\Documents\TrentU\Cois-3420\Loki\3420\finalProjSnips\ImagesMD\image-20210804123943480.png)

#### HTML Validator:

Lack of heading after section was on purpose

![image-20210804124130425](C:\Users\billj\Documents\TrentU\Cois-3420\Loki\3420\finalProjSnips\ImagesMD\image-20210804124130425.png)

## navbar.php

```php+HTML
<?php

//check if user is logged in, present with proper text
$login = 'Login';
$loginlink = 'login.php';
$homelink = 'login.php';
$profilelink = 'login.php';

//check if session started, stolen from https://stackoverflow.com/a/18542272
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

//if the user had remember me on, store their username in the session
if(isset($_COOKIE['logincookie'])){
    $_SESSION['username']=$_COOKIE['logincookie'];
}

if (isset($_SESSION['username']))
{
    $login = 'Logout';
    $loginlink = 'thanks.php';   //takes you to the thanks page
    $homelink = 'mySignups.php';
    $profilelink = 'profile.php';
}

?>
<nav id='navbar'>
    <div>
        <div>
            <i class="far fa-clock"></i>
            <h2 class='title'>Bill and Jamie's Time Slot Manager</h2>
        </div>
        <div>
            <ul>
                <li><a href="<?php echo $homelink ?>">Home</a></li>
                <li><a href="<?php echo $profilelink ?>">View Profile</a></li>
                <li><a href="<?php echo $loginlink ?>"><?php echo $login ?></a></li>
                <li><a href="searchForSheet.php">Find Sign-up Sheet</a></li>
            </ul>
        </div>
    </div>
</nav>
```

#### 

## profile.php

```php+HTML
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
```

### Testing

#### Firefox:

![img](https://lh3.googleusercontent.com/QQUuymYrlGdabV18qbeR46j0PVnDo0_C7dm7iXDQv070KjKKORIOBhnnfjhfJQqCjP3pONHFEjjj3DSp1qH2me055GbF9WqZSxdwahkdQErcbCCi2ZjzJu9UQrDZWOfB5dibnPRR)

#### Chrome:

![image-20210804124334580](C:\Users\billj\Documents\TrentU\Cois-3420\Loki\3420\finalProjSnips\ImagesMD\image-20210804124334580.png)

#### HTML Validator:

The validator does not like my last name

![image-20210804124442482](C:\Users\billj\Documents\TrentU\Cois-3420\Loki\3420\finalProjSnips\ImagesMD\image-20210804124442482.png)

## resetPassword.php

```php+HTML
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
```

### Testing

See resetPassword.php testing. Its an identical page except for some minor HTML changes



#### HTML Validator:

![changePasswordHTMl](C:\Users\billj\Documents\TrentU\Cois-3420\Loki\3420\finalProjSnips\changePasswordHTMl.JPG)

## resetPasswordEmail.php

```php
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

```

#### 

## searchForSheet.php

```php+HTML
<?php
  $search = $_GET['search'] ?? null;
  if (isset($_GET['search'])){
    include 'includes/library.php';
    $pdo = connectDB();
    $query = "SELECT * FROM `timeslot_users` WHERE username like ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$search]);
    $host = $stmt->fetch();
    $query = "select * from `timeslot_sheets` WHERE name like ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$search]);
    $titleResults = $stmt->fetchAll();
    $query = "select * from `timeslot_sheets` WHERE description like ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$search]);
    $descriptionResults = $stmt->fetchAll();
    if ($host != false){
      $query = "select * from `timeslot_sheets` WHERE host = ?";
      $stmt = $pdo->prepare($query);
      $stmt->execute([$host["ID"]]);
      $hostResults = $stmt->fetchAll();
    }
    else{
      $hostResults = false;
    }
  }
  
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>View Sign-Up Sheet</title>
    <link rel ="stylesheet" href = "styles/master.css"/>
    <script src="https://kit.fontawesome.com/accfddd944.js" crossorigin="anonymous"></script>
  </head>
  <body id = "signUpForSlot">
      <?php include 'includes/navbar.php';?>
      <header>
        <h1><i class="fas fa-search"></i> Find Sign-Up Sheet</h1>
      </header>
      <main>
        <nav>
          <ul>
            <li><a href="mySignups.php">Back</a></li>
          </ul>
        </nav>
        <section id = "FindSheet">
        <form id="searchbar" name="search"  action="<?=htmlentities($_SERVER['PHP_SELF'])?>" method="GET">
            <label><i aria-hidden="true" class="fas fa-search"></i></label>
            <input type='text' name="search" id="search" placeholder="Search for the Public Sign-Up Sheets By Creator username, Title, Description" value="<?=$search?>">
            <button id="submit" type="submit">Search</button>
          </form>
          <?php if(isset($_GET['search'])): ?>
            <?php if ($hostResults != false): ?>
            <?php foreach ($hostResults as $sheet): ?>
            <section>
              <div>
                <div>
                  <h2><?=$sheet["name"]?></h2>
                  <ul>
                  <li><?="<a href='signUpForSlot.php?id=".$sheet["ID"]."'><abbr title = 'Book Time Slot'><i class='fab fa-readme'></i></abbr></a>"?></li>
                  </ul>
                </div>
                <p><strong>Description: </strong><?=$sheet["description"]?></p>
                <p><strong>Number of Slots: </strong><?=$sheet["numslots"]?></p>
                <p><strong>Number of People Signed-Up: </strong><?=$sheet["numslotsfilled"]?></p>
              </div>
            </section>
            <?php endforeach ?> 
          <?php endif ?>
          <?php if ($titleResults != false): ?>
            <?php foreach ($titleResults as $sheet): ?>
            <section>
              <div>
                <div>
                  <h2><?=$sheet["name"]?></h2>
                  <ul>
                    <li><?="<a href='signUpForSlot.php?id=".$sheet["ID"]."'><abbr title = 'Book Time Slot'><i class='fab fa-readme'></i></abbr></a>"?></li>
                  </ul>
                </div>
                <p><strong>Description: </strong><?=$sheet["description"]?></p>
                <p><strong>Number of Slots: </strong><?=$sheet["numslots"]?></p>
                <p><strong>Number of People Signed-Up: </strong><?=$sheet["numslotsfilled"]?></p>
              </div>
            </section>
            <?php endforeach ?> 
          <?php endif ?>
          <?php if ($descriptionResults != false): ?>
            <?php foreach ($descriptionResults as $sheet): ?>
            <section>
              <div>
                <div>
                  <h2><?=$sheet["name"]?></h2>
                  <ul>
                    <li><?="<a href='signUpForSlot.php?id=".$sheet["ID"]."'><abbr title = 'Book Time Slot'><i class='fab fa-readme'></i></abbr></a>"?></li>
                  </ul>
                </div>
                <p><strong>Description: </strong><?=$sheet["description"]?></p>
                <p><strong>Number of Slots: </strong><?=$sheet["numslots"]?></p>
                <p><strong>Number of People Signed-Up: </strong><?=$sheet["numslotsfilled"]?></p>
              </div>
            </section>
            <?php endforeach ?> 
          <?php endif ?>
        <?php endif ?>
        </section>
      </main>
  </body>
</html>
```

### Testing

#### Firefox:

![img](https://lh5.googleusercontent.com/4KKkCjoNS_18sZcopEKwqELLJLhPKNT2m5Zqz2JNz-aGEHF_ZJMwPsmQlFqoRyCvGOdRZ6tgMuItAI4HoigSOtU91AK9UqmrCfr1XYrsnPuPPI3hs9nEULD6CTRXiHOdFOMUPpd_)

![img](https://lh4.googleusercontent.com/HgDCs5R1CVON8EcwtmpBfxmDe9JaPwAdYulll-cYvAGezihc-d8cTtaGtaztXDZG6v-TkrdxoPgp_poc5g0dzOa2IlPogCFDlMATZ4gxmHhHCrbcKsYMfZFCsxplRg327-yofYli)

#### Chrome:

![image-20210804124847249](C:\Users\billj\Documents\TrentU\Cois-3420\Loki\3420\finalProjSnips\ImagesMD\image-20210804124847249.png)

![image-20210804124943876](C:\Users\billj\Documents\TrentU\Cois-3420\Loki\3420\finalProjSnips\ImagesMD\image-20210804124943876.png)

#### HTML Validator:

Lack of heading after section was on purpose

![image-20210804125615765](C:\Users\billj\Documents\TrentU\Cois-3420\Loki\3420\finalProjSnips\ImagesMD\image-20210804125615765.png)

## sheetThanks.php

```html
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Done!</title>
    <link rel="stylesheet" href="styles/master.css" />
  </head>
  <body id="mySignUps">
      <?php include 'includes/navbar.php';?>
      <header>
        <h1><i class="fas fa-clipboard-check"></i> All Done!</h1>
      </header>
      <main>
        <nav>
          <ul>
            <li><a href="mySignups.php">Home</a></li>
          </ul>
        </nav>
        <section>
          <h3>Your Sign-Up Sheet has been Created!</h3>
        </section>
      </main>
  </body>
</html>

```

### Testing

#### Firefox:

![img](https://lh4.googleusercontent.com/pCXhSw3rUQrCO5P3bbPkA-S6M3WAozIIKZeMAMfOBoc-lzq2O5Xg_5jkfyriaOn6Hu2v_g7Hk2YGQ3Tt8ERHMlu__vK-_NIRUZR0T6gNp2A42_FPk0NZDGnw4Y6iMW8jrpRA3dqi)

#### Chrome:

![image-20210804125153941](C:\Users\billj\Documents\TrentU\Cois-3420\Loki\3420\finalProjSnips\ImagesMD\image-20210804125153941.png)

#### HTML Validator:

![image-20210804125221661](C:\Users\billj\Documents\TrentU\Cois-3420\Loki\3420\finalProjSnips\ImagesMD\image-20210804125221661.png)

## sidebar.php

```html
<nav id='sidebar'>
    <ul>
        <li><a href="index.php">Home</a></li>
        <li><a href="login.php">Already Have an Account</a></li>
    </ul>
</nav>
```



## signUpForSlot.php

```php+HTML
<?php 

  include 'includes/library.php';
  $pdo = connectDB();

  session_start();

  //if the user is logged in
  if (isset($_SESSION['username'])){
    $user = $_SESSION['username'];

    $query = "select * from timeslot_users WHERE username = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$user]);
    $userDetails = $stmt->fetch();
  }
  
  $book = "book";
 
  $query = "select * from timeslot_sheets WHERE ID = ?";
  $stmt = $pdo->prepare($query);
  $stmt->execute([$_GET["id"]]);
  $sheet = $stmt->fetch();

  $query = "select * from timeslot_slots WHERE sheetID = ?";
  $stmt = $pdo->prepare($query);
  $stmt->execute([$_GET["id"]]);
  $slots = $stmt->fetchAll();

  $query = "select location, notes from timeslot_slots WHERE sheetID = ?";
  $stmt = $pdo->prepare($query);
  $stmt->execute([$_GET["id"]]);
  $slotInfo = $stmt->fetch();

  $creatorID = $sheet["host"];
  $query = "select * from timeslot_users WHERE ID = ?";
  $stmt = $pdo->prepare($query);
  $stmt->execute([$creatorID]);
  $creatorDetails = $stmt->fetch();

  $creator = $creatorDetails["username"];
  $title = $sheet["name"];
  $description = $sheet["description"];
  $location = $slotInfo["location"];
  $privacy = $sheet["privacy"];
  $numSlots = $sheet["numslots"];
  $numSlotsFilled = $sheet["numslotsfilled"];
  $notes = $slotInfo["notes"];
  if ((isset($_GET["slotID"])) || (isset($_GET["action"]))){
    echo "slotID: ". $_GET["slotID"]. "";
    $query = "select * from timeslot_slots WHERE ID = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([(int)$_GET["slotID"]]);
    $slotDetails = $stmt->fetch();
    $action = $_GET["action"];
    if ($action == "book"){
        $query = "UPDATE timeslot_sheets SET numslotsfilled = numslotsfilled + 1 WHERE ID = ?";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$sheet["ID"]]);

        $query = "UPDATE timeslot_slots SET userID = ? WHERE ID = ?";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$userDetails["ID"], $slotDetails["ID"]]);
        header("Location:slotThanks.php");
        exit();
    }
  }
    
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Book a Time Slot</title>
    <link rel ="stylesheet" href = "styles/master.css"/>
    <script src="https://kit.fontawesome.com/accfddd944.js" crossorigin="anonymous"></script>
  </head>
  <body id = "signUpSheet">
      <?php include 'includes/navbar.php';?>
      <header>
        <h1><i class="fab fa-readme"></i> Book a Time Slot</h1>
      </header>
      <main>
        <nav>
          <ul>
            <li><a href="mySignups.php">Back</a></li>
          </ul>
        </nav>
        <section>
          <h2><?=$title?></h2>
          <p><i class="fas fa-user"></i><strong>Creator: </strong><?=$creator?></p>
          <p><i class="fas fa-info-circle"></i><strong>Description: </strong><?=$description?></p>
          <p><i class="fas fa-map-marker-alt"></i><strong>Location: </strong><?=$location?></p>
          <p><i class="fas fa-unlock-alt"></i><strong>Privacy: </strong><?=$privacy?></p>
          <p><i class="fas fa-sticky-note"></i><strong>Notes: </strong><?=$notes?></p>
          <div class = "table"> 
            <table>
              <thead>
                <tr>
                  <th>What</th>
                  <th>When</th>
                  <th>Who</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($slots as $slot): ?>
                  <tr>
                    <td><?=$title?></td>
                    <td><?=$slot["date"]?> @ <?=$slot["time"]?></td>
                    <?php if ($slot["userID"] == null): ?>
                      <?php if (isset($_SESSION['username'])): ?>
                        <td><div><?="<a href='signUpForSlot.php?id=".$sheet["ID"]."&slotID=".$slot["ID"]."&action=".$book."'>Book Time Slot</a>"?></div></td>
                      <?php else: ?>
                        <td><div><?="<a href='bookslotNonUser.php?slotID=".$slot["ID"]."&sheetID=".$sheet["ID"]."'>Book Time Slot</a>"?></div></td>
                      <?php endif ?>
                    <?php else: ?>
                      <td>
                        <?php
                          $query = "select * from `timeslot_users` WHERE ID= ?";
                          $stmt = $pdo->prepare($query);
                          $stmt->execute([$slot["userID"]]);
                          $slotParticipant = $stmt->fetch();

                          echo "$slotParticipant[name]";
                        ?>
                      </td>
                    <?php endif ?>
                  </tr>
                <?php endforeach ?>
              </tbody>
            </table>
          </div>
        </section>
      </main>
  </body>
</html>
```

### Testing

#### Firefox:

lets find a sheet from another user

![signupforslot](C:\Users\billj\Documents\TrentU\Cois-3420\Loki\3420\finalProjSnips\signupforslot.JPG)

lets sign up for the slot for the 11th of august

![signupforslot2](C:\Users\billj\Documents\TrentU\Cois-3420\Loki\3420\finalProjSnips\signupforslot2.JPG)

![signupforslot3](C:\Users\billj\Documents\TrentU\Cois-3420\Loki\3420\finalProjSnips\signupforslot3.JPG)

Note the user ID here and the account attached below

![signupforslot4](C:\Users\billj\Documents\TrentU\Cois-3420\Loki\3420\finalProjSnips\signupforslot4.JPG)



![signupforslot5](C:\Users\billj\Documents\TrentU\Cois-3420\Loki\3420\finalProjSnips\signupforslot5.JPG)

#### Chrome:

![image-20210804130605733](C:\Users\billj\Documents\TrentU\Cois-3420\Loki\3420\finalProjSnips\ImagesMD\image-20210804130605733.png)

#### HTML Validator:

![image-20210804130658401](C:\Users\billj\Documents\TrentU\Cois-3420\Loki\3420\finalProjSnips\ImagesMD\image-20210804130658401.png)

## signUpSheet.php

```php+HTML
<?php
  $title = $_POST['title'] ?? null;
  $description = $_POST['description'] ?? null;
  $location = $_POST['location'] ?? null;
  $privacy = $_POST['status'] ?? null;
  $numSlots = $_REQUEST['numSlots'] ?? null;
  $notes = $_POST['notes'] ?? null;
  $dateTime = $_POST['dateTime'] ?? null;
  $errors = array();

  session_start();
  
  if(!isset($_SESSION['username'])){
    header("Location:login.php");
    exit();
  }
  $creator = $_SESSION['username'];

  if (isset($_POST['submit'])){

    //sanitize all the textbox inputs
    $description = filter_var($description, FILTER_SANITIZE_STRING);
    $notes = filter_var($notes, FILTER_SANITIZE_STRING);
    //validate user has entered a title
    if (!isset($title) || strlen($title) === 0) {
      $errors['title'] = true;
    }

    //validate user has entered a title
    if (!isset($creator) || strlen($creator) === 0) {
      $errors['creator'] = true;
    }

    //validate user has entered a title
    if (!isset($description)) {
      $errors['description'] = true;
    }

    //validate user has entered a title
    if (!isset($location) || strlen($location) === 0) {
      $errors['location'] = true;
    }

    //make sure the chose a character
    if (empty($privacy)) {
      $errors['privacy'] = true;
    }

    //make sure they agreed to the terms
    if ($numSlots == "" || $numSlots == "0") {
      $errors['numSlots'] = true;
    }

      //only do this if there weren't any errors
      if (count($errors) === 0) {

        $_SESSION['title'] = $title;
        $_SESSION['description'] = $description;
        $_SESSION['location'] = $location;
        $_SESSION['privacy'] = $privacy;
        $_SESSION['numSlots'] = $numSlots;
        $_SESSION['notes'] = $notes;
        //send the user to the thankyou page.
        header("Location:generateSlots.php");
        exit();
      }
  }
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Create Sign-Up Sheet: Part 1</title>
    <link rel ="stylesheet" href = "styles/master.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.2.3/flatpickr.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.2.3/themes/dark.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.2.3/flatpickr.js"></script>
    <script defer src="scripts/createSheet.js"></script>
    <script src="https://kit.fontawesome.com/accfddd944.js" crossorigin="anonymous"></script>
  </head>
  <body id = "signUpSheet">
      <?php include 'includes/navbar.php';?>
      <header>
        <h1>Part 1: Sign-up Sheet Outline</h1>
      </header>
      <main>
        <nav id='sidebar'>
          <ul>
            <li><a href="mySignups.php">Back</a></li>
          </ul>
        </nav>
        <section>
          <h2>Sign-Up Sheet Details</h2>
          <form id="sheet" action="<?=htmlentities($_SERVER['PHP_SELF'])?>" method="POST" autocomplete="off">
            <div>
              <label for="title">Title</label>
              <input id="title" name="title" type="text" placeholder="Project Check-In #1" value="<?=$title?>"/>
              <span class="error <?=!isset($errors['title']) ? 'hidden' : "";?>">Please enter a title</span>
            </div>
            <div>
              <label for="creator">Creator</label>
              <input id="creator" name="creator" type="text" value="<?=$creator?>" disabled/>
              <span class="error <?=!isset($errors['creator']) ? 'hidden' : "";?>">Please enter your username</span>
            </div>
            <div>
              <label for="description">Description</label>
              <textarea name="description" id="description" cols="30" rows="10"><?=$description?></textarea>
              <span class="error <?=!isset($errors['description']) ? 'hidden' : "";?>">Please enter a description</span>
            </div>
            <div>
              <label for="location">Loaction</label>
              <input id="location" name="location" type="text" placeholder="Remote via Zoom" value="<?=$location?>"/>
              <span class="error <?=!isset($errors['location']) ? 'hidden' : "";?>">Please enter a location</span>
            </div>
            <fieldset>
              <legend>Privacy</legend>
              <div>
                <input id="public" name="status" type="radio" value="public" <?=$privacy == "public" ? 'checked' : ''?>/>
                <label for="public">Public</label>
              </div>
              <div>
                <input id="private" name="status" type="radio" value="private" <?=$privacy == "private" ? 'checked' : ''?>/>
                <label for="private">Private</label>
              </div>
              <span class="error <?=!isset($errors['privacy']) ? 'hidden' : "";?>">Please select a privacy setting</span>
            </fieldset>
            <div>
              <label for="notes">Notes</label>
              <textarea name="notes" id="notes" cols="30" rows="10"><?=$notes?></textarea>
            </div>
              <div>
                <label for="numSlots">Number of Time Slots</label>
                <input id="numSlots" name="numSlots" type="number" value="<?=$numSlots?>" readonly/>
                <span class="error <?=!isset($errors['numSlots']) ? 'hidden' : "";?>">Please enter the number of time slots in this sheet</span>
              </div>
              <table id="generateSlots">
                <thead>
                  <tr>
                    <th>What</th>
                    <th>When</th>
                    <th>Who</th>
                  </tr>
                </thead>
                <tbody>
                    <tr id="original">
                      <td><?=$title?></td>
                      <td>
                        <div>
                          <label for="basicDate">Date and Time: </label>
                          <input type="text" name="dateTime" id="basicDate" placeholder="Please select Date Time" data-input value="<?=$dateTime?>" disabled>
                          <span class="error <?=!isset($errors['dateTime']) ? 'hidden' : "";?>">Please enter a date</span>
                        </div>
                      </td>
                      <td><div><button id="submit" disabled>Book Time Slot</button></div></td>
                    </tr>
                </tbody>
              </table>
              <div>
                <button type="button" name="addSlot" id="addSlot">Add Another Time Slot</button>
              </div>
            <div>
              <button type="submit" name="submit">Proceed to Part 2: Time Slot Details</button>
            </div>
          </form>
        </section>
      </main>
  </body>
</html>

```

### Testing

#### Firefox:

![img](https://lh6.googleusercontent.com/wIMweH6BTkbW5ay_pfovtRdgSv15h4I3ylwryoc7n_oPIeSYzaB-SSl8IQp0obJTlLUHCACIj8S6Nq2bblAIdFIy_gIH87VTk66SaCpqVlG2ffYbPcc-1CYoVKydslUhpmC020wL)

![img](https://lh3.googleusercontent.com/itCWR2CWX6p0Q4wgNCjN44myubWFT1JpOjZu4BH08VrbRFaQDRHWk1TQk4Ys6Z892BHg0Rhp2r7nTVIACcMyr7U8kq-Z-Wv4gq_b9KZ1rTQk_0KxWTJ0jdEuHpa_2kqo5-aeT24r)

![image-20210804115622456](C:\Users\billj\Documents\TrentU\Cois-3420\Loki\3420\finalProjSnips\ImagesMD\image-20210804115622456.png)



![createSheet1](C:\Users\billj\Documents\TrentU\Cois-3420\Loki\3420\finalProjSnips\createSheet1.JPG)

![createSheet2](C:\Users\billj\Documents\TrentU\Cois-3420\Loki\3420\finalProjSnips\createSheet2.JPG)

See generateSlots.php for next steps in testing!

#### Chrome:

![image-20210804114552157](C:\Users\billj\Documents\TrentU\Cois-3420\Loki\3420\finalProjSnips\ImagesMD\image-20210804114552157.png)

![image-20210804115615378](C:\Users\billj\Documents\TrentU\Cois-3420\Loki\3420\finalProjSnips\ImagesMD\image-20210804115615378.png)

#### HTML Validator:

![image-20210804114708659](C:\Users\billj\Documents\TrentU\Cois-3420\Loki\3420\finalProjSnips\ImagesMD\image-20210804114708659.png)

## generateSlots.php

```php+HTML
<?php
  session_start();
  $creator = $_SESSION['username'];
  $title = $_POST['title'] ?? $title = $_SESSION['title'] ?? null;
  $description = $_POST['description'] ?? $description = $_SESSION['description'] ?? null;
  $location = $_POST['location'] ?? $location = $_SESSION['location'] ?? null;
  $privacy = $_POST['status'] ?? $privacy = $_SESSION['privacy'] ?? null;
  $numSlots = $_POST['numSlots'] ?? $numSlots = $_SESSION['numSlots'] ?? null;
  $notes = $_POST['notes'] ?? $notes = $_SESSION['notes'] ?? null;
  $dateTime = null; 
  $date = null; //bill july 4th
  $time = null; //bill july 4th

  $numSlots = intval($numSlots);  //bill july 4th
  
  $errors = array();
  
  include 'includes/library.php';
  $pdo = connectDB();
  $query = "select * from `timeslot_users` WHERE username = ?";
  $stmt = $pdo->prepare($query);
  $stmt->execute([$creator]);
  $host = $stmt->fetch();
  if (isset($_POST['submit'])){


    //bill july 4th
    for($i = 0; $i<$numSlots;$i++){
      $dateTime[$i] = $_POST["dateTime" .  $i ];
      //validate user has entered a date, by checking date[0]
      if ($dateTime[$i] == "") {
        $errors['dateTime'] = true;
      }
    }
    //sanitize all the textbox inputs
    $description = filter_var($description, FILTER_SANITIZE_STRING);
    $notes = filter_var($notes, FILTER_SANITIZE_STRING);
    //validate user has entered a title
    if (!isset($title) || strlen($title) === 0) {
      $errors['title'] = true;
    }

    //validate user has entered a title
    if (!isset($creator) || strlen($creator) === 0) {
      $errors['creator'] = true;
    }

    //validate user has entered a title
    if (!isset($description)) {
      $errors['description'] = true;
    }

    //validate user has entered a title
    if (!isset($location) || strlen($location) === 0) {
      $errors['location'] = true;
    }

    //make sure the chose a character
    if (empty($privacy)) {
      $errors['privacy'] = true;
    }

    //validate user has entered a number of slots
    if ($numSlots == "") {
      $errors['numSlots'] = true;
    }

      //only do this if there weren't any errors
      if (count($errors) === 0) {
        for($i = 0; $i<$numSlots;$i++){

          $date[$i] = substr($dateTime[$i], 0, 10);
          $time[$i] = substr($dateTime[$i], 10);
        }

        $query = "INSERT INTO timeslot_sheets (numslots, name, numslotsfilled,description,privacy,host,dateCreated) VALUES (?,?,?,?,?,?, NOW())";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$numSlots, $title, '0', $description, $privacy, $host["ID"]]);

        $query = "SELECT * FROM `timeslot_sheets` WHERE numslots = ? AND name = ? AND description = ? AND privacy = ?";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$numSlots, $title, $description, $privacy]);
        $sheetID = $stmt->fetch();
        for ($i = 0; $i < $numSlots; $i ++){
          $query = "insert into `timeslot_slots` (sheetID, date,time,location,notes) values (?,?,?,?,?)";
          $stmt = $pdo->prepare($query);
          $stmt->execute([$sheetID["ID"], $date[$i], $time[$i], $location, $notes]);
        }
        //send the user to the thankyou page.
        header("Location:sheetThanks.php");
        exit();
      }
  }
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Create Sign-Up Sheet: Part 2</title>
    <link rel ="stylesheet" href = "styles/master.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.2.3/flatpickr.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.2.3/themes/dark.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.2.3/flatpickr.js"></script>
    <script defer src="scripts/generateSlots.js"></script>
    <script src="https://kit.fontawesome.com/accfddd944.js" crossorigin="anonymous"></script>
  </head>
  <body id = "signUpSheet">
      <?php include 'includes/navbar.php';?>
      <header>
        <h1>Part 2: Time Slot Details</h1>
      </header>
      <main>
        <nav id='sidebar'>
          <ul>
            <li><a href="signUpSheet.php">Back</a></li>
          </ul>
        </nav>
        <section>
          <h2>Sign-Up Sheet Details</h2>
          <form id="sheet" action="<?=htmlentities($_SERVER['PHP_SELF'])?>" method="POST" autocomplete="off">
            <div>
              <label for="title">Title</label>
              <input id="title" name="title" type="text" placeholder="Project Check-In #1" value="<?=$title?>"/>
              <span class="error <?=!isset($errors['title']) ? 'hidden' : "";?>">Please enter a title</span>
            </div>
            <div>
              <label for="creator">Creator</label>
              <input id="creator" name="creator" type="text" value="<?=$creator?>"/>
              <span class="error <?=!isset($errors['creator']) ? 'hidden' : "";?>">Please enter your username</span>
            </div>
            <div>
              <label for="description">Description</label>
              <textarea name="description" id="description" cols="30" rows="10"><?=$description?></textarea>
              <span class="error <?=!isset($errors['description']) ? 'hidden' : "";?>">Please enter a description</span>
            </div>
            <div>
              <label for="location">Loaction</label>
              <input id="location" name="location" type="text" placeholder="Remote via Zoom" value="<?=$location?>"/>
              <span class="error <?=!isset($errors['location']) ? 'hidden' : "";?>">Please enter a location</span>
            </div>
            <fieldset>
              <legend>Privacy</legend>
              <div>
                <input id="public" name="status" type="radio" value="public" <?=$privacy == "public" ? 'checked' : ''?>/>
                <label for="public">Public</label>
              </div>
              <div>
                <input id="private" name="status" type="radio" value="private" <?=$privacy == "private" ? 'checked' : ''?>/>
                <label for="private">Private</label>
              </div>
              <span class="error <?=!isset($errors['privacy']) ? 'hidden' : "";?>">Please select a privacy setting</span>
            </fieldset>
            <div>
              <label for="notes">Notes</label>
              <textarea name="notes" id="notes" cols="30" rows="10"><?=$notes?></textarea>
            </div>
              <div>
                <label for="numSlots">Number of Time Slots</label>
                <input id="numSlots" name="numSlots" type="number" value="<?=$numSlots?>"/>
                <span class="error <?=!isset($errors['numSlots']) ? 'hidden' : "";?>">Please enter the number of time slots in this sheet</span>
              </div>
            <div class = "table"> 
              <table>
                <thead>
                  <tr>
                    <th>What</th>
                    <th>When</th>
                    <th>Book</th>
                  </tr>
                </thead>
                <tbody>
                  <?php for ($i = 1; $i <= $numSlots; $i++): ?>
                    <tr>
                      <td><?=$title?></td>
                      <td>
                        <div>
                          <label for="basicDate">Date and Time: </label>
                          <input type="text" name="dateTime<?=$i-1?>" id="basicDate" placeholder="Please select Date Time" data-input value="<?= ($dateTime == null) ? null : $dateTime[$i-1]?>">
                          <span class="error <?=!isset($errors['dateTime']) ? 'hidden' : "";?>">Please enter a date</span>
                        </div>
                      </td>
                      <td><div><button type="submit" disabled>Book Time Slot</button></div></td>
                    </tr>
                  <?php endfor ?>
                </tbody>
              </table>
            </div>
            <div>
              <button type="button" name="addSlot" id="addSlot">Add Another Time Slot</button>
            </div>
            <div>
              <button type="submit" name="submit">Create Sheet</button>
            </div>
          </form>     
        </section>
      </main>
  </body>
</html>
```

### Testing

#### Firefox:

![image-20210804115946538](C:\Users\billj\Documents\TrentU\Cois-3420\Loki\3420\finalProjSnips\ImagesMD\image-20210804115946538.png)

![image-20210804120003334](C:\Users\billj\Documents\TrentU\Cois-3420\Loki\3420\finalProjSnips\ImagesMD\image-20210804120003334.png)

![image-20210804120019576](C:\Users\billj\Documents\TrentU\Cois-3420\Loki\3420\finalProjSnips\ImagesMD\image-20210804120019576.png)

![image-20210804120045365](C:\Users\billj\Documents\TrentU\Cois-3420\Loki\3420\finalProjSnips\ImagesMD\image-20210804120045365.png)

![createSheet3](C:\Users\billj\Documents\TrentU\Cois-3420\Loki\3420\finalProjSnips\createSheet3.JPG)

After pressing create sheet, the sheet can be seen in mySignups.php

![createSheet4](C:\Users\billj\Documents\TrentU\Cois-3420\Loki\3420\finalProjSnips\createSheet4.JPG)

here are the entries in the database to prove it

![createSheet5](C:\Users\billj\Documents\TrentU\Cois-3420\Loki\3420\finalProjSnips\createSheet5.JPG)

![createSheet6](C:\Users\billj\Documents\TrentU\Cois-3420\Loki\3420\finalProjSnips\createSheet6.JPG)

#### Chrome:

![image-20210804114939816](C:\Users\billj\Documents\TrentU\Cois-3420\Loki\3420\finalProjSnips\ImagesMD\image-20210804114939816.png)

![image-20210804114956534](C:\Users\billj\Documents\TrentU\Cois-3420\Loki\3420\finalProjSnips\ImagesMD\image-20210804114956534.png)

![image-20210804115334099](C:\Users\billj\Documents\TrentU\Cois-3420\Loki\3420\finalProjSnips\ImagesMD\image-20210804115334099.png)

![image-20210804115416226](C:\Users\billj\Documents\TrentU\Cois-3420\Loki\3420\finalProjSnips\ImagesMD\image-20210804115416226.png)

#### HTML Validator:

Fixing the errors shown in the validator prevent the functionality of the plug-in

![image-20210804120129721](C:\Users\billj\Documents\TrentU\Cois-3420\Loki\3420\finalProjSnips\ImagesMD\image-20210804120129721.png)

## slotThanks.php

```html
<!DOCTYPE html>
<html lang="en">
  <head>
      <meta charset="utf-8" />
      <meta name="viewport" content="width=device-width, initial-scale=1.0" />
      <title>Done!</title>
      <link rel="stylesheet" href="styles/master.css" />
  </head>
  <body id="mySignUps">
      <?php include 'includes/navbar.php';?>
        <header>
          <h1><i class="fas fa-clipboard-check"></i> Time Slot Booked!</h1>
        </header>
        <main>
          <nav>
            <ul>
              <li><a href="mySignups.php">Home</a></li>
            </ul>
          </nav>
          <section>
            <h3>You are now registered for this time slot!</h3>
          </section>
      </main>
  </body>
</html>

```

### Testing

#### Firefox:

![img](https://lh6.googleusercontent.com/kAESg1BGvS2LUKcJTh-jNNabdZGNHYPQ6mx8mlrHivbMJh7rc2qpORGHzEWWsJcS6raYnY2KC_Nq7VhMwxssABtsCXGOc_VtGx9jL3bC4EenU2uoNNvES1YIug_P5w4sFXCfVR9j)

#### Chrome:

![image-20210804130915394](C:\Users\billj\Documents\TrentU\Cois-3420\Loki\3420\finalProjSnips\ImagesMD\image-20210804130915394.png)

#### HTML Validator:

![image-20210804130904638](C:\Users\billj\Documents\TrentU\Cois-3420\Loki\3420\finalProjSnips\ImagesMD\image-20210804130904638.png)

## thanks.php

```php+HTML
<?php
    session_start();
    session_destroy();  //destroy session
    setcookie("logincookie","",1);  //remove username from cookie and expire the cookie in 1 second
    header('Location:index.php');
    exit();

    //the html below wont actually show
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://kit.fontawesome.com/c2cee199ac.js" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="styles/master.css" />
  <title>Thanks</title>
</head>
<body>
  <section id="mainspace">
    <div class="container">
      <!--Citation for the image below: https://www.flexjobs.com/blog/post/essential-time-management-skills/-->
      <img src="images/MainpageImage.jpg" alt="computer with hour glass filled with blue sand" style = "width:100%" />
      <div class="content">
        <h1>Thanks!</h1>
        <p>Taking you back to the main page.</p>
      </div>
    </div>
    </div>
  </section>
</body>
</html>
```

#### 

## viewSheet.php

```php+HTML
<?php 
  session_start();
  include 'includes/library.php';
  $pdo = connectDB();
  
  $query = "select * from timeslot_sheets WHERE ID = ?";
  $stmt = $pdo->prepare($query);
  $stmt->execute([$_GET["id"]]);
  $sheet = $stmt->fetch();

  $query = "select * from timeslot_slots WHERE sheetID = ?";
  $stmt = $pdo->prepare($query);
  $stmt->execute([$_GET["id"]]);
  $slots = $stmt->fetchAll();

  $query = "select location, notes from timeslot_slots WHERE sheetID = ?";
  $stmt = $pdo->prepare($query);
  $stmt->execute([$_GET["id"]]);
  $slotInfo = $stmt->fetch();

  $creator = $_SESSION['username'];
  $title = $sheet["name"];
  $description = $sheet["description"];
  $location = $slotInfo["location"];
  $privacy = $sheet["privacy"];
  $numSlots = $sheet["numslots"];
  $numSlotsFilled = $sheet["numslotsfilled"];
  $notes = $slotInfo["notes"];
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>View Sign-Up Sheet</title>
    <link rel ="stylesheet" href = "styles/master.css"/>
    <script src="https://kit.fontawesome.com/accfddd944.js" crossorigin="anonymous"></script>
  </head>
  <body id = "signUpSheet">
      <?php include 'includes/navbar.php';?>
      <header>
        <h1><i class="fab fa-readme"></i> View Sign-Up Sheet</h1>
      </header>
      <main>
        <nav>
          <ul>
            <li><a href="mySignups.php">Back</a></li>
          </ul>
        </nav>
        <section>
        <h2><?=$title?></h2>
          <p><i class="fas fa-user"></i><strong>Creator: </strong><?=$creator?></p>
          <p><i class="fas fa-info-circle"></i><strong>Description: </strong><?=$description?></p>
          <p><i class="fas fa-map-marker-alt"></i><strong>Location: </strong><?=$location?></p>
          <p><i class="fas fa-unlock-alt"></i><strong>Privacy: </strong><?=$privacy?></p>
          <p><i class="fas fa-sticky-note"></i><strong>Notes: </strong><?=$notes?></p>
          <div class = "table"> 
            <table>
              <thead>
                <tr>
                  <th>What</th>
                  <th>When</th>
                  <th>Who</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($slots as $slot): ?>
                  <tr>
                    <td><?=$title?></td>
                    <td><?=$slot["date"]?> @ <?=$slot["time"]?></td>
                    <?php if ($slot["userID"] == null): ?>
                    <td><div><button type='submit' name='bookslot' disabled>Book Time Slot</button></div></td>
                    <?php else: ?>
                      <td><div>
                        <?php
                          $query = "select * from `timeslot_users` WHERE ID= ?";
                          $stmt = $pdo->prepare($query);
                          $stmt->execute([$slot["userID"]]);
                          $slotParticipant = $stmt->fetch();

                          echo "$slotParticipant[name]";
                        ?>
                      </div></td>
                    <?php endif ?>
                  </tr>
                <?php endforeach ?>
              </tbody>
            </table>
          </div>
        </section>
      </main>
  </body>
</html>

```

### Testing

#### Firefox:

![img](https://lh3.googleusercontent.com/cpgUqhU9UQT5ZHM1ptahCAz2QY0oA6YNizP_pgVl7EeuOxEebZn1frO2CJAwWD-nA2MWXp05A6G2D_xVJIUurhQwds4tkxFrNfnAsIPkI2_lPS9XzjJb49vdX_1Vy_hwBalG6Yi6)

![img](https://lh5.googleusercontent.com/HN6O7zNe-nxTBeOQp6I-1_X01jMJWATDNm6l2T9fWI3WndBkGNcL9OSVpIwPn5Cm_34Gm-4pgEKbL_GEgeCpkKRVk81pqhyUslh8_5RRbZldvWIi5Ubwabjvkvl1wzEajIBnDivg)

#### Chrome:

![image-20210804131612032](C:\Users\billj\Documents\TrentU\Cois-3420\Loki\3420\finalProjSnips\ImagesMD\image-20210804131612032.png)

![image-20210804131625677](C:\Users\billj\Documents\TrentU\Cois-3420\Loki\3420\finalProjSnips\ImagesMD\image-20210804131625677.png)

#### HTML Validator:

![image-20210804131557291](C:\Users\billj\Documents\TrentU\Cois-3420\Loki\3420\finalProjSnips\ImagesMD\image-20210804131557291.png)

## viewTimeSlot.php

```php+HTML
<?php
  include 'includes/library.php';
  $pdo = connectDB();

  $query = "select * from timeslot_slots WHERE ID = ?";
  $stmt = $pdo->prepare($query);
  $stmt->execute([$_GET["id"]]);
  $slot = $stmt->fetch();

  $query = "select * from timeslot_sheets WHERE ID = ?";
  $stmt = $pdo->prepare($query);
  $stmt->execute([$slot["sheetID"]]);
  $sheet = $stmt->fetch();

  $query = "SELECT * FROM `timeslot_users` WHERE ID = ?";
  $stmt = $pdo->prepare($query);
  $stmt->execute([$sheet["host"]]);
  $host = $stmt->fetch();

  $query = "SELECT * FROM `timeslot_users` WHERE ID = ?";
  $stmt = $pdo->prepare($query);
  $stmt->execute([$slot["userID"]]);
  $user = $stmt->fetch();

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>View Time Slot</title>
    <link rel ="stylesheet" href = "styles/master.css"/>
    <script defer src="scripts/deleteSlot.js"></script>
    <script src="https://kit.fontawesome.com/accfddd944.js" crossorigin="anonymous"></script>
  </head>
  <body id = "timeSlot">
      <?php include 'includes/navbar.php';?>
      <header>
        <h1><i class="fab fa-readme"></i> View Time Slot</h1>
      </header>
      <main>
        <nav>
          <ul>
            <li><a href="mySignups.php">Back</a></li>
          </ul>
        </nav>
        <section>
        <div>
            <h2><?=$sheet["name"]?></h2>
            <ul tabindex="0">
              <li><?="<a href='deleteTimeSlot.php?id=".$slot["ID"]."'><abbr title = 'Delete Time Slot'><i class='fas fa-trash-alt'></i></abbr></a>"?></li>
            </ul>
          </div>
          <p><i class="fas fa-info-circle"></i><strong>Description: </strong><?=$sheet["description"]?></p>
          <p><i class="fas fa-user-circle"></i><strong>Host: </strong><?=$host["username"]?></p>
          <p><i class="fas fa-user-circle"></i><strong>Participants: </strong><?=$user["name"]?></p>
          <p><i class="fas fa-clock"></i><strong>When: </strong><?=$slot["date"]?> @ <?=$slot["time"]?></p>
          <p><i class="fas fa-map-marked-alt"></i><strong>Where: </strong><?=$slot["location"]?></p>
          <p><i class="fas fa-sticky-note"></i><strong>Notes: </strong><?=$slot["notes"]?></p>
        </section>
      </main>
  </body>
</html>
```

### Testing

#### Firefox:

![image-20210804131900406](C:\Users\billj\Documents\TrentU\Cois-3420\Loki\3420\finalProjSnips\ImagesMD\image-20210804131900406.png)



#### Chrome:

![image-20210804131849623](C:\Users\billj\Documents\TrentU\Cois-3420\Loki\3420\finalProjSnips\ImagesMD\image-20210804131849623.png)

#### HTML Validator:

![image-20210804131838725](C:\Users\billj\Documents\TrentU\Cois-3420\Loki\3420\finalProjSnips\ImagesMD\image-20210804131838725.png)

## master.css

```css
@import 'reset.css';

/*fredicka the great*/
@import url('https://fonts.googleapis.com/css2?family=Fredericka+the+Great&display=swap');

/********A set of general styles that should apply to the entire site*/
h1 {
  font-size: 4em;
  font-weight: 700;
  padding: 0.5em;
  font-family: 'Fredericka the Great', cursive;
}

h2 {
  font-size: 1.5em;
  font-weight: 700;
  font-family: 'Fredericka the Great', cursive;
}

h3 {
  font-size: 1rem;
  padding: 0.5em;
}

em {
  font-style: italic;
}

strong {
  font-weight: 600;
}

abbr {
  display: inline;
}

/*links in nav*/
nav a {
  font-family: 'Fredericka the Great', cursive;
}

/*change visibility of accessibility text*/
.sr-only {
  visibility: hidden;
}

/*colour scheme variables*/
:root {
  --white: #dce1de;
  --black: #1f2421;
  --blue: #216869;
  --green: #49a078;
  --light-green: #9cc5a1;
  --back: #216869; /*This got deleted*/
  --borders: #1f2421; /*This got deleted*/
}

header h1 {
  color: black;
}

header {
  /*Citation for the image below: https://www.flexjobs.com/blog/post/essential-time-management-skills/*/
  background-image: url('../images/MyStuffView.jpg');
  height: 20em;
  display: flex;
  justify-content: center;
  align-items: center;
  margin-bottom: 1em;
}

main {
  display: flex;
  justify-content: space-evenly;
}

main section {
  display: flex;
  border: 0.2em solid var(--black);
  margin: 0.5rem;
  background-color: var(--white);
  color: var(--black);
  flex-direction: column;
  justify-content: space-evenly;
  justify-content: stretch;
  flex: 2 1 auto;
}

section > section {
  display: flex;
  justify-content: space-between;
  flex-direction: row;
  margin: 0.5rem;
  flex: 2 1 auto;
  border: none;
}

ul {
  display: flex;
  flex-direction: row;
}

li {
  margin-right: 0.3rem;
  font-size: 1.2em;
}

a {
  color: var(--black);
}

p {
  margin: 0.5em;
}

i {
  margin-right: 0.3em;
}

form > div {
  display: flex;
  flex-direction: column;
  padding: 1em;
}

input, select {
  flex: 2 1 auto;
  padding: 1em;
  justify-content: space-evenly;
  margin-top: 0.5em;
}

.flatpickr-input form-control input active {
  border: 2px solid whitesmoke;
  border-radius: 20px;
  padding: 12px 10px;
  text-align: center;
  width: 250px;
}

fieldset {
  display: flex;
  flex-direction: row;
  justify-content: space-evenly;
  width: 30em;
  padding: 1.5em;
  margin: 0.5em;
}
main nav a, main nav a:visited, main nav a:active, button {
  border-radius: 0.5em; /*rounded corners*/
  background-color: white;
  border: 0.3em solid var(--borders);
  color: black;
  text-align: center;
  text-decoration: none; /*remove underline*/
}

/*swap colours on hover*/
main nav a:hover, button:hover {
  background-color: var(--green);
  border: 0.3em solid var(--borders);
  color: var(--white);
  border-radius: 0.5em; /*rounded corners*/
}

main nav ul {
  flex-direction: column;
}

button {
  font-size: 1.2em;
}

button, main nav a {
  display: flex;
  padding: 1em;
  margin-bottom: 1em;
  align-items: flex-start;
  text-align: center;
  justify-content: space-around;
  box-shadow: 0 8px 16px 0 rgba(0, 0, 0, 0.5);
  border-radius: 0.5em; /*Jamie August 2*/
}

section div.table {
  justify-content: center;
  margin: 0.5rem;
}

section table {
  width: 90%;
}

td, th {
  font-size: 1em;
  border: 2px solid black;
  padding: 0.5em;
  text-align: center;
  margin: 0.3em;
}

section tr:nth-child(odd) {
  background-color: white;
}

section tr:nth-child(even) {
  background-color: var(--light-green);
}

section table thead tr th {
  background-color: var(--green);
  color: black;
}

section table tr td div {
  display: flex;
  justify-content: center;
  align-items: center;
}

.error {
  color: red;
  display: block;
  margin: 0.5em;
}

.hidden {
  display: none;
}

/*background gradient******************************************************************************/
body {
  background: linear-gradient(var(--back), transparent);
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  height: max-content;
}
/*navbar************************************************************************************************/
nav#navbar {
  font-family: 'Fredericka the Great', cursive;
}

/*the whole bar*/
nav#navbar > div {
  display: flex;
  flex-direction: row;
  justify-content: space-between;
  background-color: var(--blue);
  flex-wrap: wrap;
  padding: 1em;
  min-width: 35em;
}
/*flexbox containing the two sides of the bar*/
nav#navbar div > div {
  display: flex;
  flex-direction: row;
  justify-content: space-between;
  padding: 0 1em 0 1em;
}

/*list of links*/
nav#navbar div > ul {
  display: flex;
  flex-direction: row;
  padding: 0 1em 0 1em;
}
nav#navbar div > ul > li {
  padding: 0.5em 1em 0 1em;
  flex-shrink: 0;
}

/****forms***/
/*the search section*/
nav#navbar form {
  display: flex;
  flex-direction: row;
  padding: 0.3em 0 0 0;
}
/*search bar*/
nav#navbar div div form input:nth-child(2) {
  background-color: var(--green);
  border: none;
  border-radius: 5px;
  padding: 0.5em 4em 0.5em 2.5em;
}
/*button*/
nav#navbar div div form input:nth-child(3) {
  border: none;
  border-radius: 1em;
  padding: 0 1em 0 1em;
  background-color: var(--white);
  color: var(--black);
}

/*icons*/
nav#navbar i {
  color: var(--white);
}
nav#navbar i[class='far fa-clock'] {
  font-size: 2em;
}
nav#navbar i[class='fas fa-search'] {
  position: relative;
  top: 0.4em;
  left: 1.8em;
}

/*link colours*/
nav#navbar a {
  color: var(--white);
  text-decoration: none;
}
nav#navbar a:visited {
  color: var(--light-green);
}
nav#navbar a:hover {
  color: var(--green);
}

/*website title*/
nav#navbar h2.title {
  padding: 0 1em 0 1em;
  color: var(--white);
}
/*navbar************************************************************************************************/

/*home page for non-users*******************************************************************************/

/*the picture inside*******************************/
.container {
  position: relative;
  max-width: 100%; /* Maximum width */
  margin: 0 auto; /* Center it */
}

.container img{
  width: 100%;
}

.container .content {
  position: absolute;
  bottom: 40%;
  left: 25%;
  background: rgb(0, 0, 0); /* Fallback color */
  background: rgba(0, 0, 0, 0.7); /* Black background with 0.5 opacity */
  color: #f1f1f1; /* Grey text */
  width: 50%; /* Full width */
  padding: 20px; /* Some padding */
  text-align: center;
}

/*mySignUps ***************************************************************/

#mySignUps h3 {
  font-weight: bold;
  font-size: 1.5 rem;
}

#mySignUps section > div {
  display: flex;
  border: 0.2em solid var(--black);
  margin: 0.5rem;
  flex: 2 1 auto;
  flex-direction: column;
  padding: 0.5em;
}

#mySignUps section div > div {
  display: flex;
  flex-direction: row;
  justify-content: space-between;
  border: none;
}

#mySignUps section section{
  max-height: 1.5em;
}

/******************signupsheet section****************/

/*swap colours on hover*/
#signUpSheet button:hover {
  background-color: var(--green);
  border: 0.3em solid var(--borders);
  color: var(--white);
  border-radius: 0.5em; /*rounded corners*/
}
#FindSheet section {
  display: flex;
  border: 0.2em solid var(--black);
  margin: 0.5rem;
  background-color: var(--white);
  color: var(--black);
  flex-direction: column;
  justify-content: space-evenly;
  justify-content: stretch;
  flex: 2 1 auto;
}

#signUpSheet button, #signUpSheet td a {
  font-size: 1.2em;
  border-radius: 0.5em; /*rounded corners*/
  background-color: white;
  border: 0.3em solid var(--borders);
  color: var(--blue);
  text-align: center;
  text-decoration: none; /*remove underline*/
  display: flex;
  padding: 0.5em;
  margin-bottom: 1em;
  align-items: flex-start;
  text-align: center;
  justify-content: space-around;
  box-shadow: 0 8px 16px 0 rgba(0, 0, 0, 0.5);
}

#FindSheet form {
  display: flex;
  flex: 2 1 auto;
  padding: 1em;
  margin: 1em;
}

#FindSheet form input {
  height: 1.3em;
  margin-right: 0.1em;
}

#FindSheet form i {
  font-size: 2em;
  margin-top: 0.5em;
  margin-right: 0.3em;
}

#FindSheet form button {
  padding: 0.5em;
  margin: 0.5em;
  border-radius: 0.5em; /*Jamie August 2*/
}

#FindSheet section div div {
  display: flex;
  justify-content: space-between;
}

section .SlotSignUp {
  margin: 1em;
}

#signUpSheet header {
  /*Citation for the image below: https://www.pinterest.ca/pin/674203006692235309/*/
  background-image: url('../images/SignUpSheet.jpg');
  height: 20em;
  display: flex;
  justify-content: center;
  align-items: center;
  margin-bottom: 1em;
}

#signUpSheet section {
  display: flex;
  border: 0.2em solid var(--black);
  margin: 0.5rem;
  padding: 1em;
  background-color: var(--white);
  color: var(--black);
  flex-direction: column;
  justify-content: space-evenly;
  justify-content: stretch;
  flex: 2 1 auto;
}

#signUpSheet main {
  display: flex;
  align-items: flex-start;
  justify-content: space-between;
  margin: 0.1em;
}

#signUpSheet button:disabled {
  background-color: lightgrey;
}

#signUpSheet button:disabled:hover {
  color: var(--back);
}

#signUpSheet header h1 {
  background: rgb(255, 255, 255); /* Fallback color */
  background: rgba(255, 255, 255, 0.3); /* Black background with 0.5 opacity */
}

/*profile *************************************************************/
form#newuser, form#uploadform {
  display: flex;
  border: 0.2em solid var(--black);
  margin: 0.5rem;
  background-color: var(--white);
  color: var(--black);
  flex-direction: column;
  justify-content: space-evenly;
  justify-content: stretch;
  flex: 2 1 auto;
}
/*login page*******************************************************/
/*this is the box, border, shape*/

#loginbox section{
  max-width: 20%;
  border: solid black 2px;
  border-radius: 10px;
  background-color: var(--white);
}

#loginbox section > div {
  padding: 2em 3em 2em 3em;
  margin-bottom: 2em;
}
/*this is just for the form element, and the icon div*/
#loginbox section > div > div {
  display: flex;
  flex-direction: column;
  align-items: center;
}
/*the icon for the person*/
#loginbox section i {
  font-size: 6em;
  padding: 0.25em;
  margin-left: auto;
  margin-right: auto;
  background-color: white;
  border-radius: 10em;
}
#loginbox section > div > div > form {
  display: flex;
  flex-direction: column;
  align-items: center;
}
/*put the labels ontop of the boxes*/
#loginbox section > div > div > form label.usernamepass {
  display: block;
}
#loginbox > div > div > form > div {
  padding-top: 1em;
}

/*font and sizing for email password stuff*/
#loginbox section > div > div > form div#usernameField > input, #loginbox section > div > div > form div#passwordField > input {
  font-size: 1em;
  padding: 0.25em 1em 0.25em 1em;
}
#loginbox section > div > div > form div#usernameField > label, #loginbox section > div > div > form div#passwordField > label {
  padding: 0.25em 0 0.25em 0;
  font-size: 1.75em;
}

#loginbox section > div > div > form div button {
  font-size: 1.25em;
  padding: 0.5em 1em 0.5em 1em;
  border: transparent;
  border-radius: 5em;
  background-color: var(--light-green);
}

#loginbox section > div > div > form div button:hover {
  background-color: #85b98a;
}
/*the remember me box*/
#login > div:nth-child(6) {
  flex-direction: row;
}

/*reset password page*******************************************************/
section#emailbox > div {
  border: solid black 2px;
  border-radius: 10px;
  background-color: var(--white);
  padding: 2em 3em 2em 3em;
}
/*this is just for the form element, and the icon div*/
section#emailbox > div > div {
  display: flex;
  flex-direction: column;
  align-items: center;
}
/*the icon for the person*/
section#emailbox i {
  font-size: 6em;
  padding: 0.25em;
  margin-left: auto;
  margin-right: auto;
  background-color: white;
  border-radius: 10em;
}
section#emailbox > div > div > form {
  display: flex;
  flex-direction: column;
  align-items: center;
}
/*put the labels ontop of the boxes*/
section#emailbox > div > div > form label.emailaddress {
  display: block;
}
section#emailbox > div > div > form > div {
  padding-top: 1em;
}

/*font and sizing for email password stuff*/
section#emailbox > div > div > form div#emailspot > input {
  font-size: 1em;
  padding: 0.25em 1em 0.25em 1em;
}
section#emailbox > div > div > form div#emailspot > label {
  padding: 0.25em 0 0.25em 0;
  font-size: 1.75em;
}

section#emailbox > div > div > form div button {
  font-size: 1.25em;
  padding: 0.5em 1em 0.5em 1em;
  border: transparent;
  border-radius: 5em;
  background-color: var(--light-green);
}

section#emailbox > div > div > form div button:hover {
  background-color: #85b98a;
}

section#emailbox h3 {
  font-size: 1.5em;
  padding: 1em;
}
/*time slots**********************************************/
#timeSlot p, #timeSlot ul, #timeSlot li {
  margin: 0.5em;
}

#timeSlot main section div {
  display: flex;
  justify-content: space-between;
}

#timeSlot main section div h2 {
  margin: 0.3em;
}

```



## JavaScript

### createAccount.js

```javascript
"use strict";

//preview image
//citation https://developer.mozilla.org/en-US/docs/Web/API/FileReader/readAsDataURL
function previewFile() {
  const preview = document.querySelector('#previewimage');
  const file = document.querySelector('input[type=file]').files[0];
  const reader = new FileReader();
  if ( /\.(jpe?g|png|gif)$/i.test(file.name) ) {
    reader.addEventListener("load", function () {
      // convert image file to base64 string
      preview.src = reader.result;
    }, false);

    if (file) {
      reader.readAsDataURL(file);
    }
  }
  else{
    preview.src =  "images/profileImage.png";
  }
}

//Form validation
window.addEventListener("DOMContentLoaded", () => {

  //bool error flag
  let error = false;

  //select the form
  const uploadform = document.querySelector("#uploadform");


  //chcck that image is valid
  let fileInput   = document.querySelector('input[type=file]');
  fileInput.addEventListener("change", (ev) => {
    
      let file1 = document.querySelector('input[type=file]').files[0];

    //remove previous errors
    if(fileInput.nextSibling){
      fileInput.nextSibling.remove();
    }

    if (/\.(jpe?g|png|gif)$/i.test(file1.name) ) {
      error=false;
    }
    else{
      error=true;
      fileInput.insertAdjacentHTML("afterend", "<span class='error'>Incorrect File Type</span>");
    }
  });
  


  //Password strength plug-in
  //code referenced from:
  //https://github.com/jaimeneeves/checkforce.js
  let render = document.querySelector('.strength');
  
  CheckForce('#password1').checkPassword(function(response){
    render.innerHTML = response.content;
  });

  
  //check that passwords match
  const password1 = document.querySelector("#password1");
  const password2 = document.querySelector("#password2");
  
  password2.addEventListener("change", (ev) => {
    
    //remove previous errors
    if(password2.nextSibling){
      password2.nextSibling.remove();
    }

    if(password2.value != password1.value){
      error = true;
      password2.insertAdjacentHTML("afterend", "<span class='error'>Passwords dont match</span>");
    }
    else{
      //make sure error isnt set by something else
      if(!error){
        error = false;
      }
    }
  });

  //This Section Below checks username uniqueness using AJAX
  const username = document.querySelector("#username");

  username.addEventListener("change", (ev) => {

    //remove previous errors
    if(username.nextSibling){
      username.nextSibling.remove();
    }
    
    //open request
    const xhr = new XMLHttpRequest();
    xhr.open("GET", "checkUsername.php?username=" + username.value);

    //when xhr loads
    xhr.addEventListener("load", (ev) => {
      if(!status==200){ //if doesnt work
        console.log(xhr.response);
        console.log('something went wrong');
      }
      else{

        let response = xhr.responseText;

        if(response == 'true'){
          //username.insertAdjacentHTML("afterend", "<span>Username available</span>");
          //make sure error isnt set by something else
        if(!error){
          error = false;
        }

        }
        else if(response == 'false') {
          username.insertAdjacentHTML("afterend", "<span class='error'>Username already taken</span>");
          error = true;
        }
        else
        {
          username.insertAdjacentHTML("afterend", "<span class='error'>Unable to Check Username</span>");
          error = true;
        }
      }

    });
    xhr.send();

  });//end username uniqueness check


  //This Section Below checks email uniqueness using AJAX

  const email = document.querySelector("#email");

  email.addEventListener("change", (ev) => {

    //remove previous errors
    if(email.nextSibling){
      email.nextSibling.remove();
    }
    
    //open request
    const xhr = new XMLHttpRequest();
    xhr.open("GET", "checkEmail.php?email=" + email.value);

    //when xhr loads
    xhr.addEventListener("load", (ev) => {
      if(!status==200){ //if doesnt work
        console.log(xhr.response);
        console.log('something went wrong');
      }
      else{

        let response = xhr.responseText;

        if(response == 'true'){
          //email.insertAdjacentHTML("afterend", "<span>email available</span>");
          //make sure error isnt set by something else
        if(!error){
          error = false;
        }
        }
        else if(response == 'false') {
          email.insertAdjacentHTML("afterend", "<span class='error'>email already taken</span>");
          error = true;
        }
        else
        {
          email.insertAdjacentHTML("afterend", "<span class='error'>Unable to Check email</span>");
          error = true;
        }
      }

    });
    xhr.send();

  });//end email uniqueness check



  uploadform.addEventListener("submit", (ev) => {
    if (error) ev.preventDefault(); //STOP FORM SUBMISSION IF THERE ARE ERRORS
  });

  
});
```



### editSheet.js

```javascript
"use strict";

//preview image
//citation https://developer.mozilla.org/en-US/docs/Web/API/FileReader/readAsDataURL
function previewFile() {
    const preview = document.querySelector('#previewimage');
    const file = document.querySelector('input[type=file]').files[0];
    const reader = new FileReader();
    if ( /\.(jpe?g|png|gif)$/i.test(file.name) ) {
      reader.addEventListener("load", function () {
        // convert image file to base64 string
        preview.src = reader.result;
      }, false);
  
      if (file) {
        reader.readAsDataURL(file);
      }
    }
    else{
      preview.src =  "images/profileImage.png";
    }
  }

  //Form validation
window.addEventListener("DOMContentLoaded", () => {

    //bool error flag
    let error = false;
  
    //select the form
    const uploadform = document.querySelector("#uploadform");
  
  
    //chcck that image is valid
    let fileInput   = document.querySelector('input[type=file]');
    fileInput.addEventListener("change", (ev) => {
      
        let file1 = document.querySelector('input[type=file]').files[0];

      //remove previous errors
      if(fileInput.nextSibling){
        fileInput.nextSibling.remove();
      }

      if (/\.(jpe?g|png|gif)$/i.test(file1.name) ) {
        error=false;
      }
      else{
        error=true;
        fileInput.insertAdjacentHTML("afterend", "<span class='error'>Incorrect File Type</span>");
      }
    });
    
  
    //This Section Below checks username uniqueness using AJAX
    const username = document.querySelector("#username");
  
    username.addEventListener("change", (ev) => {
  
      //remove previous errors
      if(username){
        username.nextSibling.remove();
      }
      
      //open request
      const xhr = new XMLHttpRequest();
      xhr.open("GET", "checkUsername.php?username=" + username.value);
  
      //when xhr loads
      xhr.addEventListener("load", (ev) => {
        if(!status==200){ //if doesnt work
          console.log(xhr.response);
          console.log('something went wrong');
        }
        else{
  
          let response = xhr.responseText;
  
          if(response == 'true'){
            //username.insertAdjacentHTML("afterend", "<span>Username available</span>");
            //make sure error isnt set by something else
          if(!error){
            error = false;
          }
  
          }
          else if(response == 'false') {
            username.insertAdjacentHTML("afterend", "<span class='error'>Username already taken</span>");
            error = true;
            
          }
          else
          {
            username.insertAdjacentHTML("afterend", "<span class='error'>Unable to Check Username</span>");
            error = true;
            
          }
        }
  
      });
      xhr.send();
  
    });//end username uniqueness check
  
  
    //This Section Below checks email uniqueness using AJAX
  
    const email = document.querySelector("#email");
  
    email.addEventListener("change", (ev) => {
  
      //remove previous errors
      if(email.nextSibling){
        email.nextSibling.remove();
      }
      
      //open request
      const xhr = new XMLHttpRequest();
      xhr.open("GET", "checkEmail.php?email=" + email.value);
  
      //when xhr loads
      xhr.addEventListener("load", (ev) => {
        if(!status==200){ //if doesnt work
          console.log(xhr.response);
          console.log('something went wrong');
        }
        else{
  
          let response = xhr.responseText;
  
          if(response == 'true'){
            //email.insertAdjacentHTML("afterend", "<span>email available</span>");
            //make sure error isnt set by something else
          if(!error){
            error = false;
          }
          }
          else if(response == 'false') {
            email.insertAdjacentHTML("afterend", "<span class='error'>email already taken</span>");
            error = true;
            console.log(4);
          }
          else
          {
            email.insertAdjacentHTML("afterend", "<span class='error'>Unable to Check email</span>");
            error = true;
          }
        }
  
      });
      xhr.send();
  
    });//end email uniqueness check
  
  
  
    uploadform.addEventListener("submit", (ev) => {
        console.log(error)
      if (error) ev.preventDefault(); //STOP FORM SUBMISSION IF THERE ARE ERRORS
    });
  
    
  });
```



### createSheet.js

```javascript
"use strict";
const title = document.querySelector("#title");
title.addEventListener("change", () => {
    document.querySelector("table tbody tr > td").innerHTML = "" + title.value;
});
//create sign-up add time slots
const button = document.getElementById("addSlot");
var numSlots = 1;
button.addEventListener("click", () => {
    console.log("addSlot");
    addRow("generateSlots");
    numSlots ++;
    document.querySelector("#numSlots").value = numSlots;
});

function addRow(id){ 
    console.log("addRow");
    var x=document.getElementById(id).tBodies[0];  //get the table
    var node=x.rows[0].cloneNode(true);    //clone the first node or row
    x.appendChild(node);   //add the node or row to the table
} 

//plug-in
const datePicker = document.querySelectorAll("tbody tr td div");
for (let i = 0; i < numSlots; i ++){
    $(document).on('focus', '#basicDate',function(){
        $(this).flatpickr({
            appendTo: datePicker[i],
            enableTime: true,
            altInput: true,
            altFormat: "F, d Y H:i",
            dateFormat: "Y-m-d H:i"
        });
    });
}

//Form validation
const requestForm = document.querySelector("#sheet");

//replace 'event name here' with the correct event
requestForm.addEventListener("submit", (ev) => {
    //declare a boolean flag valid set to false for determining if there were any errors found below
    let error = false;

    const titleInput = document.querySelector("#title");
    const titleError = titleInput.nextElementSibling;

    //validate user has entered a title
    titleError.classList.remove("hidden");
    if (titleInput.value != "") {
        titleError.classList.add("hidden");
    } else {
        console.log("title error");
        error = true;
    }

    const creatorInput = document.querySelector("#creator");
    const creatorError = creatorInput.nextElementSibling;

    //validate user has entered a creator
    creatorError.classList.remove("hidden");
    if (creatorInput.value != "") {
        creatorError.classList.add("hidden");
    } else {
        error = true;
        console.log("creator error");
    }

    const descriptionInput = document.querySelector("#description");
    const descriptionError = descriptionInput.nextElementSibling;

    //validate user has entered a desc
    descriptionError.classList.remove("hidden");
    if (descriptionInput.value != "") {
        descriptionError.classList.add("hidden");
    } else {
        error = true;
        console.log("description error");
    }

    const locationInput = document.querySelector("#location");
    const locationError = locationInput.nextElementSibling;

    //validate user has entered a location
    locationError.classList.remove("hidden");
    if (locationInput.value != "") {
        locationError.classList.add("hidden");
    } else {
        error = true;
        console.log("location error");
    }

    const privacy = document.querySelector("input[type='radio']:checked");
    const privacyError = document.querySelector("fieldset span");

    //validate that a radio button was selected
    privacyError.classList.remove("hidden");
    if (privacy) {
        privacyError.classList.add("hidden");
    } else {
        error = true;
        console.log("privacy error");
    }

    const numSlotsInput = document.querySelector("#numSlots");
    const numSlotsError = numSlotsInput.nextElementSibling;

    //validate user has entered a number of slots
    numSlotsError.classList.remove("hidden");
    if (numSlotsInput.value >= 1) {
        numSlotsError.classList.add("hidden");
    } else {
        error = true;
        console.log("slot number error");
    }

    // Make this conditional on if there are errors.
    if (error) ev.preventDefault(); //STOP FORM SUBMISSION IF THERE ARE ERRORS
});
```



### deleteProfile.js

```javascript
"use strict";
//confirmation dialog on delete
const deleteButton = document.querySelector("form div button");
deleteButton.addEventListener("click", (ev) =>{
    let confirmDelete = confirm ("Are you sure you want to delete this sheet?");
    if (confirmDelete == false){
        ev.preventDefault();
    }
});
```



### deleteSlot.js

```javascript
"use strict";
//confirmation dialog on delete
const deleteButton = document.querySelector("#timeSlot div ul li");
deleteButton.addEventListener("click", (ev) =>{
    let confirmDelete = confirm ("Are you sure you want to delete this sheet?");
    if (confirmDelete == false){
        ev.preventDefault();
    }
});

deleteButton.addEventListener("keypress", (ev) =>{
    if (ev.keyCode === 13){
        let confirmDelete = confirm ("Are you sure you want to delete this sheet?");
        if (confirmDelete == false){
            ev.preventDefault();
        }
    }
});
```



### editSheet.js

```javascript
"use strict";
//plug-in
const datePicker = document.querySelectorAll(".table");
for (let i = 0; i < 2; i ++){
    $(document).on('focus', '#basicDate',function(){
        $(this).flatpickr({
            appendTo: datePicker[i],
            enableTime: true,
            altInput: true,
            altFormat: "F, d Y H:i",
            dateFormat: "Y-m-d H:i"
        });
    });
}"use strict";
//plug-in
const datePicker = document.querySelectorAll(".table");
for (let i = 0; i < 2; i ++){
    $(document).on('focus', '#basicDate',function(){
        $(this).flatpickr({
            appendTo: datePicker[i],
            enableTime: true,
            altInput: true,
            altFormat: "F, d Y H:i",
            dateFormat: "Y-m-d H:i"
        });
    });
}

//create sign-up add time slots
const button = document.getElementById("addSlot");
let numSlots = document.querySelector("#numSlots").value;
button.addEventListener("click", () => {
    console.log("addSlot");
    addRow("generateSlots");
    numSlots ++;
    document.querySelector("#numSlots").value = numSlots;
});

function addRow(id){ 
    console.log("addRow");
    let x=document.getElementById(id).tBodies[0];  //get the table
    let node=x.rows[0].cloneNode(true);    //clone the previous node or row
    console.log(node);
    x.appendChild(node);   //add the node or row to the table

    //select the new input that just got cloned and change its name for the POST to work proper
    let Inputs = document.getElementsByName('dateTime0');
    let newInput = Inputs[1];
    newInput.name = 'dateTime' + numSlots;
    newInput.disabled = false;

    //clear words from td, and add the delete checkbox
    let rows = document.getElementsByClassName('row');
    let newRow = rows[rows.length-1];
    let newRowEndItem = newRow.lastElementChild;
    newRowEndItem.innerHTML = "Delete Slot: <input type='checkbox' name='deleteNEW' value='Delete'>";
    let newDelete = document.getElementsByName('deleteNEW');
    newDelete = newDelete[0];
    newDelete.name = 'delete' + numSlots;
    
} 
```



### formVerification.js

```javascript
"use strict";
//Form validation
const requestForm = document.querySelector("#editForm");

//replace 'event name here' with the correct event
requestForm.addEventListener("submit", (ev) => {
    //declare a boolean flag valid set to false for determining if there were any errors found below
    let error = false;

    const titleInput = document.querySelector("#title");
    const titleError = titleInput.nextElementSibling;

    //validate user has entered a date
    titleError.classList.remove("hidden");
    if (titleInput.value != "") {
        titleError.classList.add("hidden");
    } else {
        error = true;
    }

    const creatorInput = document.querySelector("#creator");
    const creatorError = creatorInput.nextElementSibling;

    //validate user has entered a date
    creatorError.classList.remove("hidden");
    if (creatorInput.value != "") {
        creatorError.classList.add("hidden");
    } else {
        error = true;
    }

    const descriptionInput = document.querySelector("#description");
    const descriptionError = descriptionInput.nextElementSibling;

    //validate user has entered a date
    descriptionError.classList.remove("hidden");
    if (descriptionInput.value != "") {
        descriptionError.classList.add("hidden");
    } else {
        error = true;
    }

    const locationInput = document.querySelector("#location");
    const locationError = locationInput.nextElementSibling;

    //validate user has entered a date
    locationError.classList.remove("hidden");
    if (locationInput.value != "") {
        locationError.classList.add("hidden");
    } else {
        error = true;
    }

    const privacy = document.querySelector("input[type='radio']:checked");
    const privacyError = document.querySelector("fieldset span");

    //validate that a radio button was selected. Remember that a radio button's checked attribute determines if it was selected
    privacyError.classList.remove("hidden");
    if (privacy) {
        privacyError.classList.add("hidden");
    } else {
        error = true;
    }

    const numSlotsInput = document.querySelector("#numSlots");
    const numSlotsError = numSlotsInput.nextElementSibling;

    //validate user has entered a title
    numSlotsError.classList.remove("hidden");
    if (numSlotsInput.value == count) {
        numSlotsError.classList.add("hidden");
    } else {
        error = true;
    }

    const dateInput = document.querySelector("#date");
    const dateError = dateInput.nextElementSibling;

    //validate user has entered a date
    dateError.classList.remove("hidden");
    if (dateInput.value != "") {
        dateError.classList.add("hidden");
    } else {
        error = true;
    }

    const timeInput = document.querySelector("#time");
    const timeError = timeInput.nextElementSibling;

    //validate user has entered a time
    timeError.classList.remove("hidden");
    if (timeInput.value != "") {
        timeError.classList.add("hidden");
    } else {
        error = true;
    }

    // Make this conditional on if there are errors.
    if (error) ev.preventDefault(); //STOP FORM SUBMISSION IF THERE ARE ERRORS
});
```



### generateSlots.js

```javascript
"use strict";
const title = document.querySelector("#title");
title.addEventListener("change", () => {
    document.querySelector("table tbody tr > td").innerHTML = "" + title.value;
});
//create sign-up add time slots
const button = document.getElementById("addSlot");
var numSlots = 1;
button.addEventListener("click", () => {
    console.log("addSlot");
    addRow("generateSlots");
    numSlots ++;
    document.querySelector("#numSlots").value = numSlots;
});

function addRow(id){ 
    console.log("addRow");
    var x=document.getElementById(id).tBodies[0];  //get the table
    var node=x.rows[0].cloneNode(true);    //clone the previous node or row
    x.appendChild(node);   //add the node or row to the table
} 

//plug-in
const datePicker = document.querySelectorAll("tbody tr td div");
for (let i = 0; i < numSlots; i ++){
    $(document).on('focus', '#basicDate',function(){
        $(this).flatpickr({
            appendTo: datePicker[i],
            enableTime: true,
            altInput: true,
            altFormat: "F, d Y H:i",
            dateFormat: "Y-m-d H:i"
        });
    });
}

//Form validation
const requestForm = document.querySelector("#sheet");

//replace 'event name here' with the correct event
requestForm.addEventListener("submit", (ev) => {
    //declare a boolean flag valid set to false for determining if there were any errors found below
    let error = false;

    const titleInput = document.querySelector("#title");
    const titleError = titleInput.nextElementSibling;

    //validate user has entered a date
    titleError.classList.remove("hidden");
    if (titleInput.value != "") {
        titleError.classList.add("hidden");
    } else {
        console.log("title error");
        error = true;
    }

    const creatorInput = document.querySelector("#creator");
    const creatorError = creatorInput.nextElementSibling;

    //validate user has entered a date
    creatorError.classList.remove("hidden");
    if (creatorInput.value != "") {
        creatorError.classList.add("hidden");
    } else {
        error = true;
        console.log("creator error");
    }

    const descriptionInput = document.querySelector("#description");
    const descriptionError = descriptionInput.nextElementSibling;

    //validate user has entered a date
    descriptionError.classList.remove("hidden");
    if (descriptionInput.value != "") {
        descriptionError.classList.add("hidden");
    } else {
        error = true;
        console.log("description error");
    }

    const locationInput = document.querySelector("#location");
    const locationError = locationInput.nextElementSibling;

    //validate user has entered a date
    locationError.classList.remove("hidden");
    if (locationInput.value != "") {
        locationError.classList.add("hidden");
    } else {
        error = true;
        console.log("location error");
    }

    const privacy = document.querySelector("input[type='radio']:checked");
    const privacyError = document.querySelector("fieldset span");

    //validate that a radio button was selected.
    privacyError.classList.remove("hidden");
    if (privacy) {
        privacyError.classList.add("hidden");
    } else {
        error = true;
        console.log("privacy error");
    }

    const numSlotsInput = document.querySelector("#numSlots");
    const numSlotsError = numSlotsInput.nextElementSibling;

    //validate user has entered a title
    numSlotsError.classList.remove("hidden");
    if (numSlotsInput.value >= 1) {
        numSlotsError.classList.add("hidden");
    } else {
        error = true;
        console.log("slot number error");
    }

    const dateTimeInput = document.querySelector("#basicDate");
    const dateTimeError = dateTimeInput.nextElementSibling;
    console.log(dateTimeInput.value);
    //validate user has entered a date and time
    dateTimeError.classList.remove("hidden");
    if (dateTimeInput.value != "") {
        dateTimeError.classList.add("hidden");
    } else {
        error = true;
        console.log("date error");
    }

    // Make this conditional on if there are errors.
    if (error) ev.preventDefault(); //STOP FORM SUBMISSION IF THERE ARE ERRORS
});
```



### mySignUps.js

```javascript
"use strict";
//confirmation dialog on delete
//Mouse delete sheet
const sheetListItems = document.querySelectorAll(".Sign-upSheets div ul");
for(let i = 0; i < sheetListItems.length; i ++){
    if (sheetListItems[i].lastElementChild != undefined){
        sheetListItems[i].lastElementChild.addEventListener("click", (ev) =>{
            let confirmSheetDelete = confirm ("Are you sure you want to delete this sheet?");
            if (confirmSheetDelete == false){
                ev.preventDefault();
            }
        });
    }
}

//Mouse delete time slot
const slotListItems = document.querySelectorAll(".Slots div ul");
for(let i = 0; i < slotListItems.length; i ++){
    if (slotListItems[i].lastElementChild != undefined){
        slotListItems[i].lastElementChild.addEventListener("click", (ev) =>{
            let confirmSheetDelete = confirm ("Are you sure you want to delete this sheet?");
            if (confirmSheetDelete == false){
                ev.preventDefault();
            }
        });
    }
}

//Keyboard delete sheet
for(let i = 0; i < sheetListItems.length; i ++){
    if (sheetListItems[i].lastElementChild != undefined){
        sheetListItems[i].lastElementChild.addEventListener("click", (ev) =>{
            if (ev.keyCode === 13){
                let confirmSheetDeleteKey = confirm ("Are you sure you want to delete this sheet?");
                if (confirmSheetDeleteKey == false){
                    ev.preventDefault();
                }
            }
        });
    }
}

//Keyboard delete time slot
for(let i = 0; i < slotListItems.length; i ++){
    if (slotListItems[i].lastElementChild != undefined){
        slotListItems[i].lastElementChild.addEventListener("click", (ev) =>{
            if (ev.keyCode === 13){
                let confirmSlotDeleteKey = confirm ("Are you sure you want to delete this time slot?");
                if (confirmSlotDeleteKey == false){
                    ev.preventDefault();
                }
            }
        });
    }

}
```



## Database Dump

### Database Schema

![schema](C:\Users\billj\Documents\TrentU\Cois-3420\Loki\3420\finalProjSnips\schema.JPG)

#### Small Description/Summary

The sheets table contains a foreign key to the users table, to get information about the person who created the sheet

The slots table contains a foreign key to the sheets table, to get the sheet it is linked with. It also has a key for the user that is booked in the slot.

The images table contains a foreign key to the user table to link the user to its image

The reset password table really only contains temporary information and is not linked to anything. But it can only be filled through the application if the email is linked to a user account in the table.

### timeslot_images

```sql
--
-- Database: `williamvanleeuwen`
--

-- --------------------------------------------------------

--
-- Table structure for table `timeslot_images`
--

CREATE TABLE IF NOT EXISTS `timeslot_images` (
  `imgID` int(11) NOT NULL,
  `filepath` varchar(250) DEFAULT NULL,
  `userID` int(11) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `timeslot_images`
--

INSERT INTO `timeslot_images` (`imgID`, `filepath`, `userID`) VALUES
(14, '../www_data/14.jpg', 50),
(18, '../www_data/18.jpg', 66);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `timeslot_images`
--
ALTER TABLE `timeslot_images`
  ADD PRIMARY KEY (`imgID`),
  ADD KEY `userID` (`userID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `timeslot_images`
--
ALTER TABLE `timeslot_images`
  MODIFY `imgID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=19;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `timeslot_images`
--
ALTER TABLE `timeslot_images`
  ADD CONSTRAINT `timeslot_images_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `timeslot_users` (`ID`),
  ADD CONSTRAINT `timeslot_images_ibfk_2` FOREIGN KEY (`userID`) REFERENCES `timeslot_users` (`ID`);
```



### timeslot_resetpassword

```sql
--
-- Database: `williamvanleeuwen`
--

-- --------------------------------------------------------

--
-- Table structure for table `timeslot_resetpassword`
--

CREATE TABLE IF NOT EXISTS `timeslot_resetpassword` (
  `email` varchar(200) DEFAULT NULL,
  `expiry` int(11) DEFAULT NULL,
  `key` varchar(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `timeslot_resetpassword`
--

INSERT INTO `timeslot_resetpassword` (`email`, `expiry`, `key`) VALUES
('billvanleeuwen424@gmail.com', 1626962471, 'ab8eb50f00345e680690d047928aad91da885b810a7f5784fe5d1ff39825'),
('billvanleeuwen424@gmail.com', 1626962620, '1b9f97f9284cd769e921aca9aca2817011a97c0b53ee35bb87c823a2ce1a'),
('billvanleeuwen424@gmail.com', 1626962668, 'e87ff1564cbf38ef15ed25da10fa36c9e3d3da7788b3d815c7fbf153ed6a'),
('billvanleeuwen424@gmail.com', 1626962726, '06e731aa0b91bb70721f1fc72b5b9548dd3ef0bfcde7f58477b478980819'),
('billvanleeuwen424@gmail.com', 1626985453, '75c0b484b5413762e42ef9dff681c06098da277140c461585a29bcd22bff'),
('billvanleeuwen424@gmail.com', 1627025267, 'd0c57f5a45eaf49b159932b9696a877982428621b10a6139f0d09b19b3ca'),
('billvanleeuwen424@gmail.com', 1627025367, '978cbc94a80d9208553c507ab8dd911b357a064244e8d9e623bebd1ae4e0');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

```



### timeslot_sheets

```sql
--
-- Database: `williamvanleeuwen`
--

-- --------------------------------------------------------

--
-- Table structure for table `timeslot_sheets`
--

CREATE TABLE IF NOT EXISTS `timeslot_sheets` (
  `ID` int(11) NOT NULL,
  `numslots` int(11) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `numslotsfilled` int(11) DEFAULT NULL,
  `description` varchar(200) DEFAULT NULL,
  `privacy` enum('public','private') DEFAULT NULL,
  `host` int(11) DEFAULT NULL,
  `dateCreated` date DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=55578 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `timeslot_sheets`
--

INSERT INTO `timeslot_sheets` (`ID`, `numslots`, `name`, `numslotsfilled`, `description`, `privacy`, `host`, `dateCreated`) VALUES
(55571, 5, 'build a new sheet12', 5, 'this is a shet2', 'public', 50, '2021-08-02'),
(55572, 3, 'Testing The Creation of Signup Sheets', 2, 'This project is due tonight', 'public', 66, '2021-08-04'),
(55577, 3, 'Testing The Creation of Signup Sheets', 2, 'This project is due tonight', 'public', 66, '2021-08-04');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `timeslot_sheets`
--
ALTER TABLE `timeslot_sheets`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `host` (`host`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `timeslot_sheets`
--
ALTER TABLE `timeslot_sheets`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=55578;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `timeslot_sheets`
--
ALTER TABLE `timeslot_sheets`
  ADD CONSTRAINT `timeslot_sheets_ibfk_1` FOREIGN KEY (`host`) REFERENCES `timeslot_users` (`ID`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
```



### timeslot_slots

```sql
--
-- Database: `williamvanleeuwen`
--

-- --------------------------------------------------------

--
-- Table structure for table `timeslot_slots`
--

CREATE TABLE IF NOT EXISTS `timeslot_slots` (
  `ID` int(11) NOT NULL,
  `sheetID` int(11) NOT NULL,
  `date` date DEFAULT NULL,
  `time` time DEFAULT NULL,
  `location` varchar(50) DEFAULT NULL,
  `notes` varchar(200) DEFAULT NULL,
  `userID` int(11) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=118 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `timeslot_slots`
--

INSERT INTO `timeslot_slots` (`ID`, `sheetID`, `date`, `time`, `location`, `notes`, `userID`) VALUES
(85, 55571, '2021-08-04', '12:00:00', 'nalgene', 'beepbeppbepp', 50),
(86, 55571, '2021-08-05', '12:00:00', 'nalgene', 'beepbeppbepp', 63),
(88, 55571, '2021-08-11', '12:00:00', 'nalgene', 'beepbeppbepp', 66),
(89, 55571, '2021-08-25', '12:00:00', 'nalgene', 'beepbeppbepp', NULL),
(90, 55571, '2021-08-28', '12:00:00', 'nalgene', 'beepbeppbepp', 64),
(100, 55572, '2021-08-04', '10:00:00', 'at my desk', 'I hope we get a really good mark on this project', 67),
(101, 55572, '2021-08-04', '11:00:00', 'at my desk', 'I hope we get a really good mark on this project', NULL),
(102, 55572, '2021-08-04', '12:00:00', 'at my desk', 'I hope we get a really good mark on this project', NULL),
(115, 55577, '2021-08-04', '10:00:00', 'at my desk', 'I hope we get a really good mark on this project', NULL),
(116, 55577, '2021-08-04', '11:00:00', 'at my desk', 'I hope we get a really good mark on this project', NULL),
(117, 55577, '2021-08-04', '12:00:00', 'at my desk', 'I hope we get a really good mark on this project', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `timeslot_slots`
--
ALTER TABLE `timeslot_slots`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `sheetID` (`sheetID`),
  ADD KEY `userID` (`userID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `timeslot_slots`
--
ALTER TABLE `timeslot_slots`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=118;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `timeslot_slots`
--
ALTER TABLE `timeslot_slots`
  ADD CONSTRAINT `timeslot_slots_ibfk_1` FOREIGN KEY (`sheetID`) REFERENCES `timeslot_sheets` (`ID`),
  ADD CONSTRAINT `timeslot_slots_ibfk_2` FOREIGN KEY (`userID`) REFERENCES `timeslot_users` (`ID`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
```



### timeslot_users

```sql
-- Database: `williamvanleeuwen`
--

-- --------------------------------------------------------

--
-- Table structure for table `timeslot_users`
--

CREATE TABLE IF NOT EXISTS `timeslot_users` (
  `ID` int(11) NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` text DEFAULT NULL,
  `gender` enum('male','female','gqnc','notsay') DEFAULT NULL,
  `name` varchar(40) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=69 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `timeslot_users`
--

INSERT INTO `timeslot_users` (`ID`, `username`, `password`, `gender`, `name`, `email`) VALUES
(50, 'billvanleeuwen424', '$2y$10$TdrFCZlO/BAz19itOlCdHOATCksICihLuzDztnqmQtcmTXj3BxPRK', 'male', 'Bill Test', 'billvanleeuwen424@gmail.com'),
(56, 'guest7006880457', NULL, NULL, 'Jack Test', 'billjoebob424@gmail.com'),
(57, 'guest4a8ea9aa57', NULL, NULL, 'Jack Test', 'billjoebob424@gmail.com'),
(58, 'guest1633245005', NULL, NULL, 'alex Man', 'alex@alex.cokm'),
(59, 'guest1d9bf41391', NULL, NULL, 'Bobby Bob', 'yeet@yeet.com'),
(60, 'guest60109d2af5', NULL, NULL, 'Bobby Boby', 'yeet@yeet.com'),
(61, 'guest3f36318350', NULL, NULL, 'Bobby Bobyy', 'yeet@yeet.com'),
(63, 'guest7405d72584', NULL, NULL, 'bobby bobface', 'email@email.com'),
(64, 'guest280bfd83d7', NULL, NULL, 'Abraham Lincoln', 'Abe@union.com'),
(66, 'williamvanleeuwen', '$2y$10$hlJ1Wd95ZKKgl8ORUMmQTOsBoE8UkXytDq2O8cbx8Cl9LoFv1C5Xy', 'male', 'William VanLeeuwen', 'williamvanleeuwen@trentu.ca'),
(67, 'guest1c0a569a48', NULL, NULL, 'Severus Snape', 'slytherinhouse@hogwarts.co.uk');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `timeslot_users`
--
ALTER TABLE `timeslot_users`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `timeslot_users`
--
ALTER TABLE `timeslot_users`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=69;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

```

