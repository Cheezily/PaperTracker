<?php
require_once 'database.php';

function checkPapers($username) {
    global $db;
    $query = 'SELECT * FROM papers WHERE username=:username ORDER BY whenSubmitted DESC';
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

function checkPapersForReviewer($reviewername) {
    global $db;
    $query = 'SELECT * FROM papers WHERE reviewername=:reviewername ORDER BY when_submitted DESC';
    $statement = $db->prepare($query);
    $statement->bindValue(":reviewername", $reviewername);
    $statement->execute();
    $results = $statement->fetchAll();
    if ($results) {
        return $results;
    } else {
        return FALSE;
    }
}

function uploadDraft($username, $filename, $title) {
    global $db;
    $query = 'INSERT INTO papers (username, draftFilename, whenSubmitted, title) VALUES (:username, :filename, :whenSubmitted, :title)';
    $statement = $db->prepare($query);
    $statement->bindValue(":username", $username);
    $statement->bindValue(":whenSubmitted", date("Y-m-d H:i:s"));
    $statement->bindValue(":filename", $filename);
    $statement->bindValue(":title", $title);
    $statement->execute();
}


?>