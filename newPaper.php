<?php if (isset($newPaperSubmitted) || isset($newPaperCancel)) { ?>
    <div class='paperSubmitted'>
<?php } elseif (isset($newPaperError)) { ?>
    <div class='newPaper'>
<?php } else { ?>
    <div class='newPaper newPaperAppear'>
<?php } ?>
        
    <form method='post' action='index.php' enctype="multipart/form-data">
        <label for='paperTitle'>Paper Title</label><br>
        <input type='text' name='paperTitle' id='paperTitle' required><br>
        <label for='paperFile'>Upload File (MS Word format)
            <?php if(isset($newPaperError)) {
                echo "<span class='miniWarning'>".$newPaperError."</span>";
            } ?>
        </label>
        <input type='file' class='upload' name='paperFile' id='paperFile' required><hr>
        <input type='submit' name='paperSubmit' value='Submit Paper'><br>
        
    </form>
        <form method='post' action='index.php'>
            <input type='submit' name='paperSubmitCancel' value='Cancel'>
        </form>
</div>
