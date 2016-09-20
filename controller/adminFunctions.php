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


//'awaiting_assignment','awaiting_review','awaiting_revisions','revisions_submitted','accepted','rejected'
function getPapersByStatus($papers) {

    $awaitingReview = array();
    $awaitingAuthorUpdate = array();
    $accepted = array();
    $rejected = array();
    $needsAssignment = array();
    $recentlyUpdated = array();
    
    forEach($papers as $paper) {
        switch ($paper['status']) {
            case 'awaiting_review':
                array_push($awaitingReview, $paper);
                break;
            case 'awaiting_revisions':
                array_push($awaitingUpdate, $paper);
                break;
            case 'revisions_submitted':
                array_push($needsAssignment, $paper);
                break;
            case 'accepted':
                array_push($accepted, $paper);
                break;
            case 'rejected':
                array_push($rejected, $paper);
                break;
            default:
                array_push($needsAssignment, $paper);
        }
    }
    
    forEach($papers as $paper) {
        if ($paper['recentlyUpdated'] == 1) {
            array_push($recentlyUpdated, $paper);
        }
    }
    
    $output = array("needsAssignment" => $needsAssignment, 
        "awaitingReview" => $awaitingReview, 
        "awaitingAuthorUpdate" => $awaitingAuthorUpdate, 
        "accepted" => $accepted, 
        "rejected" => $rejected,
        "recentlyUpdated" => $recentlyUpdated);
    
    return $output;
}

?>

