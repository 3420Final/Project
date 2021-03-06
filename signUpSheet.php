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

