<?php 
  include 'includes/library.php';
  $pdo = connectDB();

  $query = "delete from timeslot_slots where sheetID = ?";
  $stmt = $pdo->prepare($query);
  $stmt->execute([$_GET["id"]]);

  $query = "delete from timeslot_sheets where ID = ?";
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
          <h1><i class="fas fa-clipboard-check"></i> Sheet Deleted!</h1>
        </header>
        <main>
          <nav>
            <ul>
              <li><a href="mySignups.php">Home</a></li>
            </ul>
          </nav>
          <section>
            <h3>This sheet has now been deleted!</h3>
          </section>
      </main>
  </body>
</html>
