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

function getAuthor($username) {
    global $db;
    $query = "SELECT first_name, last_name FROM users WHERE username=:username";
    
    $statement=$db->prepare($query);
    $statement->bindValue('username', $username);
    $statement->execute();
    $results = $statement->fetch();
    
    return $results;
}

function getAllReviewers() {
    global $db;
    $query = "SELECT * FROM users WHERE role='reviewer'";
    
    $statement=$db->prepare($query);
    $statement->execute();
    $results = $statement->fetchAll();
    
    //var_dump($results);
    
    return $results;   
}
?>
