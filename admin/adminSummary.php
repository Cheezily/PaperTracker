<?php

$messageCount = getMessageCounts();
//$newMessages = $messageCount[0];
//$oldMessages = $messageCount[1];

?>

<div class="mainWrapper">
    <h1>New Messages to You: <?php echo $messageCount; ?></h1>
</div>