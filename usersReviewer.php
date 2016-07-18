<?php

session_start();

if (!isset($_SESSION['username'])) {
    header("Location: index.php");
}

?>

<!DOCTYPE html>
<html>
    <head>
        <title>
            Submission Tracker
        </title>
        <script type='text/javascript' src='bower_components/jquery/dist/jquery.min.js'></script>
    </head>
    
    <body>
        <?php include 'static/header.php';?>

        <div class='mainWrapper'>
            <h1>Hello, <?php echo $_SESSION['firstname']; ?>!  Welcome to the REVIEWER page!</h1>
        </div>
    </body>
    
    
</html>