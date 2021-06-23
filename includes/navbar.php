<?php

//check if user is logged in, present with proper text
session_start();
$login = 'Login';
$loginlink = 'login.php';
if (isset($_SESSION['username']))
{
    $login = 'Logout';
    $loginlink = 'index.php';   //takes you to the homepage to get out
}

?>
<nav id='navbar'>
    <div>
        <div>
            <i class="far fa-clock"></i>
            <h2 class='title'>Bill and Jamie's Time Slot Manager</h2>
        </div>
        <div>
            <ul>
                <li><a href="mySignups.php">Home</a></li>
                <li><a href="profile.php">View Profile</a></li>
                <li><a href=<?php echo $loginlink ?>><?php echo $login ?></a></li>
            </ul>
            <form id="searchbar" name="search">
                <i aria-hidden="true" class="fas fa-search"></i>
                <input type='text' name="search" id="search">
                <input id="submit" type="submit" value="Go">
            </form>
        </div>
    </div>
</nav>