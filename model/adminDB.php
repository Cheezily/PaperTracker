<?php

function getAllMessages() {
    global $db;
    $query = "SELECT * FROM messages ORDER BY whenSent DESC";
    $statement = $db->prepare($query);
    $statement->execute();
    $results = $statement->fetchAll();
    
    return $results;
}

function getAllPapers() {
    global $db;
    $query = "SELECT * FROM papers";
    $statement=$db->prepare($query);
    $statement->execute();
    $results = $statement->fetchAll();
    
    return $results;
}

function getAllUsers() {
    global $db;
    $query = "SELECT * FROM users";
    $statement=$db->prepare($query);
    $statement->execute();
    $results = $statement->fetchAll();
    
    return $results;
}


?>
