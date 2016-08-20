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
        $nameBlank = filter_input(INPUT_GET, 'u');
        $firstNameBlank = filter_input(INPUT_GET, 'f');
        $lastNameBlank = filter_input(INPUT_GET, 'l');
        $emailBlank = filter_input(INPUT_GET, 'e');
        $emailInvalid = filter_input(INPUT_GET, 'ei');
        $userPWBlank = filter_input(INPUT_GET, 'pw');
        $userPW2Blank = filter_input(INPUT_GET, 'pw2');
        $userPWTooShort = filter_input(INPUT_GET, 'pws');
        $nameFilled = filter_input(INPUT_GET, 'username');
        $firstNameFilled = filter_input(INPUT_GET, 'firstname');
        $lastNameFilled = filter_input(INPUT_GET, 'lastname');
        $emailFilled = filter_input(INPUT_GET, 'email');
            
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