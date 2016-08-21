<?php

session_start();

if (!isset($_SESSION['username'])) {
    header("Location: index.php");
}

require_once 'model/papersDB.php';
require_once 'model/messagesDB.php';

$userMessages = getMessages($_SESSION['username']);
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
        <?php
            //from papersDB.php
            
            if (!empty($yourPapers)) {
                //var_dump($yourPapers);
                forEach ($yourPapers as $paper) { ?>
                <div class='paperWrapper'>
                        
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
                            echo "<span class='attributeLabel'>Status:</span> Review Complete. Awaiting your revisions";
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
                    
                    <div class='paperAttribute'>
                        <?php if ($paper['firstReplyFilename'] && !$paper['finalReplyFilename']) {
                                $firstReplyFilename = htmlspecialchars($paper['firstReplyFilename']);
                                echo "<span class='attributeLabel'>Feedback:</span> ".
                                "<a target='_blank' href='uploads/firstResponses/".$firstReplyFilename.
                                "'>".$firstReplyFilename."</a>";
                            } elseif ($paper['finalReplyFilename']) {
                                $finalReplyFilename = htmlspecialchars($paper['finalReplyFilename']);
                                echo "<span class='attributeLabel'>Final Feedback:</span> ".
                                "<a target='_blank' href='uploads/finalResponses/".$finalReplyFilename.
                                "'>".$finalReplyFilename."</a>";    
                            } else {
                                echo "<span class='attributeLabel'>Feedback:</span> N/A";
                            }
                        ?>
                    </div>
                    
                    <?php if ($paper['firstReplyFilename'] && 
                            !$paper['finalReplyFilename']) { ?>
                        <div class='paperAttribute'>
                        <span class='attributeLabel'>Your Revised Paper:</span>
                        <form method='post' action='index.php'>
                            <input class='revisionSubmit' type='submit' name='revisionSubmit' value='Submit'>
                            <input class='revisionUpload' type='file' name='revision'>
                            <input type='hidden' name='paperID' value='<?php 
                                echo $paper['paperID']; ?>'>
                        </form>
                        </div>
                    <?php } ?>
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
            
            <h2>Messages:</h2>
            <div class='messageList'>
                <?php if (count($userMessages) == 0) {
                    echo "<p>You have no messages at this time!</p>";
                } else {
                    forEach ($userMessages as $message) {
                        echo "<p class='messageLineHeader'>Sent on ".date("F j, Y, g:i a", 
                                strtotime($message['whenSent']))." by Administrator:</p>";
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
            <?php if (isset($newPaper) || isset($newPaperError)) {
                include 'newPaper.php';
            } ?>
    </body>
</html>

<?php 

?>