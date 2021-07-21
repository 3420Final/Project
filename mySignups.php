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

  $query = "select * from timeslot_slots WHERE userID = ?";
  $stmt = $pdo->prepare($query);
  $stmt->execute([$host["ID"]]);
  $slots = $stmt->fetchAll();

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
  <body>
    <section id = "mySignUps">
      <?php include 'includes/navbar.php';?>
      <header>
          <img src="images/checklist.png" alt="pencil on a clipboard" />
          <h1>My Sign Ups</h1>
        </header>
        <main>
          <nav>
            <ul>
              <li><a href="calender.php">View Calender</a></li>
            </ul>
          </nav>
          <section class = "Sign-upSheets">
            <section class = "h2">
              <h2>My Sign-up Sheets </h2>
              <a href="signUpSheet.php"><abbr title = "Create Sign-up Sheet"><i class="fas fa-plus-square"></i></abbr></a>
            </section>
            <?php foreach ($sheets as $sheet): ?>
              <div>
                  <div>
                      <h3><?=$sheet["name"]?></h3>
                      <ul>
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
              <a href="SignUpSheet.php"><abbr title = "Create Sign-up Sheet"><i class="fas fa-plus-square"></i></abbr></a>
            </section>
            <?php foreach ($slots as $slot): ?>
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
                    <ul>
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
    </section>
  </body>
</html>