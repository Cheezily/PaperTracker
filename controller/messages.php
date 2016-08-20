<?php
    //Message sent alert needs to be cleared on each reload
    $messageStatus = NULL;

    //Handle when a message is sent
    if (isset($_POST['sendMessage'])) {
        if ($_SESSION['messageSent'] != $_POST['message']) {
            require_once 'model/messagesDB.php';
            $message = filter_input(INPUT_POST, 'message');
            $fromUsername = filter_input(INPUT_POST, 'fromUsername');
            $messageTitle = filter_input(INPUT_POST, 'messageTitle');
            //echo $message."<br>".$fromUsername."<br>".$messageTitle;
            if (!isset($_POST['toUsername'])) {
                $toUsername = "admin";
            } else {
                $toUsername = filter_input(INPUT_POST, 'toUsername');
            }
            
            //this is run through the session var so it doesn't resend the message
            //each time the user refreshes the page
            $_SESSION['messageSent'] = $message;

            $messageStatus = sendMessage($fromUsername, $toUsername, $message, $messageTitle);
        }
    }
?>