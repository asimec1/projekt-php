<?php
print '
<nav class="main-nav">
    <button class="nav-toggle" type="button" aria-label="Otvori izbornik" aria-expanded="false" aria-controls="main-menu">
        <span></span>
        <span></span>
        <span></span>
    </button>

    <ul id="main-menu" class="main-menu">
        <li><a href="index.php?menu=1">Home</a></li>
        <li><a href="index.php?menu=2">News</a></li>
        <li><a href="index.php?menu=3">Contact</a></li>
        <li><a href="index.php?menu=4">About</a></li>
        <li><a href="index.php?menu=20">Gallery</a></li>';

        if (!isset($_SESSION['user']['valid']) || $_SESSION['user']['valid'] == 'false') {
            print '
            <li><a href="index.php?menu=5">Register</a></li>
            <li><a href="index.php?menu=6">Sign In</a></li>';
        }
        else if ($_SESSION['user']['valid'] == 'true') {
            print '
            <li><a href="index.php?menu=7">Admin</a></li>
            <li><a href="signout.php">Sign Out</a></li>';
        }

        print '
    </ul>
</nav>';
?>