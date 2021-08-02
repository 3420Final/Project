<?php 
  session_start();
  if (isset($_SESSION['username'])){
    $user = $_SESSION['username'];
    $query = "select * from timeslot_users WHERE username = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$user]);
    $userDetails = $stmt->fetch();
  }

  $book = "book";
  
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
  <body>
    <section id = "signUpSheet">
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
                        <td>
                          <div><button type="submit" name="submitUser"><?="<a href='signUpForSlot.php?id=".$sheet["ID"]."&slotID=".$slot["ID"]."&action=".$book."'>Book Time Slot</a>"?></button></div>
                        </td>
                      <?php else: ?>
                        <td><div><button type="submit" name="submit"><?="<a href='bookSlotNonUsers.php?slotID=".$slot["ID"]."&sheetID=".$sheet["ID"]."'>Book Time Slot</a>"?></button></div></td>
                      <?php endif ?>
                    <?php else: ?>
                      <td>
                        <?php
                          $query = "select * from 'timeslot_users' WHERE ID= ?";
                          $stmt = $pdo->prepare($query);
                          $stmt->execute([$slot["userID"]]);
                          $slotParticipant = $stmt->fetch();

                          echo "$slotParticipant[username]";
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
    </section>
  </body>
</html>