<?php
require_once 'controller/messages.php';
$userMessages = getMessageList($_SESSION['username']);
$repliedMessages = $userMessages[0];
$needsReply = $userMessages[1];
//var_dump($repliedMessages);
?>

<h2>Replies to your Messages:</h2>
<div class='messageList'>
    <?php if (count($repliedMessages) == 0) {
        echo "<p>You have no messages with replies at this time!</p>";
    } else {
        forEach ($repliedMessages as $message) { ?>
            <div class='messageWraper'>
                <p class='messageLineHeader'>Your Message Sent On <?php 
                    echo date("M j, Y, g:i a", strtotime($message['whenSent'])); ?>
                </p>
                <p class='messageLineHeader'>Re: <?php echo $message['title']; ?></p>
                <p class='messageLine'>Message: <?php echo $message['message']; ?><p>
                <p class='messageLineHeader'>Editor Replied On <?php 
                    echo date("M j, Y, g:i a", strtotime($message['whenReplied'])); ?>
                </p>
                <p class='messageLine'>Reply: <?php echo $message['reply']; ?><p>
            </div>
        <?php }
    } ?>
</div>
<hr>
<br>
<h2>Your Messages Awaiting a Reply:</h2>
<div class='messageList'>
    <?php if (count($needsReply) == 0) {
        echo "<p>You have no messages that need a reply!</p>";
    } else {
        forEach ($needsReply as $message) { ?>
            <div class='messageWraper'>
                <p class='messageLineHeader'>Your Message Sent On <?php 
                    echo date("M j, Y, g:i a", strtotime($message['whenSent'])); ?>
                </p>
                <p class='messageLineHeader'>Re: <?php echo $message['title']; ?></p>
                <p class='messageLine'>Message: <?php echo $message['message']; ?><p>
            </div>
        <?php }
    } ?>
</div>
<hr>
<br>
<div class='messageAdmin'>
    <p class='tip'>Please use the following form to contact the Editor.  They
    are responsible for assigning your papers to reviewers as well as 
    handling any requests to delete submitted papers from the system.</p>
    <form method='post' action='index.php'>
        <label class='paperTitleLabel' for='selectPaperTitle'>Which paper is this about?</label><br>
        <select class='selectPaperTitle' id='selectPaperTitle' name='messageTitle' required>
            <?php forEach ($yourPapers as $paper) {
                echo "<option value='".$paper['title']."'>".$paper['title']."</option>";
            } ?>
            <option value='General Question' selected>General Question - No specific paper</option>
        </select>
        <br>
        <textarea class='messageText' name='message' placeholder="Type your message here..." required></textarea>
        <br>
        <input type='hidden' name='fromUsername' value='<?php echo $_SESSION['username']; ?>'>
        <input class='submitButton' type='submit' name='sendMessage' value='Send Message'>
        <?php //echo "STATUS: ".$_SESSION['messageSent']; ?>
        <?php if(isset($_SESSION['messageSent'])) {
            //echo "STUFF";
            echo "<span class='alert'>".$messageStatus."</span>";
        } ?>
    </form>
</div>

