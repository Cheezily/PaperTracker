<?php
require_once 'database.php';

function checkUsername($username) {
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

function addUser($user) {
    global $db;
    $query = "INSERT INTO users (username, account_created, first_name,".
            "last_name, email, passwordHash, role) VALUES (:username, ".
            ":account_created, :first_name, :last_name, :email, :passwordHash, ".
            ":role)";
    $accountCreated = date("Y-m-d H:i:s");
    
    $passwordOptions = ['cost' => 11];
    $passwordHash = password_hash($user['password'], PASSWORD_BCRYPT, $passwordOptions);
    
    $statement = $db->prepare($query);
    $statement->bindValue(":username", $user['username']);
    $statement->bindValue(":account_created", $accountCreated);
    $statement->bindValue(":first_name", $user['firstName']);
    $statement->bindValue(":last_name", $user['lastName']);
    $statement->bindValue(":email", $user['email']);
    $statement->bindValue(":passwordHash", $passwordHash);
    $statement->bindValue(":role", $user['role']);
    $statement->execute();
    
}
?>
