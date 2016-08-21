<?php
    //Handle when an author clicks to submit a new paper from the dashboard
    if (isset($_POST['newPaper'])) {
        $newPaper = TRUE;
    }
    
    //Handle when an author clicks to cancel new paper submission from the newPaper.php dialog
    if (isset($_POST['paperSubmitCancel'])) {
        $newPaper = TRUE;
        $newPaperCancel = TRUE;
    }
    
    //Handle when the author submits a new paper from the newPaper.php dialog
    if (isset($_POST['paperSubmit'])) {
        $title = filter_input(INPUT_POST, 'paperTitle');
        if ($title == FALSE) {
            $newPaperError = "Please enter a paper title";
        }
        //prevents duplicate submissions when the user refreshes
        if ($_SESSION['titleSubmitted'] != $title) {
            
            $newPaper = TRUE;
            $randomPad = rand(1000, 9999);
            //var_dump($_FILES);
            $doc_dir = getcwd().'/uploads/drafts/';
            $doc_file  = $doc_dir.$randomPad."-".basename($_FILES["paperFile"]["name"]);
            $filetype = pathinfo($doc_file, PATHINFO_EXTENSION);
            //echo $filetype;
            if ($_FILES["paperFile"]["size"] > 10000000) {
                $newPaperError = "Filesize must be less than 10MB";
            }
            if ($filetype != "doc" && $filetype != "docx") {
                $newPaperError = "File must be a MS Word file";
            }
            if (file_exists($doc_file)) {
                $newPaperError = "File already exists";
            }
            if (!isset($newPaperError)) {
                if (move_uploaded_file($_FILES["paperFile"]["tmp_name"], $doc_file)) {
                    require_once 'model/papersDB.php';
                    uploadDraft($_SESSION['username'],
                            $randomPad."-".basename($_FILES["paperFile"]["name"]), 
                            $title);
                    $newPaperSubmitted = TRUE;
                } else {
                    $newPaperError = "Something went wrong. Please try again.";
                }
            }
            $_SESSION['titleSubmitted'] = $title;
        }
    }
    
    //Handle author revision upload
    if (isset($_POST['revisionSubmit'])) {
        $paperID = filter_input(INPUT_POST, 'paperID', FILTER_VALIDATE_INT);
        require_once 'model/papersDB.php';
        if ($paperID && !checkForRevision($paperID)) {
            $randomPad = rand(1000, 9999);
            $doc_dir = getcwd().'/uploads/revisions/';
            $doc_file  = $doc_dir.$randomPad."-".basename($_FILES["revisionFile"]["name"]);
            $filetype = pathinfo($doc_file, PATHINFO_EXTENSION);
            if ($_FILES["revisionFile"]["size"] > 10000000) {
                $revisionError = "Filesize must be less than 10MB";
            }
            if ($filetype != "doc" && $filetype != "docx") {
                $revisionError = "File must be a MS Word file";
            }
            if (file_exists($doc_file)) {
                $revisionError = "File already exists";
            }
            if (!isset($revisionError)) {
                if (move_uploaded_file($_FILES["revisionFile"]["tmp_name"], $doc_file)) {
                    uploadRevision($paperID, $randomPad."-".basename($_FILES["revisionFile"]["name"]));
                } else {
                    $revisionError = "Something went wrong. Please try again.";
                }
            }
        }
    }
?>