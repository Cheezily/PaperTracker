<?php

if ($_POST['deletePaperConfirm']) {
    $deletePaperID = filter_input(INPUT_POST, 'paperID');
}

function getMessageCounts() {
    require_once 'model/adminDB.php';
    $newMessages = getNewMessages();
    //$oldMessages = count(getOldMessages());
    return $newMessages;
    
    //return array($newMessages, $oldMessages);
}


?>

