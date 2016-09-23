<?php

if (!isset($adminPage)) {
    switch ($_POST['adminPage']) {
        case "users":
            $adminPage = "adminUsers.php";
            break;
        case "settings":
            $adminPage = "adminSettings.php";
            break;
        case "papers":
            $adminPage = "adminPapers.php";
            break;
        case "messages":
            $adminPage = "adminMessages.php";
            break;
        default:
            $adminPage = "adminSummary.php";
    }  
}


?>

<nav class="adminNav">
    <form 
        <?php if ($adminPage === "adminSummary.php") {
            echo " class='activePage' ";
        } ?>
        method="post" action="index.php">
        <input type="hidden" name="adminPage" value="summary">
        <input class='navButton' type="submit" value="Summary">
    </form>

    <form 
        <?php if ($adminPage === "adminPapers.php") {
            echo " class='activePage' ";
        } ?>    
        method="post" action="index.php">
        <input type="hidden" name="adminPage" value="papers">
        <input class='navButton' type="submit" value="Papers">
    </form>

    <form 
        <?php if ($adminPage === "adminUsers.php") {
            echo " class='activePage' ";
        } ?>    
        method="post" action="index.php">
        <input type="hidden" name="adminPage" value="users">
        <input class='navButton' type="submit" value="Users">
    </form>

    <form 
        <?php if ($adminPage === "adminMessages.php") {
            echo " class='activePage' ";
        } ?>        
        method="post" action="index.php">
        <input type="hidden" name="adminPage" value="messages">
        <input class='navButton' type="submit" value="Messages">
    </form>
    <form 
        <?php if ($adminPage === "adminSettings.php") {
            echo " class='activePage' ";
        } ?>
        method="post" action="index.php">
        <input type="hidden" name="adminPage" value="settings">
        <input class='navButton' type="submit" value="Settings">
    </form>
</nav>
