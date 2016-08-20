<?php
    error_reporting(E_ALL &  ~E_NOTICE);
    session_start();
    
    date_default_timezone_set ("America/Chicago");
    
    //CSS transitions are not supported in IE 8 or 9
    if(preg_match('/(?i)msie [5-9]/',$_SERVER['HTTP_USER_AGENT'])) {
        echo "<h1>Please upgrade to a modern browser like Chrome,".
                " Firefox, Edge, or Internet Explorer version 10+</h1>";
        die();
    }

    require_once 'controller/messages.php';
    require_once 'controller/login.php';
    require_once 'controller/newUser.php';
    require_once 'controller/newPaper.php';
    
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



        

    //***********************************
    //-------------ROUTING---------------
    //***********************************
    if (empty($role)) {
        include "greeting.php";
    }

    if ($_SESSION['role'] == 'author') {
        include "authorDashboard.php";
    }
    
    if ($_SESSION['role'] == 'reviewer') {
        include "reviewerDashboard.php";
    }
    
    if ($_SESSION['role'] == 'admin') {
        include "adminDashboard.php";
    }
    
?>