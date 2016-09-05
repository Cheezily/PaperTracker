<?php
    $messages = getMessageLists();
    $needsReply = $messages[0];
    $alreadyReplied = $messages[1];
?>

<div class="mainWrapper">
    <h2>Messages Awaiting Reply:</h2>
    <?php
        if ($needsReply > 0) {
            forEach ($needsReply as $message) { ?>
                <div class="adminMessage">
                    <div class="messageLine">
                        <?php echo "From: ".$message['fromUsername']." on ".
                                $message['whenSent'];
                        ?>
                    </div>
                    <div class="messageLine">
                        <?php echo "Message: ".$message['message']; ?>
                    </div>
                </div>
            <?php }
        } else {
            echo "<p>No messages currently waiting for a reply</p>";
        }
    ?>
    <hr>
    <h2>Messages Replied To:</h2>
    <?php
        if ($alreadyReplied > 0) {
            forEach ($alreadyReplied as $message) { ?>
                <div class="adminMessage">
                    <div class="messageLine">
                        <?php echo "From: ".$message['fromUsername']." on ".
                                $message['whenSent'];
                        ?>
                    </div>
                    <div class="messageLine">
                        <?php echo "Message: ".$message['message']; ?>
                    </div>
                </div>
            <?php }
        } else {
            echo "<p>No messages Replied To</p>";
        }
    ?>
</div>
