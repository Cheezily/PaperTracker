<?php

require_once 'database.php';

function getNewMessages() {
    global $db;
    var_dump($db);
    //$query = "SELECT * FROM messages WHERE toUsername='admin' and newMessage=1";
    $query = "SELECT * FROM messages";
    $statement = $db->prepare($query);
    $statement->execute();
    
    return $statement->fetchAll();
}

function getOldMessages() {
    global $db;
    $query = "SELECT * FROM messages WHERE toUsername=admin and newMessage=0";
    $statement=$db->prepare($query);
    $statement->execute();
    
    return $statement->fetchAll();
}


?>
