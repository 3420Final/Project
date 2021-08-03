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

        // $query = "SELECT * FROM `timeslot_sheets` WHERE numslots = ? AND name = ? AND description = ? AND privacy = ?";
        // $stmt = $pdo->prepare($query);
        // $stmt->execute([$numSlots, $title, $description, $privacy]);
        // $sheetID = $stmt->fetch();
        // for ($i = 0; $i < $numSlots; $i ++){
        //   $query = "UPDATE timeslot_slots SET date = ?, time = ?, location = ?, notes =?";
        //   $stmt = $pdo->prepare($query);
        //   $stmt->execute([$date, $time, $location, $notes]);
        // }
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
  <body>
    <section id = "signUpSheet">
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
              <input id="title" name="title" type="text" placeholder="Project Check-In #1"value="<?=$title?>"/>
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
                          <label for="date">Date and Time: </label>
                          <input type="text" name="dateTime<?=$i-1?>" id="basicDate" placeholder="Please select Date Time" <?php if($slot["userID"] != null) echo'disabled'?> data-input value="<?= ($dateTime == null) ? null : $dateTime?>"  >
                          <span class="error <?=!isset($errors['dateTime']) ? 'hidden' : "";?>">Please enter a date</span>
                        </div>
                      </td>
                      <?php if ($slot["userID"] == null): ?>
                        <td><button id="submit" disabled>Book Time Slot</button></td>
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
    </section>
  </body>
</html>
