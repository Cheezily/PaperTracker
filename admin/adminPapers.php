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
            <div class='paperAttribute'>
                <span class='attributeLabel'>Draft Filename:</span>
                <a target='_blank' href='uploads/drafts/<?php echo $paper['draftFilename'];?>'>
                   <?php echo htmlspecialchars($paper['draftFilename']);?>
                </a>
                <br>
            </div>
            <div class='paperAttribute'>
                <span class='attributeLabel'>Submitted By: </span>
                <?php 
                    $author = getRealName($paper['username']);
                    echo '<b>'.$author[0].' '.$author[1].'</b> from <b>'.$author[2].'<b> on '. 
                         date("M j, Y, g:i a (e)", strtotime($paper['whenSubmitted']));
                ?>
                <br>
            </div>
            <div class='paperAttribute'>
                <span class='attributeLabel'>Please Assign Reviewer: </span>
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
        </div>
        <br>
            <?php } 
            } //ends needsAssignment loop ?>
            
            
            
            <hr>
            <br>
            <h3>Papers Awaiting Initial Review:</h3>
            <?php if(empty($awaitingInitialReview)) { ?>
            <p>There are no papers awaiting initial review at this time.</p>
            <?php } else { ?>
            <?php forEach ($awaitingInitialReview as $paper) { ?>
            <div class='adminPaper'>
                <div class='paperAttribute'>
                    <span class='attributeLabel'>Draft Filename:</span>
                    <a target='_blank' href='uploads/drafts/<?php echo $paper['draftFilename'];?>'>
                       <?php echo htmlspecialchars($paper['draftFilename']);?>
                    </a>
                    <br>
                </div>
                <div class='paperAttribute'>
                    <span class='attributeLabel'>Submitted By: </span>
                    <?php 
                        $author = getRealName($paper['username']);
                        echo '<b>'.$author[0].' '.$author[1].'</b> from <b>'.$author[2].'</b> on '. 
                             date("M j, Y, g:i a", strtotime($paper['whenSubmitted']));
                    ?>
                    <br>
                </div>
                <div class='paperAttribute paperAttributeAlt'>
                    <span class='attributeLabel attributeLabelAlt'>Reviewer: </span>
                    <?php 
                        $reviewer = getRealName($paper['reviewername']);
                        echo '<b>'.$reviewer[0].' '.$reviewer[1].'</b> from <b>'.$reviewer[2].'</b> on '. 
                             date("M j, Y, g:i a", strtotime($paper['whenAssigned']));
                    ?>
                    <br>
                </div>
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
            </div>
            <br>
            <?php }
            } //ends awaitingInitialReview loop ?>
            
            <hr>
            <br>
            <h3>Papers with Initial Review Completed Awaiting Your Input:</h3>
            <?php if(empty($needsPostReviewStatus)) { ?>
            <p>There are no papers awaiting a status update at this time.</p>
            <?php } else { ?>
            <?php forEach ($needsPostReviewStatus as $paper) { ?>
            <div class='adminPaper'>
                <div class='paperAttribute'>
                    <span class='attributeLabel'>Draft Filename:</span>
                    <a target='_blank' href='uploads/drafts/<?php echo $paper['draftFilename'];?>'>
                       <?php echo htmlspecialchars($paper['draftFilename']);?>
                    </a>
                    <br>
                </div>
                <div class='paperAttribute'>
                    <span class='attributeLabel'>Submitted By: </span>
                    <?php 
                        $author = getRealName($paper['username']);
                        echo '<b>'.$author[0].' '.$author[1].'</b> from <b>'.$author[2].'</b> on '. 
                             date("M j, Y, g:i a", strtotime($paper['whenSubmitted']));
                    ?>
                    <br>
                </div>
                <div class='paperAttribute paperAttributeAlt'>
                    <span class='attributeLabel attributeLabelAlt'>Reviewer: </span>
                    <?php 
                        $reviewer = getRealName($paper['reviewername']);
                        echo '<b>'.$reviewer[0].' '.$reviewer[1].'</b> from <b>'.$reviewer[2].'</b> on '. 
                             date("M j, Y, g:i a", strtotime($paper['whenAssigned']));
                    ?>
                    <br>
                </div>
                <div class='paperAttribute paperAttributeAlt'>
                    <span class='attributeLabel attributeLabelAlt'>Reviewer Initial Recommendation: </span>
                    <?php 
                        switch ($paper['recommendation']) {
                            case "accept":
                                return "Accept As-Is";
                            case "reject":
                                return "Reject Draft";
                            case "minor":
                                return "Minor Revisions Needed";
                            case "major":
                                return "Major Revisions Needed";
                            default:
                                return "No Recommendation Made";
                        }
                    ?>
                    <br>
                </div>
                <div class='paperAttribute paperAttributeAlt'>
                    <span class='attributeLabel attributeLabelAlt'>Change Reviewer: </span>
                    <form method="post" action="index.php">
                        <select name="editorReview">
                            <option value='none'></option>
                            <option value='rr'>Request Revisions</option>
                            <option value='accept'>Accept As-Is</option>
                            <option value='reject'>Reject</option>
                        </select>
                        <input type='hidden' name='paperID' value='<?php echo $paper['paperID'];?>'>
                        <input type='hidden' name='adminPage' value='papers'>
                        <input type='submit' name='editorReview' value='Assign Reviewer'>
                    </form>
                    <br>
                </div>
            </div>
            <?php }
            } //ends needsPostReviewStatus loop ?>            
            
            
        </div>

    <?php } ?>
        
</div>

