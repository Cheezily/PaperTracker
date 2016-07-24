<?php
    //session_start();
?>

<link rel="stylesheet" href="css/main.css">

<header>
    <?php 
        if (isset($_SESSION['firstname'])) {
            echo "<form class='logoutForm' method='post' action='index.php'>";
            echo "<input type='submit' class='logoutButton' name='logout' value='Logout' id='logout'>";
            echo "</form>";
            echo "<span class='greeting'>Hello, ".$_SESSION['firstname']."</span>";
        } elseif (isset($_GET['q']) && $_GET['q'] == 'recovery') {
            echo "<div class=loginButtons>";
            echo "Password Recovery";
            echo "</div>";
        } else {
            echo "<div class='loginButtons'>";
            echo "<form method='post' action='index.php' class='headerFrom'>";
            echo "<input type='submit' class='loginButton' id='newUser' name='newUserRequest' value='New User'>";
            echo "</form>";
            echo " or ";
            echo "<form method='post' action='index.php' class='headerFrom'>";
            echo "<input type='submit' class='loginButton' id='login' name='loginRequest' value='Login'>";
            echo "</form>";
            echo "</div>";
        }
    ?>
</header>

