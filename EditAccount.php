<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Edit Profile</title>
    <link rel ="stylesheet" href = "styles/master.css"/>
    <script src="https://kit.fontawesome.com/accfddd944.js" crossorigin="anonymous"></script>
  </head>
  <body>
    <?php include 'includes/navbar.php';?>
    <header>
    <h1><i class="fas fa-user-edit"></i> Edit Profile</h1>
  </header>
  <main>
    <nav id='sidebar'>
      <ul>
        <li><a href="mySignups.php">Back</a></li>
      </ul>
    </nav>
    <form id="uploadform" action="fileupload.php" method="post" enctype="multipart/form-data">
        <div>
            <img src="images/profileImage.png" alt="Profile Image Icon" width="350" height="350" />
          <!--this is require to restrict size of file upload in php-->
          <input type="hidden" name="MAX_FILE_SIZE" value="12400" />
          <label for="imgupload">Upload Profile Image:</label>
          <input type="file" name="imgupload" id="imgupload" />
        </div>
        <input type="submit" name="submit" value="Finished" />
      </form>
    <form id="newuser" name="newuser" action="results.php" method="post">
      
        <div>
            <label for="name">Name </label>
            <input type="text" id="name" name="name" pattern="[A-Za-z-0-9]+\s[A-Za-z-'0-9]+"
              title="firstname lastname" autocorrect="off" placeholder = "Jamie Le Neve" required/>
          </div>
          <div>
            <label for="email">Email </label>
            <input type="email" name="Email" id="email" placeholder="jamieleneve@trentu.ca" />
          </div>

      <fieldset>
        <legend>Gender</legend>
          <div>
            <input type="radio" name="gender" id="male" value="m" />
            <label for="male">Male</label>
          </div>
          <div>
            <input type="radio" name="gender" id="female" value="f" checked/>
            <label for="female">Female</label>
          </div>
          <div>
            <input type="radio" name="gender" id="gnc" value="gnc"/>
            <label for="gnc">Gender Queer/Non-Conforming</label>
          </div>
        <div>
            <input type="radio" name="gender" id="notsay" value="notsay"/>
            <label for="notsay">Prefer not to say</label>
         </div>
        </fieldset>

      <div>
        <label for="username">Username </label>
        <input type="text" name="username" id="username" />
      </div>

      <div>
        <label for="passwd">Password </label>
        <input type="password" name="password" id="passwd" />
      </div>

      <div><button type="submit" name="submit">Submit</button></div>
    </form>
    </main>
  </body>
</html>