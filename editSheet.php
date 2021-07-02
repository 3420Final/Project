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
  $title = $_POST['title'] ?? $sheet["name"];
  $description = $_POST['description'] ?? $sheet["description"];
  $location = $_POST['location'] ?? $slotInfo["location"];
  $privacy = $_POST['status'] ?? $sheet["privacy"];
  $numSlots = $_POST['numSlots'] ?? $sheet["numslots"];
  $notes = $_POST['notes'] ?? $slotInfo["notes"];

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
        $query = "UPDATE timeslot_sheets SET numslots = ?, name = ?, description = ?, privacy = ?, host = ?";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$numSlots, $title, $description, $privacy, $host["ID"]]);

        $query = "SELECT * FROM `timeslot_sheets` WHERE numslots = ? AND name = ? AND description = ? AND privacy = ?";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$numSlots, $title, $description, $privacy]);
        $sheetID = $stmt->fetch();
        for ($i = 0; $i < $numSlots; $i ++){
          $query = "UPDATE timeslot_slots SET date = ?, time = ?, location = ?, notes =?";
          $stmt = $pdo->prepare($query);
          $stmt->execute([$date, $time, $location, $notes]);
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
    <title>Edit Sign-Up Sheet</title>
    <link rel ="stylesheet" href = "styles/master.css"/>
    <script src="https://kit.fontawesome.com/accfddd944.js" crossorigin="anonymous"></script>
  </head>
  <body>
    <section id = "signUpSheet">
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
          <form id="requestform" action="EditSheet.php" method="post">
            <div>
              <label for="title">Sign-Up Sheet Title</label>
              <input id="title" name="title" type="text" placeholder="Project Check-In #1" />
            </div>
            <div>
              <label for="description">Sign-Up Sheet Description</label>
              <textarea name="description" id="description" cols="30" rows="10" placeholder="Your overall site design, HTML forms and corresponding CSS styling on all pages"></textarea>
            </div>
            <div>
              <label for="slots">Number of slots</label>
              <select name="primary" id="primary">
                <option value="">Choose One</option>
                <option value="1">1</option>
                <option value="2" selected>2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
                <option value="6">6</option>
                <option value="7">7</option>
                <option value="8">8</option>
                <option value="9">9</option>
                <option value="10">10</option>
                <option value="o">Other</option>
              </select>
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
                  <tr>
                    <td>Project Check-In #1</td>
                    <td>Tue, Jun 15 @ 3:20 PM</td>
                    <td>Jamie Le Neve</td>
                  </tr>
                  <tr>
                    <td>Project Check-In #1</td>
                    <td>Tue, Jun 15 @ 3:30 PM</td>
                    <td>Bill Van Leeuwan</td>
                  </tr>
                </tbody>
              </table>
            </div>
            <fieldset>
              <legend>Privacy</legend>
              <div>
                <input id="public" name="status" type="radio" value="O" />
                <label for="public">Public</label>
              </div>

              <div>
                <input id="private" name="status" type="radio" value="C" checked/>
                <label for="private">Private</label>
              </div>
            </fieldset>
            <div>
              <button id="submit">Update Sheet</button>
            </div>
          </form>
        </section>
      </main>
    </section>
  </body>
</html>
