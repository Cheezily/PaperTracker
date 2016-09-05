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
    $statement->bindValue(":username", strtolower($user['username']));
    $statement->bindValue(":account_created", $accountCreated);
    $statement->bindValue(":first_name", $user['firstName']);
    $statement->bindValue(":last_name", $user['lastName']);
    $statement->bindValue(":email", $user['email']);
    $statement->bindValue(":passwordHash", $passwordHash);
    $statement->bindValue(":role", $user['role']);
    $statement->execute();

}

function login($name, $password) {
    //echo "---------------<br>";
    //echo "username: ".$name."<br>";
    if (!isset($_SESSION['username'])) {
        $lastLogin = getLastLogin($name);
        global $db;
        $query = "SELECT * FROM users WHERE username=:username";
        $statement = $db->prepare($query);
        $statement->bindValue(":username", strtolower($name));
        $statement->execute();

        $result = $statement->fetch();
        $pwcheck = password_verify(substr($password, 0, 60), $result['passwordHash']);

        if (password_verify($password, $result['passwordHash'])) {
            updateTimestamp($name);
            //$_SESSION['lastLogin'] = $lastLogin;
            return array($result, $lastLogin);
        } else {
            return FALSE;
        }
    }
}

function updateTimestamp($name) {
    global $db;
    $query = "UPDATE users SET last_login=CURRENT_TIMESTAMP WHERE username=:username";
    $statement = $db->prepare($query);
    //$statement->bindValue(":timestamp", date("Y-m-d H:i:s"));
    $statement->bindValue(":username", strtolower($name));
    $statement->execute();
}

function getLastLogin($name) {
    global $db;
    $query = "SELECT last_login FROM users WHERE username=:username";
    $statement = $db->prepare($query);
    $statement->bindValue(":username", strtolower($name));
    $statement->execute();
    
    return $statement->fetch();
}
?>