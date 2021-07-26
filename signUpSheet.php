<?php
  $title = $_POST['title'] ?? null;
  $description = $_POST['description'] ?? null;
  $location = $_POST['location'] ?? null;
  $privacy = $_POST['status'] ?? null;
  $numSlots = $_POST['numSlots'] ?? null;
  $notes = $_POST['notes'] ?? null;
  $date = $_POST['date'] ?? null;
  $time = $_POST['time'] ?? null;
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
    if (!isset($date)) {
      $errors['date'] = true;
    }

    //validate user has entered a title
    if (!isset($time)) {
      $errors['time'] = true;
    }

      //only do this if there weren't any errors
      if (count($errors) === 0) {

        include 'includes/library.php';
        $pdo = connectDB();

        //get the user ID
        $query = "select * from `timeslot_users` WHERE username = ?";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$creator]);
        $host = $stmt->fetch();

        //insert the sheet
        $query = "INSERT INTO timeslot_sheets VALUES (NULL, ?,?,?,?,?,?, NOW())";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$numSlots, $title, '0', $description, $privacy, $host["ID"]]);



        $query = "SELECT * FROM `timeslot_sheets` WHERE numslots = ? AND name = ? AND description = ? AND privacy = ?";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$numSlots, $title, $description, $privacy]);
        $sheetID = $stmt->fetch();
        for ($i = 0; $i < $numSlots; $i ++){
          $query = "insert into timeslot_slots values (NULL,?,?,?,?,?,NULL)";
          $stmt = $pdo->prepare($query);
          $stmt->execute([$sheetID["ID"], $date, $time, $location, $notes]);
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
    <title>Create Sign-Up Sheet</title>
    <link rel ="stylesheet" href = "styles/master.css"/>
    <script defer src="scripts/createSheet.js"></script>
    <script src="https://kit.fontawesome.com/accfddd944.js" crossorigin="anonymous"></script>
  </head>
  <body>
    <section id = "signUpSheet">
      <?php include 'includes/navbar.php';?>
      <header>
        <h1>New Sign-Up Sheet</h1>
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
                <input id="numSlots" name="numSlots" type="number" value="<?=$numSlots?>" disabled/>
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
                          <label for="date">Date: </label>
                          <input id="date" name="date" type="date" value="<?=$date?>"/>
                          <span class="error <?=!isset($errors['date']) ? 'hidden' : "";?>">Please enter a date</span>
                        </div>
                        <div>
                          <label for="time">Time</label>
                          <input id="time" name="time" type="time" value="<?=$time?>"/>
                          <span class="error <?=!isset($errors['time']) ? 'hidden' : "";?>">Please enter a time</span>
                        </div>
                      </td>
                      <td><button id="submit" disabled>Book Time Slot</button></td>
                    </tr>
                </tbody>
              </table>
              <div>
                <button type="button" name="addSlot" id="addSlot">Add Another Time Slot</button>
              </div>
            <div>
              <button type="submit" name="submit">Generate Slots</button>
            </div>
          </form>
        </section>
      </main>
    </section>
  </body>
</html>
