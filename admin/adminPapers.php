<?php

$papers = getAllPapers();
$paperList = getPapersByStatus($papers);
$needsAssignment = $paperList["needsAssignment"];

forEach ($paperList as $paper) {
    var_dump($paper);
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
            </div>
            <?php } ?>

        </div>
    
        
    <?php } ?>
        
</div>

