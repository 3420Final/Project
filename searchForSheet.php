<?php
  $search = $_GET['search'] ?? null;
  if (isset($_GET['search'])){
    include 'includes/library.php';
    $pdo = connectDB();
    $query = "SELECT * FROM `timeslot_users` WHERE username like ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$search]);
    $host = $stmt->fetch();
    $query = "select * from `timeslot_sheets` WHERE name like ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$search]);
    $titleResults = $stmt->fetchAll();
    $query = "select * from `timeslot_sheets` WHERE description like ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$search]);
    $descriptionResults = $stmt->fetchAll();
    if ($host != false){
      $query = "select * from `timeslot_sheets` WHERE host = ?";
      $stmt = $pdo->prepare($query);
      $stmt->execute([$host["ID"]]);
      $hostResults = $stmt->fetchAll();
    }
    else{
      $hostResults = false;
    }
  }
  
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>View Sign-Up Sheet</title>
    <link rel ="stylesheet" href = "styles/master.css"/>
    <script src="https://kit.fontawesome.com/accfddd944.js" crossorigin="anonymous"></script>
  </head>
  <body>
    <section id = "signUpForSlot">
      <?php include 'includes/navbar.php';?>
      <header>
        <h1><i class="fas fa-search"></i> Find Sign-Up Sheet</h1>
      </header>
      <main>
        <nav>
          <ul>
            <li><a href="mySignups.php">Back</a></li>
          </ul>
        </nav>
        
        <section id = "FindSheet">
        <form id="searchbar" name="search"  action="<?=htmlentities($_SERVER['PHP_SELF'])?>" method="GET">
            <label><i aria-hidden="true" class="fas fa-search"></i></label>
            <input type='text' name="search" id="search" placeholder="Search for the Public Sign-Up Sheets By Creator username, Title, Description" value="<?=$search?>">
            <button id="submit" type="submit">Search</button>
          </form>
          <?php if(isset($_GET['search'])): ?>
            <?php if ($hostResults != false): ?>
            <?php foreach ($hostResults as $sheet): ?>
            <section>
              <div>
                <div>
                  <h2><?=$sheet["name"]?></h2>
                  <ul>
                  <li><?="<a href='signUpForSlot.php?id=".$sheet["ID"]."'><abbr title = 'Book Time Slot'><i class='fab fa-readme'></i></abbr></a>"?></li>
                  </ul>
                </div>
                <p><strong>Description: </strong><?=$sheet["description"]?></p>
                <p><strong>Number of Slots: </strong><?=$sheet["numslots"]?></p>
                <p><strong>Number of People Signed-Up: </strong><?=$sheet["numslotsfilled"]?></p>
              </div>
            </section>
            <?php endforeach ?> 
          <?php endif ?>
          <?php if ($titleResults != false): ?>
            <?php foreach ($titleResults as $sheet): ?>
            <section>
              <div>
                <div>
                  <h2><?=$sheet["name"]?></h2>
                  <ul>
                    <li><?="<a href='signUpForSlot.php?id=".$sheet["ID"]."'><abbr title = 'Book Time Slot'><i class='fab fa-readme'></i></abbr></a>"?></li>
                  </ul>
                </div>
                <p><strong>Description: </strong><?=$sheet["description"]?></p>
                <p><strong>Number of Slots: </strong><?=$sheet["numslots"]?></p>
                <p><strong>Number of People Signed-Up: </strong><?=$sheet["numslotsfilled"]?></p>
              </div>
            </section>
            <?php endforeach ?> 
          <?php endif ?>
          <?php if ($descriptionResults != false): ?>
            <?php foreach ($descriptionResults as $sheet): ?>
            <section>
              <div>
                <div>
                  <h2><?=$sheet["name"]?></h2>
                  <ul>
                    <li><?="<a href='signUpForSlot.php?id=".$sheet["ID"]."'><abbr title = 'Book Time Slot'><i class='fab fa-readme'></i></abbr></a>"?></li>
                  </ul>
                </div>
                <p><strong>Description: </strong><?=$sheet["description"]?></p>
                <p><strong>Number of Slots: </strong><?=$sheet["numslots"]?></p>
                <p><strong>Number of People Signed-Up: </strong><?=$sheet["numslotsfilled"]?></p>
              </div>
            </section>
            <?php endforeach ?> 
          <?php endif ?>
        <?php endif ?>
        </section>
      </main>
    </section>
  </body>
</html>