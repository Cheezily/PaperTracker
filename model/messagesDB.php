<?php

require_once 'database.php';

function getMessages($username) {
    global $db;
    $query = "SELECT * FROM messages WHERE username=:username ORDER BY whenSent DESC";
    $statement = $db->prepare($query);
    $statement->bindValue(":username", $username);
    $statement->execute();
    
    $results = $statement->fetchAll();
    return $results;
}

function sendMessage($fromUsername, $toUsername, $message, $messageTitle) {
    $whenSent = date("Y-m-d H:i:s");
    
    global $db;
    $query = "INSERT INTO messages(fromUsername, toUsername, whenSent, message, title) VALUES (:fromUsername, :toUsername, :whenSent, :message, :title)";
    $statement = $db->prepare($query);
    $statement->bindValue(":fromUsername", $fromUsername);
    $statement->bindValue(":toUsername", $toUsername);
    $statement->bindValue(":whenSent", $whenSent);
    $statement->bindValue(":message", $message);
    $statement->bindValue(":title", $messageTitle);
    $statement->execute();
    
    //make sure the message went through
    $query = "SELECT * FROM messages WHERE fromUsername=:fromUsername AND whenSent=:whenSent AND title=:title";
    $statement = $db->prepare($query);
    $statement->bindValue(":fromUsername", $fromUsername);
    $statement->bindValue(":whenSent", $whenSent);
    $statement->bindValue(":title", $messageTitle);
    $statement->execute();
    $results = $statement->fetch();
    
    if ($results) {
        return "Message sent";
    } else {
        return;
    }
}
?>