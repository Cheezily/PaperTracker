<?php

require_once 'database.php';


function getMessages($fromUsername) {
    global $db;
    $query = "SELECT * FROM messages WHERE fromUsername=:fromUsername ORDER BY whenSent DESC";
    $statement = $db->prepare($query);
    $statement->bindValue(":fromUsername", $fromUsername);
    $statement->execute();
    
    $results = $statement->fetchAll();
    return $results;
}


function sendMessage($fromUsername, $message, $messageTitle) {
    $whenSent = date("Y-m-d H:i:s");
    
    global $db;
    $query = "INSERT INTO messages(fromUsername, whenSent, message, title) VALUES (:fromUsername, :whenSent, :message, :title)";
    $statement = $db->prepare($query);
    $statement->bindValue(":fromUsername", $fromUsername);
    $statement->bindValue(":whenSent", $whenSent);
    $statement->bindValue(":message", $message);
    $statement->bindValue(":title", $messageTitle);
    $results = $statement->execute();

    if ($results) {
        return "Message sent";
    } else {
        return;
    }
}


function replyToMessage($messageID, $reply) {
    
    //echo "id ".$messageID." reply: ".$reply;
    $whenReplied = date("Y-m-d H:i:s");
    global $db;
    $query = "UPDATE messages SET whenReplied=:whenReplied, reply=:reply WHERE messageID=:messageID";
    $statement = $db->prepare($query);
    $statement->bindValue("whenReplied", $whenReplied);
    $statement->bindValue("reply", $reply);
    $statement->bindValue("messageID", $messageID);
    $result = $statement->execute();
    
    return $result;
}
    
?>