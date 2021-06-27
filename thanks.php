<?php
    session_start();
    session_destroy();
    header('Location:index.php');
    exit();

    //the html below wont actually show
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://kit.fontawesome.com/c2cee199ac.js" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="styles/mainpage.css" />
  <title>Thanks</title>
</head>
<body>
  <section id="mainspace">
    <div class="container">
      <!--Citation for the image below: https://www.flexjobs.com/blog/post/essential-time-management-skills/-->
      <img src="images/MainpageImage.jpg" alt="computer with hour glass filled with blue sand" style = "width:100%" />
      <div class="content">
        <h1>Thanks!</h1>
        <p>Taking you back to the main page.</p>
      </div>
    </div>
    </div>
  </section>
</body>
</html>
