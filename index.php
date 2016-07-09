<?php
    
    if (empty($_SESSION)) {
        $lifetime = 90000;
        session_set_cookie_params($lifetime, '/');
        session_start();
        $_SESSION['token'] = '9872344234';
        $_SESSION['last_activity'] = time();
    }

    session_destroy();
    
    echo "Time ".time()."<br>";

    if (!empty($_SESSION) && (time() - $_SESSION['last_activity']) > 30) {
        echo 'The session has expired.  Please log in again.';
        $_SESSION = array();
    } else {
        include 'static/header.php';
    }
    
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

?>