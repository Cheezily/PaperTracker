<?php

$papers = getAllPapers();
$paperList = getPapersByStatus($papers);

var_dump($paperList);
?>

<div class="mainWrapperWithNav">
    <h2>Paper List</h2>
    <?php if(empty($papers)) { ?>
        <p>There are no papers in the database at the moment.</p>
    <?php } else { ?>
        <div class='adminPaperWrapper'>
            <div class='paperAttribute'>
                <?php echo "<span class='attributeLabel'>Draft Filename:</span> ".
                        "<a target='_blank' href='uploads/drafts/".$paper['draftFilename'].
                        "'>".htmlspecialchars($paper['draftFilename'])."</a>"; ?>
            </div>
        </div>
    
        
    <?php } ?>
        
</div>

