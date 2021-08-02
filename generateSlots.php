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
  <body>
    <section id = "signUpSheet">
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
              <input id="title" name="title" type="text" placeholder="Project Check-In #1"value="<?=$title?>"/>
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
                  </tr>
                </thead>
                <tbody>
                  <?php for ($i = 1; $i <= $numSlots; $i++): ?>
                    <tr>
                      <td><?=$title?></td>
                      <td>
                        <div>
                          <label for="date">Date and Time: </label>
                          <input type="text" name="dateTime<?=$i-1?>" id="basicDate" placeholder="Please select Date Time" data-input value="<?= ($dateTime == null) ? null : $dateTime[$i-1]?>">
                          <span class="error <?=!isset($errors['dateTime']) ? 'hidden' : "";?>">Please enter a date</span>
                        </div>
                      </td>
                      <td><button id="submit" disabled>Book Time Slot</button></td>
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
    </section>
  </body>
</html>
