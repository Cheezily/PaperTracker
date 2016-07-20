<?php
require_once 'database.php';

function checkPapers($username) {
    global $db;
    $query = 'SELECT * FROM papers WHERE username=:username';
    $statement = $db->prepare($query);
    $statement->bindValue(":username", $username);
    $statement->execute();
    $results = $statement->fetchAll();
    if ($results) {
        return $results;
    } else {
        return FALSE;
    }
}
?>