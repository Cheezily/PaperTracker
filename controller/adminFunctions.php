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
    if ($paper['editorNotes']) {
        
        $noteDate = date('M j, Y, g:i a (e)', strtotime($paper['whenEditorNotes']));
        $buttonTitle = "Edit/View Note to the Author Updated on ".$noteDate;
        
        $note = "<div class='noteAlert'>".
                    "<div class='noteButton' class='viewNote' noteNumber=".$paper['paperID'].">".
                        "Editor Note Submitted on ".$noteDate.
                    "</div>".
                "</div>".
                "<div class='adminReadNote' id=".$paper['paperID'].">".
                    "<div class='adminReadNoteHeader>".
                        "<h4>Note Submitted on ".$noteDate."</h4>".
                    "</div>".
                    "<button class='closeNote' noteNumber=".$paper['paperID'].">".
                "</div>";
    } else {
        $buttonTitle = "Add Note to the Author";
    }
    
    $output = $note."<button class='adminNoteButton' paperID=".$paper['paperID'].">".$buttonTitle.
            "</button>".
            "<div class='adminPaperNote' id='makeNoteFor".$paper['paperID']."'>".
                "<div class='adminNoteHeading'>Note for <b>".$paper['title']."</b></div><hr>".
            "<form method='post' action=''>".
                "<input type='hidden' name='paperID' value='".$paper['paperID']."'>".
                "<textarea class='paperNote' name='noteText' id='textAreaFor".$paper['paperID']."'>".
                "</textarea><hr>".
                "<input type='submit' name='adminNote' value='Submit Note'>".
            "</form>".
            "<button class='adminNoteCancel' paperID=".$paper['paperID'].">Cancel</button>".
            "<div id='textFor".$paper['paperID']."' style='display: none;'>".
            ($paper['editorNotes']).
            "</div>".
            "</div>";
    
    return $output;
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

?>

