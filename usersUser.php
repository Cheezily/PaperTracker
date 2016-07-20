<?php

session_start();

if (!isset($_SESSION['username'])) {
    header("Location: index.php");
}

$hideAlert = FALSE;
if (isset($_POST['getStarted'])) {
    $hideAlert = TRUE;
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
        
        <?php 
            if ($_SESSION['firstLogin']) {
        ?>
        
            <?php if($hideAlert == FALSE) {?>
                <div class='newAuthorAlert'>
            <?php 
            } else {
            ?>
                <div class='hideAuthorAlert'>    
            <?php
            }
            ?>
        
            <h2>Hi there!</h2>
            <p>Welcome to your dashboard.  As an author, there won't be
                much here to look at -- just the status of the papers you've submitted 
                and a button to submit a new one.</p>
            <p>The possible states your paper could be in are:</p>
            <ul id='statusListAlert'>
                <li>Awaiting Assignment (to a reviewer)</li>
                <li>Awaiting Review</li>
                <li>Awaiting Revisions (from you)</li>
                <li>Rejected</li>
                <li>Accepted (you're done!)</li>
            </ul>
            <br>
            <p>Press the button below to get started and submit a paper for review.</p>
            <form method='post' action='usersUser.php'>
                <input type='submit' name='getStarted' class='getStartedButton' value='Get Started'> 
            </form>
            
        </div>

        <?php
            }
        ?>
        <div class='statusList'>
            
        </div>
        <?php if ($_SESSION['firstLogin'] && !$hideAlert) {?>
        <div class='mainWrapper fadeOut'>
        <?php
        } elseif ($_SESSION['firstLogin'] && $hideAlert) {
            $hideAlert = FALSE;
            $_SESSION['firstLogin'] = FALSE;
        ?>
        <div class='mainWrapper fadeIn'>    
        <?php
        } else {
        ?>
        <div class='mainWrapper'>
        <?php
        }
        ?>
            <h1>Hello, <?php echo $_SESSION['firstname']; ?>!  Welcome to the AUTHOR page!</h1>
        </div>
    </body>
</html>

<?php 

?>