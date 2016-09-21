<?php

$papers = getAllPapers();
$paperList = getPapersByStatus($papers);
$needsAssignment = $paperList["needsAssignment"];
$awaitingReview = $paperList["awaitingReview"];
$awaitingAuthorUpdate = $paperList["awaitingAuthorUpdate"];
$accepted = $paperList["accepted"];
$rejected = $paperList["rejected"];
$recentlyUpdated = $paperList["recentlyUpdated"];

$reviewerOptions = reviewerOptionList();


forEach ($paperList as $paper) {
    //var_dump($paper);
    echo "<br>";
}
?>

<div class="adminPaperWrapper">
    <h2>Paper List</h2>
    <?php if(empty($papers)) { ?>
        <p>There are no papers in the database at the moment.</p>
    <?php } else { ?>
        <div class='mainWrapperWithNav'>
            <?php forEach ($needsAssignment as $paper) { ?>
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
                    $author = getAuthor($paper['username']);
                    echo $author[0].' '.$author[1].' on '. 
                         date("F j, Y, g:i a", strtotime($paper['whenSubmitted']));
                ?>
                <br>
            </div>
            <div class='paperAttribute'>
                <span class='attributeLabel'>Assign Reviewer: </span>
                <form method="post" action="">
                    <select name="reviewer">
                        <?php echo $reviewerOptions; ?>
                    </select>
                </form>
                
                <br>
            </div>
            <?php } ?>

        </div>
    
        
    <?php } ?>
        
</div>

