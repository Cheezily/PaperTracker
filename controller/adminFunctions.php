<?php

require_once 'model/database.php';
require_once 'model/adminDB.php';

if ($_POST['deletePaperConfirm']) {
    $deletePaperID = filter_input(INPUT_POST, 'paperID');
}

function getMessageCounts() {

    $allMessages = getAllMessages();
    $newMessages = 0;
    $oldMessages = 0;
    $previousLogin = date($_SESSION['previousLogin']);
    
    forEach($allMessages as $message) {
        if ($previousLogin < date($message['whenSent'])) {
            $newMessages++;
        } else {
            $oldMessages++;
        }
    }
    return array($newMessages, $oldMessages);
}


function getMessageLists() {
    
    $needsReply = array();
    $alreadyReplied = array();
    $allMessages = getAllMessages();
    
    forEach($allMessages as $message) {
        if (empty($message['whenReplied'])) {
            array_push($needsReply, $message);
        } else {
            array_push($alreadyReplied, $message);
        }
    }
    
    return array($needsReply, $alreadyReplied);
}


function getPaperCounts() {

    $allPapers = getAllPapers();
    $newPapers = 0;
    $oldPapers = 0;
    $previousLogin = date($_SESSION['previousLogin']);
    
    forEach($allPapers as $paper) {
        if ($previousLogin < date($paper['whenSubmitted'])) {
            $newPapers++;
        } else {
            $oldPapers++;
        }
    }
    return array($newPapers, $oldPapers);
}


function getUserCounts() {

    $allUsers = getAllUsers();
    $newUsers = 0;
    $oldUsers = 0;
    $previousLogin = date($_SESSION['previousLogin']);
    
    forEach($allUsers as $user) {
        if ($previousLogin < date($user['account_created'])) {
            $newUsers++;
        } else {
            $oldUsers++;
        }
    }
    return array($newUsers, $oldUsers);
}


?>

