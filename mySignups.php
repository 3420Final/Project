<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>User Home</title>
    <link rel ="stylesheet" href = "styles/mySignups.css"/>
    <script src="https://kit.fontawesome.com/accfddd944.js" crossorigin="anonymous"></script>
  </head>
  <body>
    <?php include 'includes/navbar.php';?>
    <header>
        <img src="images/checklist.png" alt="pencil on a clipboard" />
        <h1>My Sign Ups</h1>
      </header>
      <main>
        <nav>
          <ul>
            <li><a href="signUpForSlot.php">Find a Sheet</a></li>
          </ul>
        </nav>
        <section class = "Sign-upSheets">
          <section class = "h2">
            <h2>My Sign-up Sheets </h2>
            <a href="signUpSheet.php"><abbr title = "Create Sign-up Sheet"><i class="fas fa-plus-square"></i></abbr></a>
          </section>
            <div>
                <div>
                    <h3>Sign-up Sheet 1</h3>
                    <ul>
                      <li><a href="viewSheet.php"><abbr title = "View Sign-up Sheet"><i class="fab fa-readme"></i></abbr></a></li>
                      <li><a href="editSheet.php"><abbr title = "Edit Sign-up Sheet"><i class="far fa-edit"></i></abbr></a></li>
                      <li><a href="copySheet.php"><abbr title = "Copy Sign-up Sheet"><i class="far fa-copy"></i></abbr></a></li>
                      <li><a href="deleteSheet.php"><abbr title = "Delete Sign-up Sheet"><i class="fas fa-trash-alt"></i></abbr></a></li>
                    </ul>
                 </div>
                <p><strong>Number of Slots: </strong>5</p>
                <p><strong>Number of People Signed-Up</strong> 3</p>
            </div>
            <div>
                <div>
                    <h3>Sign-up Sheet 2</h3>
                    <ul>
                      <li><a href="viewSheet.php"><abbr title = "View Sign-up Sheet"><i class="fab fa-readme"></i></abbr></a></li>
                      <li><a href="editSheet.php"><abbr title = "Edit Sign-up Sheet"><i class="far fa-edit"></i></abbr></a></li>
                      <li><a href="copySheet.php"><abbr title = "Copy Sign-up Sheet"><i class="far fa-copy"></i></abbr></a></li>
                      <li><a href="deleteSheet.php"><abbr title = "Delete Sign-up Sheet"><i class="fas fa-trash-alt"></i></abbr></a></li>
                    </ul>
                 </div>
              <p><strong>Number of Slots: </strong>25</p>
              <p><strong>Number of People Signed-Up</strong> 20</p>
          </div>
             <div>
                 <div>
                    <h3>Sign-up Sheet 3</h3>
                    <ul>
                      <li><a href="viewSheet.php"><abbr title = "View Sign-up Sheet"><i class="fab fa-readme"></i></abbr></a></li>
                      <li><a href="editSheet.php"><abbr title = "Edit Sign-up Sheet"><i class="far fa-edit"></i></abbr></a></li>
                      <li><a href="copySheet.php"><abbr title = "Copy Sign-up Sheet"><i class="far fa-copy"></i></abbr></a></li>
                      <li><a href="deleteSheet.php"><abbr title = "Delete Sign-up Sheet"><i class="fas fa-trash-alt"></i></abbr></a></li>
                    </ul>
                 </div>
                <p><strong>Number of Slots: </strong>10</p>
                <p><strong>Number of People Signed-Up</strong> 7</p>
            </div>   
        </section>
        <section class = "Slots">
          <section>
            <h2>My Time Slots</h2>
            <a href="SignUpSheet.php"><abbr title = "Create Sign-up Sheet"><i class="fas fa-plus-square"></i></abbr></a>
          </section>
            <div>
                <div>
                    <h3>Time Slot 1</h3>
                    <ul>
                        <li><a href="viewTimeSlot.php"><abbr title = "View Time Slot"><i class="fab fa-readme"></i></abbr></a></li>
                        <li><a href="deleteTimeSlot.php"><abbr title = "Delete Time Slot"><i class="fas fa-trash-alt"></i></abbr></a></li>
                    </ul>
                </div>
                <p><strong>Date: </strong>June 15, 2021</p>
                <p><strong>Time: </strong>3:20 PM</p>
                <p><strong>Location: </strong>Remote via Zoom</p>
            </div>
            <div>
                <div>
                    <h3>Time Slot 2</h3>
                    <ul>
                        <li><a href="viewTimeSlot.php"><abbr title = "View Time Slot"><i class="fab fa-readme"></i></abbr></a></li>
                        <li><a href="deleteTimeSlot.php"><abbr title = "Delete Time Slot"><i class="fas fa-trash-alt"></i></abbr></a></li>
                    </ul>
                </div>
              <p><strong>Date: </strong>June 15, 2021</p>
              <p><strong>Time: </strong>3:30 PM</p>
              <p><strong>Location: </strong>Remote via Zoom</p>
          </div>
          <div>
            <div>
                <h3>Time Slot 3</h3>
                <ul>
                    <li><a href="viewTimeSlot.php"><abbr title = "View Time Slot"><i class="fab fa-readme"></i></abbr></a></li>
                    <li><a href="deleteTimeSlot.php"><abbr title = "Delete Time Slot"><i class="fas fa-trash-alt"></i></abbr></a></li>
                </ul>
            </div>
              <p><strong>Date: </strong>June 15, 2021</p>
              <p><strong>Time: </strong>3:40 PM</p>
              <p><strong>Location: </strong>Remote via Zoom</p>
          </div>
        </section>
    </main>
  </body>
</html>