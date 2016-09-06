<?php
    $messages = getMessageLists();
    $needsReply = $messages[0];
    $alreadyReplied = $messages[1];
?>

<div class="mainWrapperWithNav">
    <h2>Messages Awaiting Reply:</h2>
    <div class='adminMessageList'>
        <?php
            if ($needsReply > 0) {
                forEach ($needsReply as $message) { ?>
                    <div class="adminMessage">
                        <p class="messageLineHeader">
                            <?php echo "From: ".$message['fromUsername']." on ".
                                    $message['whenSent'];
                            ?>
                        </p>
                        <p class="messageLineHeader">
                            <?php echo "Re: ".$message['title']; ?>
                        </p>
                        <p class="messageLine">
                            <?php echo "Message: ".$message['message']; ?>
                        </p>
                        <hr>
                        <form method='post' action='index.php'>
                            <input type='hidden' name='messageID' placeholder="Your Reply..."
                                   value=<?php echo $message['messageID']; ?>>
                            <textarea class='replyText' name='reply'></textarea>
                            <input class='replySubmit' type='submit' name='replyToMessage' value='Submit'>
                        </form>
                    </div>
                <?php }
            } else {
                echo "<p>No messages currently waiting for a reply</p>";
            }
        ?>
    </div>
    <hr>
    <br>
    <h2>Messages Already Replied To:</h2>
    <div class='adminMessageList'>
        <?php
            if ($alreadyReplied > 0) {
                forEach ($alreadyReplied as $message) { ?>
                    <div class="adminMessageOld">
                        <p class="messageLineHeader">
                            <?php echo "From: ".$message['fromUsername']." on ".
                                    $message['whenSent'];
                            ?>
                        </p>
                        <p class="messageLineHeader">
                            <?php echo "Re: ".$message['title']; ?>
                        </p>
                        <p class="messageLine">
                            <?php echo "Message: ".$message['message']; ?>
                        </p>
                        <hr>
                        <p class="messageLineHeader">
                            <?php echo "Reply Sent On: ".$message['whenReplied']; ?>
                        </p> 
                        <p class="messageLine">
                            <?php echo "Reply: ".$message['reply']; ?>
                        </p>                        
                    </div>
                <?php }
            } else {
                echo "<p>No messages Replied To</p>";
            }
        ?>
    </div>
</div>
