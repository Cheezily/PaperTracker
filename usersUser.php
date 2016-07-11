<?php

session_start();

if (!isset($_SESSION)) {
    header("Location: index.php");
} else {
    echo "Hello, ".$_SESSION['firstname'].'. Welcome to the basic user page!';
}
?>