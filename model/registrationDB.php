<?php
require_once 'database.php';

function check_username($username) {
    global $db;
    $query = 'SELECT * FROM users WHERE username=:username';
    $statement = $db->prepare($query);
    $statement->bindValue(":username", $username);
    $statement->execute();
    $results = $statement->fetchAll();
    if ($results) {
        return TRUE;
    } else {
        return FALSE;
    }
}

?>
