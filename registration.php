<?php
require_once 'model/usersDB.php';

//this should be refactored at some point to not have to feed
//index.php any get parameters. That will require a seperate
//registrationSuccess.php or something else to forward to upon success

$role = trim(filter_input(INPUT_POST, 'role', FILTER_SANITIZE_STRIPPED));
$firstName = trim(filter_input(INPUT_POST, 'firstName', FILTER_SANITIZE_STRIPPED));
$lastName = trim(filter_input(INPUT_POST, 'lastName', FILTER_SANITIZE_STRIPPED));
$affiliation = trim(filter_input(INPUT_POST, 'affiliation', FILTER_SANITIZE_STRIPPED));
$email = trim(filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL));
$username = trim(filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRIPPED));
$password = trim(filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRIPPED));
$passwordConfirm = trim(filter_input(INPUT_POST, 'passwordConfirm', FILTER_SANITIZE_STRIPPED));

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
    $errorList .= "&firstname=".ucfirst($firstName);
}

if (!$lastName) {
    $errorList .= "&l=true";
    $errorTrigger = TRUE;
} else {
    $errorList .= "&lastname=".ucfirst($lastName);
}

if (!$affiliation) {
    $errorList .= "&a=true";
    $errorTrigger = TRUE;
} else {
    $errorList .= "&affiliation=".$affiliation;
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
    'firstName' => ucfirst($firstName),
    'lastName' => ucfirst($lastName),
    'affiliation' => $affiliation,
    'email' => $email,
    'password' => $password,
    'role' => $role
);
addUser($user);

$loginInfo = login($user['username'], $user['password']);

if (!empty($loginInfo)) {
    session_start();
    var_dump($loginInfo);
    $_SESSION['username'] = $loginInfo[0]['username'];
    $_SESSION['firstname'] = $loginInfo[0]['first_name'];
    $_SESSION['lastname'] = $loginInfo[0]['last_name'];
    $_SESSION['email'] = $loginInfo[0]['email'];
    $_SESSION['role'] = $loginInfo[0]['role'];
    $_SESSION['userID'] = $loginInfo[0]['userID'];
    $_SESSION['username'] = $loginInfo[0]['username'];
    $_SESSION['lastLogin'] = $loginInfo[1];
    $_SESSION['firstLogin'] = TRUE;

    header("Location: index.php");
}
?>