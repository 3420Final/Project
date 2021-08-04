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
