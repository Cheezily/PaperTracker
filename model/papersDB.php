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
    //echo $reviewername;
    global $db;
    $query = 'SELECT * FROM papers WHERE reviewername=:reviewername ORDER BY whenSubmitted DESC';
    $statement = $db->prepare($query);
    $statement->bindValue(":reviewername", $reviewername);
    $statement->execute();
    $results = $statement->fetchAll();
    //var_dump($results);
    if ($results) {
        return $results;
    } else {
        return FALSE;
    }
}

function uploadDraft($username, $filename, $title) {
    global $db;
    $query = 'INSERT INTO papers (username, draftFilename, whenSubmitted, title, recentlyUpdated) '
            . 'VALUES (:username, :filename, :whenSubmitted, :title, :recentlyUpdated)';
    $statement = $db->prepare($query);
    $statement->bindValue(":username", $username);
    $statement->bindValue(":whenSubmitted", date("Y-m-d H:i:s"));
    $statement->bindValue(":filename", $filename);
    $statement->bindValue(":title", $title);
    $statement->bindValue(":recentlyUpdated", 1);
    $statement->execute();
}

function checkForRevision($paperID) {
    global $db;
    $query = 'SELECT * FROM papers WHERE paperID=:paperID';
    $statement = $db->prepare($query);
    $statement->bindValue(":paperID", $paperID);
    $statement->execute();
    
    $results = $statement->fetch();
    return $results['revisedFilename'];
}

function uploadRevision($paperID, $filename) {
    global $db;
    $query = 'UPDATE papers SET revisedFilename=:filename, whenRevised=:whenRevised, status=:status WHERE paperID=:paperID';
    $statement = $db->prepare($query);
    $statement->bindValue(":filename", $filename);
    $statement->bindValue(":status", "revisions_submitted");
    $statement->bindValue(":whenRevised", date("Y-m-d H:i:s"));
    $statement->bindValue(":paperID", $paperID);
    $statement->execute();
}

?>