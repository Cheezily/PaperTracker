<?php
require_once 'model/database.php';

$role = filter_input(INPUT_POST, 'role');
$first_name = filter_input(INPUT_POST, 'firstName');
$last_Name = filter_input(INPUT_POST, 'lastName');
$email = filter_input(INPUT_POST, 'email');
$username = filter_input(INPUT_POST, 'username');
$password = filter_input(INPUT_POST, 'password');
$password_confirm = filter_input(INPUT_POST, 'passwordConfirm');

if ($password != $password_confirm) {
    $newUserError = 'Passwords do not match.';
    $passwordError = TRUE;
    $newUserErrorRole = 'Registration: New '.$role;
    if ($role == 'Author') {
        $r = 'a';
    } elseif ($role == 'Reviewer') {
        $r = 'r';
    } else {
        //if the passwords don't match and there is no role,
        //the passwords are the least of the concerns
        header('Location: index.php');
    }
    
    header('Location: index.php?err=pwm&r='.$r);
}
?>