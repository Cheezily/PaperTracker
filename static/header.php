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
            echo "<div class=loginButtons>";
            echo "<button class='loginButton' id='newUser'>New User</button>";
            echo " or ";
            echo "<button class='loginButton' id='login'>Login</button>";
            echo "</div>";
        }
    ?>
</header>

