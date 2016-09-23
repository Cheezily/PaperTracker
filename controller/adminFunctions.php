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
    
    $needsAssignment = array();
    $awaitingInitialReview = array();
    $needsPostReviewStatus = array();
    $awaitingRevisions = array();
    $awaitingFinalReview = array();
    $finalReviewDone = array();
    $accepted = array();
    $rejected = array();
    $recentlyUpdated = array();
    
    forEach($papers as $paper) {
        if ($paper['status'] === 'awaiting_assignment') {
            array_push($needsAssignment, $paper);
        }
        
        if ($paper['status'] === 'awaiting_review' && empty($paper['firstReviewFilename'])) {
            array_push($awaitingInitialReview, $paper);
        }
        
        if ($paper['status'] === 'awaiting_review' && !empty($paper['firstReviewFilename'])) {
            array_push($needsPostReviewStatus, $paper);
        }

        if ($paper['status'] === 'awaiting_revisions') {
            array_push($awaitingRevisions, $paper);
        }
        
        if ($paper['status'] === 'revisions_submitted' && empty($paper['finalReviewFilename'])) {
            array_push($awaitingFinalReview, $paper);
        }
        
        if ($paper['status'] === 'revisions_submitted' && !empty($paper['finalReviewFilename'])) {
            array_push($finalReviewDone, $paper);
        }
        
        if ($paper['status'] === 'accepted') {
            array_push($accepted, $paper);
        }
        
        if ($paper['status'] === 'accepted') {
            array_push($rejected, $paper);
        }
    }
    
    forEach($papers as $paper) {
        if ($paper['recentlyUpdated'] == 1) {
            array_push($recentlyUpdated, $paper);
        }
    }
    
    $output = array("needsAssignment" => $needsAssignment, 
        "awaitingInitialReview" => $awaitingInitialReview, 
        "needsPostReviewStatus" => $needsPostReviewStatus, 
        "awaitingRevisions" => $awaitingRevisions,
        "awaitingFinalReview" => $awaitingFinalReview,
        "finalReviewDone" => $finalReviewDone,
        "accepted" => $accepted,        
        "rejected" => $rejected,
        "recentlyUpdated" => $recentlyUpdated);
    
    return $output;
}

//options for assigning a reviewer to a paper
function reviewerOptionList() {
    $reviewers = getAllReviewers();
    
    //var_dump($reviewers);
    $list = "<option></option>";

    forEach($reviewers as $reviewer) {
        $list = $list."<option class='reviewer' value=".$reviewer['username'].'>'.
                $reviewer['first_name'].' '.$reviewer['last_name'].' - '.
                $reviewer['affiliation'].'</option>';
    }
    
    return $list;
}


function paperNote($paper) {
    
    $note = '';
    $authorName = getRealName($paper['username']);
    
    if ($paper['editorNotes']) {
        
        $noteDate = date('M j, Y, g:i a', strtotime($paper['whenEditorNotes']));
        $buttonTitle = "Editor Note Submitted on ".$noteDate." -- Click to View/Edit";

    } else {
        $buttonTitle = "Click here to add note to the Author";
    }
    
    
    $output = "<button class='adminNoteButton' paperID=".$paper['paperID'].">".$buttonTitle.
            "</button>".
            "<div class='adminPaperNote' id='makeNoteFor".$paper['paperID']."'>".
                "<div class='adminNoteHeading'>Note for <b>".$paper['title']."</b> by ".
                    '<b>'.$authorName[0].' '.$authorName[1].'</b> at <b>'.$authorName[2].'</b>'.
                "</div><hr>".
                "<form class='noteForm' method='post' action=''>".
                    "<input type='hidden' name='paperID' value='".$paper['paperID']."'>".
                    "<textarea class='paperNote' name='noteText' id='textAreaFor".$paper['paperID']."'>".
                    "</textarea><hr>".
                    "<input class='submitNoteButton' type='submit' name='adminNote' value='Submit'>".
                    "<input class='deleteNoteButton' type='submit' name='deleteNote' value='Delete This Note'>".
                    "<button type='button' class='cancelNoteButton' paperID=".$paper['paperID'].">Cancel</button>".
                "</form>".
                "<div id='textFor".$paper['paperID']."' style='display: none;'>".
                    ($paper['editorNotes']).
                "</div>".
            "</div>";
    
    return $output;
}

function deletePaper($paper) {
    $output = "<div class='deletePaperButtonWrapper'>".
            "<button class='deletePaperButton' paperID=".$paper['paperID']." id='delete".$paper['paperID']."'>".
                "Delete This Paper".
            "</button>".
            "<form class='deleteConfirm' id='confirm".$paper['paperID']."' method='post' action=''>".
                "<input type='hidden' name='paperID' value=".$paper['paperID'].">".
                "<input type='submit' name='deletePaper' value='Click to confirm that you really wish to delete this paper!'>".
            "</form>".
            "</div>";
    
    return $output;
}

if (isset($_POST['deletePaper'])) {
    $paperID = filter_input(INPUT_POST, "paperID", FILTER_SANITIZE_NUMBER_INT);
    
    $adminPage = "adminPapers.php";
    //only the editor can delete papers from the system
    if ($_SESSION['username'] === 'admin' && $paperID) {
        deletePaperDB($paperID);
    }
}


if (isset($_POST['changeReviewer']) &&
        isset($_POST['reviewer']) &&
        isset($_POST['paperID'])) {
    
    $reviewer = filter_input(INPUT_POST, "reviewer", FILTER_SANITIZE_STRIPPED);
    $paperID = filter_input(INPUT_POST, "paperID", FILTER_SANITIZE_NUMBER_INT);

    
    if($reviewer && $paperID) {
        assignReviewer($paperID, $reviewer);
    }
}

if (isset($_POST['adminNote'])) {

    $adminPage = "adminPapers.php";
    $noteText = filter_input(INPUT_POST, "noteText", FILTER_SANITIZE_STRING);
    $paperID = filter_input(INPUT_POST, "paperID", FILTER_SANITIZE_NUMBER_INT);
    
    if (!empty($noteText) && !empty($paperID)) {
        addEditorNotes($paperID, $noteText);
    }
}

if (isset($_POST['deleteNote'])) {

    $adminPage = "adminPapers.php";
    $paperID = filter_input(INPUT_POST, "paperID", FILTER_SANITIZE_NUMBER_INT);
    
    if (!empty($paperID)) {
        deleteEditorNotes($paperID, $_SESSION['username']);
    }
}


?>

