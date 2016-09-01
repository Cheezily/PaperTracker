<?php

session_start();

if (!isset($_SESSION['username'])) {
    header("Location: index.php");
}

require_once 'model/papersDB.php';
require_once 'model/messagesDB.php';

$userMessages = getMessages($_SESSION['username']);
$yourPapers = checkPapersForReviewer($_SESSION['username']);

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
        <?php } else { ?>
            <div class='mainWrapper'>
        <?php } ?>
            <h2>Your Review Queue:</h2>
            <div class='paperList'>
        <?php
            //from papersDB.php
            
            if (!empty($yourPapers)) {
                //var_dump($yourPapers);
                forEach ($yourPapers as $paper) { ?>
                <div class='paperWrapper'>
                    
                    <!--Display the paper title-->
                    <div class='paperAttribute'>
                        <?php echo "<span class='attributeLabel'>Title:</span> ".htmlspecialchars($paper['title']); ?>
                    </div>
                    
                    <!--Display a link to the draft file submitted by the author-->
                    <div class='paperAttribute'>
                        <?php echo "<span class='attributeLabel'>Draft File:</span> ".
                                "<a target='_blank' href='uploads/drafts/".$paper['draftFilename'].
                                "'>".htmlspecialchars($paper['draftFilename'])."</a>"; ?>
                    </div>
                    
                    <!--Display the box for the reviewer to submit the initial review
                        or display the comment file the reviewer already submitted-->
                    <?php if (!$paper['recommendation']) { ?>
                        <p class='instructions'>Please review the draft file. Select your 
                            recommendation and submit a MS Word file with your comments whenever you're ready</p>
                        <?php if ($reviewError) {
                            echo "<span class='miniWarning'>".$reviewError."</span>";
                        } ?>
                        <div class='paperAttribute'>
                            <span class='attributeLabel'>Recommendation:</span>
                            <form method='post' action='index.php' enctype="multipart/form-data">
                                <select name='recommendation' required>
                                    <option value='' selected></option>
                                    <option value='accept' >Accept As Is</option>
                                    <option value='minor' >Minor Revisions Needed</option>
                                    <option value='major' >Major Revisions Needed</option>
                                    <option value='reject' >Reject This Paper</option>
                                </select>
                                <input type='hidden' name='paperID' value='<?php 
                                echo $paper['paperID']; ?>'>
                                <input type='file' name='reviewFile' required>
                                <input type='submit' class='reviewSubmit' 
                                       name='firstReviewSubmit' value='Submit'>
                            </form>
                        </div>
                    <?php } ?>
                    
                    <!--Let the reviewer know what's going on after they upload the
                        initial review-->
                    <?php if ($paper['firstReviewFilename']) { ?>
                        <div class='paperAttribute'>
                            <?php echo "<span class='attributeLabel'>Initial Feedback File:</span> ".
                                    "<a target='_blank' href='uploads/firstReview/".$paper['firstReviewFilename'].
                                    "'>".htmlspecialchars($paper['firstReviewFilename'])."</a>"; ?>
                        </div>
                        <?php if ($paper['recentlyUpdated'] == "1" &&
                                !$paper['finalReviewFilename'] &&
                                !$paper['revisedFilename']) {
                            echo "<p class='instructions'>Thanks! Based on your 
                            feedback, if a decision for a revise & resubmit is made by the Editor, 
                            this box will allow you to submit a final review to the Editor 
                            once the revised copy has been submitted by the author. You will be notified by email
                            when the Editor reviews your recommendation.</p>";
                        } elseif ($paper['status'] != 'revisions_submitted') {
                            echo "<p class='instructions'>The Editor is currently"
                            . " reviewing your recommendation.</p>";
                        } ?>
                    <?php } ?>
                    
                    <!--Display a link to the revised file submitted by the author
                        if one exists-->
                    <?php if ($paper['revisedFilename']) { ?>
                    <div class='paperAttribute'>
                        <?php echo "<span class='attributeLabel'>Revised File:</span> ".
                                "<a target='_blank' href='uploads/drafts/".$paper['revisedFilename'].
                                "'>".htmlspecialchars($paper['revisedFilename'])."</a>"; ?>
                    </div>
                    <?php } ?>
                        
                    <!--Display the box for the reviewer to submit the final review-->
                    <?php if ($paper['status'] == 'revisions_submitted' && 
                            !$paper['finalReviewFilename']) { ?>
                        <p class='instructions'>Please select your final
                            recommendation and submit a MS Word file with your comments whenever you're ready</p>
                        <?php if ($reviewError) {
                            echo "<span class='miniWarning'>".$reviewError."</span>";
                        } ?>
                        <div class='paperAttribute'>
                            <span class='attributeLabel'>Final Recommendation:</span>
                            <form method='post' action='index.php' enctype="multipart/form-data">
                                <select name='recommendation' required>
                                    <option value='' selected></option>
                                    <option value='accept' >Accept</option>
                                    <option value='reject' >Reject</option>
                                </select>
                                <input type='hidden' name='paperID' value='<?php 
                                echo $paper['paperID']; ?>'>
                                <input type='file' name='reviewFile' required>
                                <input type='submit' class='reviewSubmit' 
                                       name='finalReviewSubmit' value='Submit'>
                            </form>
                        </div>
                    <?php } ?>
                    
                        
                    <!--Let the reviewer know what's going on after they upload the
                        final review-->
                    <?php if ($paper['finalReviewFilename'] &&
                            $paper['status'] != 'accepted' && 
                            $paper['status'] != 'rejected') { ?>
                        <div class='paperAttribute'>
                            <?php echo "<span class='attributeLabel'>Final Feedback File:</span> ".
                                    "<a target='_blank' href='uploads/firstReview/".$paper['finalReviewFilename'].
                                    "'>".htmlspecialchars($paper['finalReviewFilename'])."</a>"; ?>
                        </div>
                        <?php if ($paper['recentlyUpdated'] == "1") {
                            echo "<p class='instructions'>Thanks! You will be notified by email
                            when the Editor makes a final decision on this paper.</p>";
                        } else {
                            echo "<p class='instructions'>The Editor is currently "
                            . "reviewing your recommendation.</p>";
                        } ?>
                    <?php } ?>
                </div>    
            <?php 
                } 
            } else { ?>
            <h2>You have no papers waiting for your review. The administrator will
                assign one shortly or you can message them using the form below.</h2>
            <?php } ?>
            </div>

            <br>
            <br>
            
            <h2>Messages:</h2>
            <div class='messageList'>
                <?php if (count($userMessages) == 0) {
                    echo "<p>You have no messages at this time!</p>";
                } else {
                    forEach ($userMessages as $message) {
                        echo "<p class='messageLineHeader'>Sent on ".date("F j, Y, g:i a", 
                                strtotime($message['whenSent']))." by the Editor:</p>";
                        echo "<p class='messageLineHeader'>Re: ".$message['title']."</p>";
                        echo "<p class='messageLine'>".$message['message']."<p><hr>";
                    }
                }
                ?>
            </div>
            <div class='messageAdmin'>
                <p class='tip'>Please use the following form to contact the administrator.  They
                are responsible for assigning your papers to reviewers as well as 
                handling any requests to delete submitted papers from the system.</p>
                <form method='post' action='index.php'>
                    <label class='paperTitleLabel' for='selectPaperTitle'>Which paper is this about?</label><br>
                    <select class='selectPaperTitle' id='selectPaperTitle' name='messageTitle' required>
                        <?php forEach ($yourPapers as $paper) {
                            echo "<option value='".$paper['title']."'>".$paper['title']."</option>";
                        } ?>
                        <option value='General Question' selected>General Question - No specific paper</option>
                    </select>
                    <br>
                    <textarea class='messageText' name='message' placeholder="Type your message here..." required></textarea>
                    <br>
                    <input type='hidden' name='fromUsername' value='<?php echo $_SESSION['username']; ?>'>
                    <input class='submitButton' type='submit' name='sendMessage' value='Send Message'>
                    <?php //echo "STATUS: ".$_SESSION['messageSent']; ?>
                    <?php if(isset($_SESSION['messageSent'])) {
                        //echo "STUFF";
                        echo "<span class='alert'>".$messageStatus."</span>";
                    } ?>
                </form>
            </div>
        </div>
    </body>
</html>
