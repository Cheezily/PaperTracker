<?php

    //new user request flag
    if ($_POST['newUserRequest']) {
        $newUserRequested = TRUE;
    }
    
    //Handling the new user page after the user has selected a role
    if ($_POST['newAuthorRegister']) {
        $newAuthorRequested = TRUE;
        $newUserRequested = FALSE;
    }
    
    if ($_POST['newReviewerRegister']) {
        $newReviewerRequested = TRUE;
        $newUserRequested = FALSE;
    }
    
    //Error handling from the registration script
    if (isset($_GET['err'])) {
        //Get variables from the registration page if there is an error
        $newUserError = TRUE;
        $nameBlank = filter_input(INPUT_GET, 'u', FILTER_SANITIZE_STRIPPED);
        $firstNameBlank = filter_input(INPUT_GET, 'f', FILTER_SANITIZE_STRIPPED);
        $lastNameBlank = filter_input(INPUT_GET, 'l', FILTER_SANITIZE_STRIPPED);
        $affiliationBlank = filter_input(INPUT_GET, 'a', FILTER_SANITIZE_STRIPPED);
        $emailBlank = filter_input(INPUT_GET, 'e', FILTER_SANITIZE_STRIPPED);
        $emailInvalid = filter_input(INPUT_GET, 'ei', FILTER_SANITIZE_STRIPPED);
        $userPWBlank = filter_input(INPUT_GET, 'pw', FILTER_SANITIZE_STRIPPED);
        $userPW2Blank = filter_input(INPUT_GET, 'pw2', FILTER_SANITIZE_STRIPPED);
        $userPWTooShort = filter_input(INPUT_GET, 'pws', FILTER_SANITIZE_STRIPPED);
        $nameFilled = filter_input(INPUT_GET, 'username', FILTER_SANITIZE_STRIPPED);
        $firstNameFilled = filter_input(INPUT_GET, 'firstname', FILTER_SANITIZE_STRIPPED);
        $lastNameFilled = filter_input(INPUT_GET, 'lastname', FILTER_SANITIZE_STRIPPED);
        $affiliationFilled = filter_input(INPUT_GET, 'affiliation', FILTER_SANITIZE_STRIPPED);
        $emailFilled = filter_input(INPUT_GET, 'email', FILTER_SANITIZE_EMAIL);
            
        //gets the role from the 'r' parameter
        $errRole = filter_input(INPUT_GET, 'r');
        if ($errRole == 'a') {
            $errRole = 'Author';
        }
        if ($errRole == 'r') {
            $errRole = 'Reviewer';
        }
        $newUserErrorRole = 'Registration: New '.$errRole;

        if ($_GET['err'] == 'pwm') {
            $newUserError = 'Passwords do not match.';
            $userPWError = TRUE;
        }
        if ($_GET['err'] == 'u') {
            $newUserError = 'Username is taken';
            $nameError = TRUE;
        }
    }
?>