<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Edit Profile</title>
    <link rel ="stylesheet" href = "styles/ProfilePage.css"/>
    <script src="https://kit.fontawesome.com/accfddd944.js" crossorigin="anonymous"></script>
  </head>
  <body>
    <?php include 'includes/navbar.php';?>
    <header>
    <h1><i class="fas fa-user"></i> My Profile</h1>
  </header>
  <main>
    <nav id='sidebar'>
      <ul>
        <li><a href="mySignups.php">Back</a></li>
        <li><a href="editAccount.php">Edit Profile</a></li>
      </ul>
    </nav>
      <div>
        <img src="images/Profile.JPG" alt="Profile Image Icon" width="350" height="350" /> 
      </div>
    <form id="newuser" name="newuser" action="results.php" method="post">
        <div>
            <label for="name">Name </label>
            <input type="text" id="name" name="name" pattern="[A-Za-z-0-9]+\s[A-Za-z-'0-9]+"
              title="firstname lastname" autocorrect="off" value = "Jamie Le Neve" required/>
          </div>
          <div>
            <label for="email">Email </label>
            <input type="email" name="Email" id="email" value="jamieleneve@trentu.ca" />
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
        <input type="password" name="password" id="passwd"/>
      </div>

      <div><button type="submit" name="submit">Submit</button></div>
    </form>
    </main>
  </body>
</html>