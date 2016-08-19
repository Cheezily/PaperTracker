<?php
require_once 'database.php';

function checkPapers($username) {
    global $db;
    $query = 'SELECT * FROM papers WHERE username=:username ORDER BY when_submitted DESC';
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

function uploadPaper($username, $filename, $title) {
    global $db;
    $query = 'INSERT INTO papers (username, filename, when_submitted, title) VALUES (:username, :filename, :when_submitted, :title)';
    $statement = $db->prepare($query);
    $statement->bindValue(":username", $username);
    $statement->bindValue(":when_submitted", date("Y-m-d H:i:s"));
    $statement->bindValue(":filename", $filename);
    $statement->bindValue(":title", $title);
    $statement->execute();
}
?>