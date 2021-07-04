<?php
  session_start();
  $creator = $_SESSION['username'];
  $title = $_POST['title'] ?? $title = $_SESSION['title'] ?? null;
  $description = $_POST['description'] ?? $description = $_SESSION['description'] ?? null;
  $location = $_POST['location'] ?? $location = $_SESSION['location'] ?? null;
  $privacy = $_POST['status'] ?? $privacy = $_SESSION['privacy'] ?? null;
  $numSlots = $_POST['numSlots'] ?? $numSlots = $_SESSION['numSlots'] ?? null;
  $notes = $_POST['notes'] ?? $notes = $_SESSION['notes'] ?? null;
  $date = $_POST['date'] ?? null;
  $time = $_POST['time'] ?? null;
  
  $errors = array();
  
  include 'includes/library.php';
  $pdo = connectDB();
  $query = "select * from `timeslot_users` WHERE username = ?";
  $stmt = $pdo->prepare($query);
  $stmt->execute([$creator]);
  $host = $stmt->fetch();
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
    if ($numSlots == "") {
      $errors['numSlots'] = true;
    }
      //validate user has entered a title
      if (!isset($date)) {
        $errors['date'] = true;
      }

      //validate user has entered a title
      if (!isset($time)) {
        $errors['time'] = true;
      }

      //only do this if there weren't any errors
      if (count($errors) === 0) {

        $numSlots = intval($numSlots);  //bill july 4th

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
          <form action="<?=htmlentities($_SERVER['PHP_SELF'])?>" method="POST" autocomplete="off">
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
                    <th>Who</th>
                  </tr>
                </thead>
                <tbody>
                  <?php for ($i = 1; $i <= $numSlots; $i++): ?>
                    <tr>
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
                    </tr>
                  <?php endfor ?>
                </tbody>
              </table>
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
