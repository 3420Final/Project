<?php

//check if user is logged in, present with proper text
session_start();
$login = 'Login';
$loginlink = 'login.php';
$homelink = 'login.php';
$profilelink = 'login.php';

if (isset($_SESSION['username']))
{
    $login = 'Logout';
    $loginlink = 'thanks.php';   //takes you to the thanks page
    $homelink = 'mySignups.php';
    $profilelink = 'profile.php';
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
                <li><a href="<?php echo $homelink ?>">Home</a></li>
                <li><a href="<?php echo $profilelink ?>">View Profile</a></li>
                <li><a href="<?php echo $loginlink ?>"><?php echo $login ?></a></li>
            </ul>
            <form id="searchbar" name="search">
                <i aria-hidden="true" class="fas fa-search"></i>
                <input type='text' name="search" id="search">
                <input id="submit" type="submit" value="Go">
            </form>
        </div>
    </div>
</nav>
