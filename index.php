<?php
    error_reporting(E_ALL &  ~E_NOTICE);
    session_start();
    
    //CSS transitions are not supported in IE 8 or 9
    if(preg_match('/(?i)msie [5-9]/',$_SERVER['HTTP_USER_AGENT'])) {
        echo "<h1>Please upgrade to a modern browser like Chrome, Firefox, Edge, or Internet Explorer version 10+</h1>";
        die();
    }
    
    //var_dump($_SERVER);
    //Login request flag
    if ($_POST['loginRequest']) {
        $newLoginRequest = TRUE;
    }
    
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

    //handles logout requests
    if ($_POST['logout'] == 'Logout') {
        $_SESSION = array();
        session_destroy();
        //header("Location: index.php");
    }
    
    //error handling from the login form on this page
    if ($_POST['from_login_form'] && !$_POST['forgot_PW']) {
        $name = filter_input(INPUT_POST, 'username');
        $userPW = filter_input(INPUT_POST, 'password');
        
        if (!$name && !$userPW) {
            $loginError = "Please enter a username and password.";
        } elseif ($name && !$userPW) {
            $loginError = "Please enter a password.";
        } elseif (!$name && $userPW) {
            $loginError = "No username entered.";
        } else {
            //checks to see if the username and password matches.
            require_once 'model/registrationDB.php';
            
            $result = login($name, $userPW);
            if (!empty($result)) {
                //var_dump($result);
                $_SESSION['username'] = $result['username'];
                $_SESSION['firstname'] = $result['first_name'];
                $_SESSION['lastname'] = $result['last_name'];
                $_SESSION['email'] = $result['email'];
                $_SESSION['role'] = $result['role'];
                $_SESSION['userID'] = $result['userID'];
                $_SESSION['username'] = $result['username'];
                $_SESSION['lastLogin'] = $result['last_login'];
                $_SESSION['firstLogin'] = TRUE;

            } else {
                $loginError = "Invalid username or password";
            }
        }
        
    } elseif ($_POST['from_login_form'] && $_POST['forgot_PW']) {
        //header("Location: forgotPW.php?q=recovery");
        $forgotPW = TRUE;
    } else {
        $loginError = NULL;
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
    
    //Handle routing if the session is set
    if ($_SESSION['role'] == 'author') {
        //header("Location: authorDashboard.php");
        $role = "author";
    }
    if ($_SESSION['role'] == 'reviewer') {
        //header("Location: reviewerDashboard.php");
        $role = "reviewer";
    }
    if ($_SESSION['role'] == 'admin') {
        //header("Location: adminDashboard.php");
        $role = "admin";
    }

    //Handle when an author clicks to submit a new paper from the dashboard
    if (isset($_POST['newPaper'])) {
        $newPaper = TRUE;
    }
    
    //Handle when an author clicks to cancel new paper submission from the newPaper.php dialog
    if (isset($_POST['paperSubmitCancel'])) {
        $newPaper = TRUE;
        $newPaperCancel = TRUE;
    }
    
    //Handle when the author submits a new paper from the newPaper.php dialog
    if (isset($_POST['paperSubmit'])) {
        $title = filter_input(INPUT_POST, 'paperTitle');
        if ($title == FALSE) {
            $newPaperError = "Please enter a paper title";
        }
        $newPaper = TRUE;
        //var_dump($_FILES);
        $doc_dir = getcwd().'/uploads/';
        $doc_file  = $doc_dir.$_SESSION['userID']."-".basename($_FILES["paperFile"]["name"]);
        $filetype = pathinfo($doc_file, PATHINFO_EXTENSION);
        //echo $filetype;
        if ($_FILES["paperFile"]["size"] > 10000000) {
            $newPaperError = "Filesize must be less than 10MB";
        }
        if ($filetype != "doc" && $filetype != "docx") {
            $newPaperError = "File must be a MS Word file";
        }
        if (file_exists($doc_file)) {
            $newPaperError = "File already exists";
        }
        if (!isset($newPaperError)) {
            if (move_uploaded_file($_FILES["paperFile"]["tmp_name"], $doc_file)) {
                require_once 'model/papersDB.php';
                uploadPaper($_SESSION['username'], basename($_FILES["paperFile"]["name"]), $title);
                $newPaperSubmitted = TRUE;
            } else {
                $newPaperError = "Something went wrong. Please try again.";
            }
        }
        
    }
    //***********************************
    //-------------ROUTING---------------
    //***********************************
    if (empty($role)) {
        include "greeting.php";
    }
    
    if ($role == 'author') {
        include "authorDashboard.php";
    }
    
    if ($role == 'reviewer') {
        include "reviewerDashboard.php";
    }
    
    if ($role == 'admin') {
        include "adminDashboard.php";
    }
    
?>