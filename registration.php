<?php
require_once 'model/registrationDB.php';

//this should be refactored at some point to not have to feed
//index.php any get parameters. That will require a seperate
//registrationSuccess.php or something else to forward to upon success

$role = filter_input(INPUT_POST, 'role');
$firstName = filter_input(INPUT_POST, 'firstName');
$lastName = filter_input(INPUT_POST, 'lastName');
$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
$username = filter_input(INPUT_POST, 'username');
$password = filter_input(INPUT_POST, 'password');
$passwordConfirm = filter_input(INPUT_POST, 'passwordConfirm');

//Get the role and store it as $r for GET params back to index.php if needed
if ($role == 'Author') {
    $r = 'a'; 
} elseif ($role == 'Reviewer') {
    $r = 'r';
} else {
    $r='ERROR. Refresh page';
}

//Check to see if any of the fields are blank or if the passwords are too short
$errorList = '';
$errorTrigger = FALSE;
if (!$firstName) {
    $errorList .= "&f=true";
    $errorTrigger = TRUE;
} else {
    $errorList .= "&firstname=".$firstName;
}

if (!$lastName) {
    $errorList .= "&l=true";
    $errorTrigger = TRUE;
} else {
    $errorList .= "&lastname=".$lastName;
}

if (strlen($_POST['email']) == 0) {
    $errorList .= "&e=true";
    $errorTrigger = TRUE;
} elseif (strlen($_POST['email']) > 0 && $email == FALSE) {
    //check to see if there was an email submitted and that it's invalid
    $errorList .= "&ei=true";
} else {
    $errorList .= "&email=".$email;
}

if (!$username) {
    $errorList .= "&u=true";
    $errorTrigger = TRUE;
} else {
    $errorList .= "&username=".$username;
}

if (!$password) {
    $errorList .= "&pw=true";
    $errorTrigger = TRUE;
}
if (!$passwordConfirm && $password && strlen($password) >= 8) {
    $errorList .= "&pw2=true";
    $errorTrigger = TRUE;
}

//Check to see if the password is too short
if ($password && strlen($password) < 8) {
    $errorList .= "&pws=true";
    $errorTrigger = TRUE;    
}

//Check to see if the username is taken. Function in model/registrationDB.php
if (checkUsername($username)) {
    header('Location: index.php?err=u'.$errorList.'&r='.$r);
    die();
}

if ($errorTrigger) {
    header("Location: index.php?err=b".$errorList."&r=".$r);
    die();
}

//Check if the passwords submitted match
if ($password != $passwordConfirm) {
    header('Location: index.php?err=pwm&r='.$r);
    die();
}

//If all of the above checks have passed, we add the user to the database
//and display a success page
$user = array(
    'username' => $username,
    'firstName' => $firstName,
    'lastName' => $lastName,
    'email' => $email,
    'password' => $password,
    'role' => $role
);
addUser($user);
$loginInfo = login($user['username'], $user['password']);

?>



<?php 
if (!empty($loginInfo)) {
    session_start();
    //var_dump($result);
    $_SESSION['username'] = $loginInfo['username'];
    $_SESSION['firstname'] = $loginInfo['first_name'];
    $_SESSION['lastname'] = $loginInfo['last_name'];
    $_SESSION['email'] = $loginInfo['email'];
    $_SESSION['role'] = $loginInfo['role'];
    $_SESSION['userID'] = $loginInfo['userID'];
    $_SESSION['username'] = $loginInfo['username'];
    $_SESSION['lastLogin'] = $loginInfo['last_login'];
    $_SESSION['firstLogin'] = TRUE;
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
            <h1>Thanks for registering!</h1>
            <h3>Click on the button below to be taken to your dashboard.</h3>
            <form method="post" action="index.php">
                <form method='get' action='index.php'>
                    <input class='registrationSuccess' type='submit' value='My Dashboard'>
                </form>
            </form>
        </div>
    </body>
</html>
<?php
    } else {
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
            <h1>Something went wrong with registering your username :(</h1>
            <h3>The administrator has been notified. You can click on the button below to try again if you wish. </h3>
            <form method="post" action="index.php">
                <form method='get' action='index.php'>
                    <input class='registrationSuccess' type='submit' value='Back to the Home Page'>
                </form>
            </form>
        </div>
    </body>
</html>
<?php
}
?>
