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
?>