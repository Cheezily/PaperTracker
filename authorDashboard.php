<?php

session_start();

if (!isset($_SESSION['username'])) {
    header("Location: index.php");
}

require_once 'model/papersDB.php';
require_once 'controller/messages.php';

$yourPapers = checkPapers($_SESSION['username']);


$hideAlert = FALSE;
if (isset($_POST['getStarted'])) {
    $hideAlert = TRUE;
}

?>

<!DOCTYPE html>
<html>
    <head>
        <title>
            Submission Tracker
        </title>
        <script type='text/javascript' src='bower_components/jquery/dist/jquery.min.js'></script>
    </head>
    
    <body>
        <?php include 'static/header.php';?>
        
        <?php 
            if ($_SESSION['firstLogin']) {
        ?>
        
            <?php if($hideAlert == FALSE) {?>
                <div class='newAuthorAlert'>
            <?php 
            } else {
            ?>
                <div class='hideAuthorAlert'>    
            <?php
            }
            ?>
        
            <h2>Hi there!</h2>
            <p>Welcome to your dashboard.  As an author, there won't be
                much here to look at -- just the status of the papers you've submitted 
                and a button to submit a new one.</p>
            <p>The possible states your paper could be in are:</p>
            <ul id='statusListAlert'>
                <li>Awaiting Assignment (to a reviewer)</li>
                <li>Awaiting Review</li>
                <li>Awaiting Revisions (from you)</li>
                <li>Rejected</li>
                <li>Accepted (you're done!)</li>
            </ul>
            <br>
            <p>Press the button below to get started and submit a paper for review.</p>
            <form method='post' action='index.php'>
                <input type='submit' name='getStarted' class='getStartedButton' value='Get Started'> 
            </form>
            
        </div>

        <?php
            }
        ?>
        <div class='statusList'>
            
        </div>
        <?php if ((!isset($newPaperError) && isset($newPaper) && 
                (!isset($newPaperCancel) && !isset($newPaperSubmitted))) 
                || ($_SESSION['firstLogin'] && !$hideAlert)) {?>
        <div class='mainWrapper fadeOut'>
        <?php
        } elseif (($_SESSION['firstLogin'] && $hideAlert) || isset($newPaperCancel) ||
                isset($newPaperSubmitted)) {
            $hideAlert = FALSE;
            $_SESSION['firstLogin'] = FALSE;
        ?>
        <div class='mainWrapper fadeIn'>    
        <?php } elseif (isset($newPaperError) || isset($newPaperSubmitted) || isset($newPaperCancel)) { ?>
            <div class='mainWrapper dimmed'>
        <?php } else { ?>
            <div class='mainWrapper'>
        <?php } ?>
            <h2>Submission List:</h2>
            <div class='paperList'>
        <?php if (!empty($yourPapers)) {
                forEach ($yourPapers as $paper) { ?>
                    <?php switch ($paper['status']) {
                        case "awaiting_assignment":
                            echo "<div class='paperWrapper awaitingAssignment'>";
                            break;
                        case "awaiting_review":
                            echo "<div class='paperWrapper awaitingReview'>";
                            break;
                        case "awaiting_revisions":
                            echo "<div class='paperWrapper awaitingRevisions'>";
                            break;
                        case "revisions_submitted":
                            echo "<div class='paperWrapper awaitingFinal'>";
                            break;
                        case "accepted":
                            echo "<div class='paperWrapper accepted'>";
                            break;
                        case "rejected":
                            echo "<div class='paperWrapper rejected'>";
                            break;
                        default:
                            echo "<div class='paperWrapper'>";
                    } ?>
  
                    <div class='paperAttribute'>
                        <?php echo "<span class='attributeLabel'>Title:</span> ".htmlspecialchars($paper['title']); ?>
                    </div>

                    <div class='paperAttribute'>
                    <?php switch ($paper['status']) {
                        case "awaiting_assignment":
                            echo "<span class='attributeLabel'>Status:</span> Awaiting Assignment to Reviewer";
                            break;
                        case "awaiting_review":
                            echo "<span class='attributeLabel'>Status:</span> Assigned to Reviewer. Under review";
                            break;
                        case "awaiting_revisions":
                            echo "<span class='attributeLabel'>Status:</span> Initial Review Complete. Awaiting your revisions";
                            break;
                        case "revisions_submitted":
                            echo "<span class='attributeLabel'>Status:</span> Revision submitted. Awaiting final review";
                            break;
                        case "accepted":
                            echo "<span class='attributeLabel'>Status:</span> Complete. Accepted!";
                            break;
                        case "rejected":
                            echo "<span class='attributeLabel'>Status:</span> Rejected";
                            break;
                        default:
                            echo "Status not available. Please contact the administrator";
                    } ?>
                    </div>
                    
                    <div class='paperAttribute'>
                        <?php echo "<span class='attributeLabel'>Draft Filename:</span> ".
                                "<a target='_blank' href='uploads/drafts/".$paper['draftFilename'].
                                "'>".htmlspecialchars($paper['draftFilename'])."</a>"; ?>
                    </div>
                    
                    <!--display the link to the revised paper if there is one-->
                    <?php if ($paper['revisedFilename']) { ?>
                        <div class='paperAttribute'>
                            <span class='attributeLabel'>Your Revised Paper:</span>
                            <?php echo "<a href='".$paper['revisedFilename'].
                                "'>".$paper['revisedFilename']."</a>"; ?>
                        </div>
                    <?php } ?>
                    
                    <!--display the links to the feedback docs if they're there
                        and the editor has updated the status-->
                    <div class='paperAttribute'>
                        <?php if ($paper['firstReviewFilename'] && !$paper['finalReviewFilename'] &&
                                $paper['status'] == "awaiting_revisions") {
                                $firstReviewFilename = htmlspecialchars($paper['firstReviewFilename']);
                                echo "<span class='attributeLabel'>Initial Reviewer Feedback:</span> ".
                                "<a target='_blank' href='uploads/firstReview/".$firstReviewFilename.
                                "'>".$firstReviewFilename."</a>";
                            } elseif ($paper['finalReviewFilename'] &&
                                    ($paper[status] == "accepted" || 
                                     $paper['status'] == "rejected")) {
                                $finalReviewFilename = htmlspecialchars($paper['finalReviewFilename']);
                                echo "<span class='attributeLabel'>Final Review:</span> ".
                                "<a target='_blank' href='uploads/finalReview/".$finalReviewFilename.
                                "'>".$finalReviewFilename."</a>";    
                            } else {
                                echo "<span class='attributeLabel'>Initial Reviewer Feedback:</span> N/A";
                            }
                        ?>
                    </div>

                    <!--display option to submit a revised paper if only the 
                        first feedback file exists and the status has been updated
                        by the editor-->
                    <?php if ($paper['firstReviewFilename'] && 
                            !$paper['finalReviewFilename'] && 
                            !$paper['revisedFilename'] &&
                            $paper['status'] == "awaiting_revisions") { ?>
                        <div class='paperAttribute'>
                        <span class='attributeLabel'>Your Revised Paper:</span>
                        <form method='post' action='index.php' enctype="multipart/form-data">
                            <input class='revisionSubmit' type='submit' name='revisionSubmit' value='Submit'>
                            <input type='file' class='revisionUpload' name='revisionFile' required>
                            <input type='hidden' name='paperID' value='<?php 
                                echo $paper['paperID']; ?>'>
                        </form>
                        <?php if ($revisionError) {
                            echo "<br><div><span class='miniWarning'>".$revisionError."</span></div>";
                        } ?>
                        </div>
                    <?php } echo readEditorNote($paper);?>

                </div>    
            <?php 
                
                } 
            } else { ?>
            <h2>You have no papers waiting for review. Click the button below
                to submit a new document for review.</h2>
            <?php } ?>
            </div>
            <form method='post' action='index.php'>
                <button class='submitButton' type='submit' name="newPaper">Submit New Draft</button>
            </form>

            <br>
            <br>
            
            <?php include "userMessages.php"; ?>
        </div>
            <?php if (isset($newPaper) || isset($newPaperError)) {
                include 'newPaper.php';
            } ?>
            <script type="text/javascript" src="js/readNotes.js"></script>
    </body>
</html>

<?php 

?>