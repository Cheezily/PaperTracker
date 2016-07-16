<?php
require_once 'model/registrationDB.php';

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
if (check_username($username)) {
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





?>