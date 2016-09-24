<?php

$papers = getAllPapers();
$paperList = getPapersByStatus($papers);
$needsAssignment = $paperList["needsAssignment"];
$awaitingInitialReview = $paperList["awaitingInitialReview"];
$needsPostReviewStatus = $paperList["needsPostReviewStatus"];
$awaitingRevisions = $paperList["awaitingRevisions"];
$awaitingFinalReview = $paperList["awaitingFinalReview"];
$accepted = $paperList["accepted"];
$rejected = $paperList["rejected"];
$recentlyUpdated = $paperList["recentlyUpdated"];

$reviewerOptions = reviewerOptionList();

function paperTitle($paper) {
    echo "<div class='paperAttribute'>".
            "<span class='attributeLabel'>Paper Title: </span>".htmlspecialchars($paper['title']).
        "</div>";
}


function draftFilename($paper) {
    echo "<div class='paperAttribute'>".
            "<span class='attributeLabel'>Draft File: </span>".
                "<a target='_blank' href='uploads/drafts/".$paper['draftFilename']."'>".
                    htmlspecialchars($paper['draftFilename']).
            "</a>".
            "<br>".
        "</div>";
}


function submittedBy($paper) {
    $author = getRealName($paper['username']);
    echo "<div class='paperAttribute'>".
            "<span class='attributeLabel'>Submitted By: </span>".
                '<b>'.$author[0].' '.$author[1].'</b> from <b>'.$author[2].'<b> on '. 
                    date("M j, Y, g:i a (e)", strtotime($paper['whenSubmitted'])).
            "<br>".
        "</div>";
}


function getReviewer($paper) {
    $reviewer = getRealName($paper['reviewername']);
    echo "<div class='paperAttribute paperAttributeAlt'>".
                "<span class='attributeLabel attributeLabelAlt'>Reviewer: </span>".
                    '<b>'.$reviewer[0].' '.$reviewer[1].'</b> from <b>'.$reviewer[2].
                    '</b> assigned on '.date("M j, Y, g:i a", strtotime($paper['whenAssigned'])).
                "<br>".
            "</div>";
}


function reviewerInitialRecommendation($paper) {
    $recommendaton = '';
        switch ($paper['firstRecommendation']) {
        case "accept":
            $recommendaton = "Accept As-Is -- Recommendation made ".
                date("M j, Y, g:i a", strtotime($paper['whenFirstReply']));
            break;
        case "reject":
            $recommendaton = "Reject Draft -- Recommendation made ".
                date("M j, Y, g:i a", strtotime($paper['whenFirstReply']));
            break;
        case "minor":
            $recommendaton = "Minor Revisions Needed -- Recommendation made ".
                date("M j, Y, g:i a", strtotime($paper['whenFirstReply']));
            break;
        case "major":
            $recommendaton = "Major Revisions Needed -- Recommendation made ".
                date("M j, Y, g:i a", strtotime($paper['whenFirstReply']));
            break;
        default:
            $recommendaton = "Error: No Recommendation Made. Please contact the reviewer.";
        }
        
        echo "<div class='paperAttribute paperAttributeAlt'>".
            "<span class='attributeLabel attributeLabelAlt'>Reviewer Initial Recommendation: </span>".
                $recommendaton."<br>".
            "</div>";
}


function firstReviewFilename($paper) {
    echo "<div class='paperAttribute paperAttributeAlt'>".
            "<span class='attributeLabel attributeLabelAlt'>Initial Reviewer Notes: </span>".
                "<a class='attributeLinkAlt' target='_blank' href='uploads/drafts/".$paper['firstReviewFilename']."'>".
                    htmlspecialchars($paper['firstReviewFilename']).
            "</a>".
            "<br>".
        "</div>";
}


