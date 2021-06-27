<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Create Sign-Up Sheet</title>
    <link rel ="stylesheet" href = "styles/master.css"/>
    <script src="https://kit.fontawesome.com/accfddd944.js" crossorigin="anonymous"></script>
  </head>
  <body>
    <section id = "signUpSheet">
      <?php include 'includes/navbar.php';?>
      <header>
        <h1>New Sign-Up Sheet</h1>
      </header>
      <main>
        <nav id='sidebar'>
          <ul>
            <li><a href="mySignups.php">Back</a></li>
          </ul>
        </nav>
        <section>
          <h2>Sign-Up Sheet Details</h2>
          <form id="requestform" action="SignUpSheet.php" method="post">
            <div>
              <label for="title">Sign-Up Sheet Title</label>
              <input id="title" name="title" type="text" placeholder="Project Check-In #1" />
            </div>
            <div>
              <label for="description">Sign-Up Sheet Description</label>
              <textarea name="description" id="description" cols="30" rows="10"></textarea>
            </div>
            <div>
              <label for="slots">Number of slots</label>
              <select name="primary" id="primary">
                <option value="">Choose One</option>
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
                <option value="6">6</option>
                <option value="7">7</option>
                <option value="8">8</option>
                <option value="9">9</option>
                <option value="10">10</option>
                <option value="o">Other</option>
              </select>
            </div>
            <fieldset>
              <legend>Privacy</legend>
              <div>
                <input id="public" name="status" type="radio" value="O" />
                <label for="public">Public</label>
              </div>

              <div>
                <input id="private" name="status" type="radio" value="C" />
                <label for="private">Private</label>
              </div>
            </fieldset>
            <div>
              <button id="submit"><a href="SheetThanks.php">Create Sheet</a></button>
            </div>
          </form>
        </section>
      </main>
    </section>
  </body>
</html>
