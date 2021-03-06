<?php 
  include 'includes/library.php';
  $pdo = connectDB();

  $query = "UPDATE timeslot_slots SET userID = NULL WHERE ID = ?";
  $stmt = $pdo->prepare($query);
  $stmt->execute([$_GET["id"]]);
?>
<!DOCTYPE html>
<html lang="en">
  <head>
      <meta charset="utf-8" />
      <meta name="viewport" content="width=device-width, initial-scale=1.0" />
      <title>Done!</title>
      <link rel="stylesheet" href="styles/master.css" />
  </head>
  <body id="mySignUps">
      <?php include 'includes/navbar.php';?>
        <header>
          <h1><i class="fas fa-clipboard-check"></i> Time Slot Cancelled!</h1>
        </header>
        <main>
          <nav>
            <ul>
              <li><a href="mySignups.php">Home</a></li>
            </ul>
          </nav>
          <section>
            <h3>This time slot has now been cancelled!</h3>
          </section>
      </main>
  </body>
</html>