function paperNote($paper) {
    
    $noteButton = '';
    $authorName = getRealName($paper['username']);
    
    if ($paper['editorNotes']) {
        
        $noteDate = date('M j, Y, g:i a', strtotime($paper['whenEditorNotes']));
        $buttonTitle = "Editor Note Submitted on ".$noteDate." -- Click to View/Edit";
        $noteButton = "<div class='noteButtonWrapper'>".
                        "<button class='adminNoteButton' paperID=".$paper['paperID'].">".$buttonTitle.
                        "</button>".
                    "</div>";
    } else {
        $buttonTitle = "Click here to add note to the Author";
        $noteButton = "<div class='noteButtonWrapper'>".
                        "<button class='adminNoteButton adminNoteButton1' paperID=".$paper['paperID'].">".
                        $buttonTitle.
                        "</button>".
                    "</div>";
    }
    
    $output = $noteButton."<div class='adminPaperNote' id='makeNoteFor".$paper['paperID']."'>".
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

?>

<div class="mainWrapperWithNav">
<?php if(empty($papers)) { ?>
    <p>There are no papers in the database at the moment.</p>
<?php } else { ?>
    <h3>New Papers Awaiting Reviewer Assignment:</h3>
    <?php if(empty($needsAssignment)) { ?>
    <p>There are no papers that need to be assigned at this time.</p>
    <?php } else { ?>
    <?php forEach ($needsAssignment as $paper) { ?>
        <div class='adminPaper'>
            <?php echo paperTitle($paper);?>
            <?php echo submittedBy($paper);?>
            <?php echo draftFilename($paper);?>
            <div class='paperAttribute paperAttributeAlt'>
                <span class='attributeLabel attributeLabelAlt'>Please Assign Reviewer: </span>
                <form method="post" action="index.php">
                    <select name="reviewer">
                        <?php echo $reviewerOptions; 
                        echo $adminPage;?>
                    </select>
                    <input type='hidden' name='paperID' value='<?php echo $paper['paperID'];?>'>
                    <input type='hidden' name='adminPage' value='papers'>
                    <input type='submit' class='paperOptionSubmit' name='changeReviewer' value='Assign Reviewer'>
                </form>
            </div>
            <?php echo paperNote($paper); ?>
            <?php echo deletePaper($paper); ?>
        </div>
    <br>
        <?php } 
        } //ends needsAssignment loop ?>

        <hr>
        <br>
        <h3>Papers Awaiting Initial Review:</h3>
        
        <!--The editor needs to add an author note once they make a decision.
            Any papers with r&r, accepted, or rejected with no note will still
            appear here with a notice-->
        <?php if(empty($awaitingInitialReview)) { ?>
        <p>There are no papers awaiting initial review at this time.</p>
        <?php } else { ?>
        <?php forEach ($awaitingInitialReview as $paper) { ?>
        <div class='adminPaper'>
            <?php echo paperTitle($paper);?>
            <?php echo submittedBy($paper);?>
            <?php echo draftFilename($paper);?>
            <?php echo getReviewer($paper);?>
            <div class='paperAttribute paperAttributeAlt'>
                <span class='attributeLabel attributeLabelAlt'>Change Reviewer: </span>
                <form method="post" action="index.php">
                    <select name="reviewer">
                        <?php echo $reviewerOptions; 
                        echo $adminPage;?>
                    </select>
                    <input type='hidden' name='paperID' value='<?php echo $paper['paperID'];?>'>
                    <input type='hidden' name='adminPage' value='papers'>
                    <input type='submit' name='changeReviewer' value='Assign Reviewer'>
                </form>
                <br>
            </div>
            <?php echo paperNote($paper); ?>
            <?php echo deletePaper($paper); ?>
        </div>
        <br>
        <?php }
        } //ends awaitingInitialReview loop ?>

        <hr>
        <br>
        <h3>Papers with Initial Review Completed and Awaiting Your Input:</h3>
        <?php if(empty($needsPostReviewStatus)) { ?>
        <p>There are no papers awaiting a status update at this time.</p>
        <?php } else { ?>
        <?php forEach ($needsPostReviewStatus as $paper) { ?>
        <div class='adminPaper'>
            <?php echo paperTitle($paper);?>
            <?php echo submittedBy($paper);?>
            <?php echo draftFilename($paper);?>
            <?php echo getReviewer($paper);?>
            <?php echo reviewerInitialRecommendation($paper); ?>
            <?php echo firstReviewFilename($paper); ?>
            <div class='paperAttribute paperAttributeAlt1'>
                <span class='attributeLabel attributeLabelAlt1'>Editor's initial Decision: </span>
                <form class='paperOptionList' method="post" action="index.php">
                    <select class='paperOptionList' name="editorStatus">
                        <option value='none'></option>
                        <option value='awaiting_revisions'>Request Revisions</option>
                        <option value='accepted'>Accept As-Is</option>
                        <option value='rejected'>Reject</option>
                    </select>
                    <input type='hidden' name='paperID' value='<?php echo $paper['paperID'];?>'>
                    <input type='hidden' name='adminPage' value='papers'>
                    <input type='submit' class='paperOptionSubmit' name='changePaperStatus' value='Submit'>
                    <span class='attributeLabel attributeLabelAlt1'>Be sure to add a note to the author with your feedback!</span>
                </form>
                <br>
            </div>
            <?php if($needsEditorNote && $paper['paperID'] === $needsEditorNoteID) {
                    echo "<div class='paperAttribute paperAttributeAlt1 noteWarning'>".
                            "This paper needs a note to the author with your feedback before the status can ".
                            "be updated! Please click on the link below this one to add your ".
                            "feedback and try again.".
                        "</div>";
                } ?>
            <?php echo paperNote($paper); ?>
            <?php echo deletePaper($paper); ?>
        </div>
        <?php }
        } //ends needsPostReviewStatus loop ?>            


    </div>

<?php } ?>

<script type='text/javascript' src='admin/JS/adminPaper.js'></script>