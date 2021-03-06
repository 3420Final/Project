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