<?php
    //Login request flag
    if ($_POST['loginRequest'] && !isset($_SESSION['username'])) {
        $newLoginRequest = TRUE;
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
            require_once 'model/usersDB.php';
            
            $result = login($name, $userPW);
            if (!empty($result)) {
                //var_dump($result);
                $_SESSION['username'] = $result[0]['username'];
                $_SESSION['firstname'] = $result[0]['first_name'];
                $_SESSION['lastname'] = $result[0]['last_name'];
                $_SESSION['email'] = $result[0]['email'];
                $_SESSION['role'] = $result[0]['role'];
                $_SESSION['userID'] = $result[0]['userID'];
                $_SESSION['username'] = $result[0]['username'];
                $_SESSION['lastLogin'] = $result[0]['last_login'];
                $_SESSION['previousLogin'] = $result[1][0];
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
?>