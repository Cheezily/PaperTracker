<?php
    require_once 'model/messagesDB.php';
    //Message sent alert needs to be cleared on each reload
    $messageStatus = NULL;

    //Handle when a message is sent
    if (isset($_POST['sendMessage'])) {
        if ($_SESSION['messageSent'] != $_POST['message']) {
            
            $message = filter_input(INPUT_POST, 'message');
            $fromUsername = filter_input(INPUT_POST, 'fromUsername');
            $messageTitle = filter_input(INPUT_POST, 'messageTitle');

            //this is run through the session var so it doesn't resend the message
            //each time the user refreshes the page
            $_SESSION['messageSent'] = $message;

            $messageStatus = sendMessage($fromUsername, $message, $messageTitle);
        }
    }
    
    if (isset($_POST['replyToMessage'])) {
            require_once 'model/messagesDB.php';
            $reply = filter_input(INPUT_POST, 'reply');
            $messageID = filter_input(INPUT_POST, 'messageID', FILTER_VALIDATE_INT);
            $adminPage = "adminMessages.php";
            
            replyToMessage($messageID, $reply);
    }
    
    function getMessageList($fromUsername) {
        $messages = getMessages($fromUsername);
        $repliedMessages = array();
        $noRepliesYet = array();
        
        forEach($messages as $message) {
            if (!empty($message['reply'])) {
                array_push($repliedMessages, $message);
            } else {
                array_push($noRepliesYet, $message);
            }
        }
        
        return array($repliedMessages, $noRepliesYet);
    }

    
    function readEditorNote($paper) {
    
    $noteButton = '';
    
    if ($paper['editorNotes']) {
        
        $noteDate = date('M j, Y, g:i a', strtotime($paper['whenEditorNotes']));
        $buttonTitle = "Editor Note Submitted on ".$noteDate." -- Click to View";
        $noteButton = "<div class='noteButtonWrapper'>".
                        "<button class='adminNoteButton' paperID=".$paper['paperID'].">".$buttonTitle.
                        "</button>".
                    "</div>";
    } else {
        $noteButton = "<div class='attributeLabel paperAttribute'>There are no notes from the Editor yet for this paper.</div>";
        /*$noteButton = "<div class='noteButtonWrapper'>".
                        "<button class='adminNoteButton adminNoteButton1' paperID=".$paper['paperID'].">".
                        $buttonTitle.
                        "</button>".
                    "</div>";*/
    }
    
    $output = $noteButton."<div class='adminPaperNote' id='makeNoteFor".$paper['paperID']."'>".
                "<div class='adminNoteHeading'>Note for <b>".$paper['title']."</b> by the Editor".
                "</div><hr>".
                "<div class='paperNote' id='textFor".$paper['paperID']."'>".
                    ($paper['editorNotes']).
                "</div><hr>".
            "<button type='button' class='closeNoteButton' paperID=".$paper['paperID'].">Close</button>".
            "</div>";
    
    return $output;
}
?>