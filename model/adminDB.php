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

function getRealName($username) {
    global $db;
    $query = "SELECT first_name, last_name, affiliation FROM users WHERE username=:username";
    
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

function assignReviewer($paperID, $reviewer) {
    global $db;
    $query = "UPDATE papers SET reviewername=:reviewer, status='awaiting_review', whenAssigned=:assigned WHERE paperID=:paperID";
    
    $statement=$db->prepare($query);
    $statement->bindValue('reviewer', $reviewer);
    $statement->bindValue('paperID', $paperID);
    $statement->bindValue('assigned', date("Y-m-d H:i:s"));
    return $statement->execute();
}

function addEditorNotes($paperID, $noteText) {
    global $db;
    $query = "UPDATE papers SET editorNotes=:editorNotes, whenEditorNotes=:whenEditorNotes WHERE paperID=:paperID";
    
    $statement=$db->prepare($query);
    $statement->bindValue('editorNotes', $noteText);
    $statement->bindValue('whenEditorNotes', date("Y-m-d H:i:s"));
    $statement->bindValue('paperID', $paperID);
    return $statement->execute();
}
    
function deleteEditorNotes($paperID) {
    //only the editor can delete notes
    if ($_SESSION['username'] === 'admin') {
        global $db;
        $query = "UPDATE papers SET editorNotes=NULL, whenEditorNotes=NULL WHERE paperID=:paperID";

        $statement=$db->prepare($query);
        $statement->bindValue('paperID', $paperID);
        return $statement->execute();    
    }
}

function deletePaperDB($paperID) {
    
    //only the editor can delete anything
    if ($_SESSION['username'] === 'admin') {
        global $db;
        $query = "SELECT * FROM papers WHERE paperID=:paperID";
        $statement = $db->prepare($query);
        $statement->bindValue('paperID', $paperID);
        $statement->execute();
        
        $paper = $statement->fetch();
        
        if (!empty($paper['draftFilename'])) {
            $doc_dir = getcwd().'/uploads/drafts/';
            unlink($doc_dir.$paper['draftFilename']);
        }
        
        if (!empty($paper['firstReviewFilename'])) {
            $doc_dir = getcwd().'/uploads/firstReview/';
            unlink($doc_dir.$paper['firstReviewFilename']);
        }
        
        if (!empty($paper['revisedFilename'])) {
            $doc_dir = getcwd().'/uploads/revisions/';
            unlink($doc_dir.$paper['revisedFilename']);
        }
        
        if (!empty($paper['finalReviewFilename'])) {
            $doc_dir = getcwd().'/uploads/finalReview/';
            unlink($doc_dir.$paper['finalReviewFilename']);
        }
        
        $query = "DELETE FROM papers WHERE paperID=:paperID";
        $statement = $db->prepare($query);
        $statement->bindValue('paperID', $paperID);
        return $statement->execute();
    }
}
?>
