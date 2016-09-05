<?php

$messageCount = getMessageCounts();
$newMessages = $messageCount[0];
$oldMessages = $messageCount[1];

$papersCount = getPaperCounts();
$newPapers = $papersCount[0];
$oldPapers = $papersCount[1];

$usersCount = getUserCounts();
$newUsers = $usersCount[0];
$oldUsers = $usersCount[1];
?>

<div class="mainWrapper">
    <h2>Messages:</h2>
    <h3>New Messages to You Since Last Login: <?php echo $newMessages; ?></h3>
    <p>(Total messages to the Editor: <?php echo $newMessages + $oldMessages; ?>)</p>
    <br><br>
    
    <h2>Papers:</h2>
    <h3>New Papers Submitted Since Last Login: <?php echo $newPapers; ?></h3>
    <p>(Total papers submitted: <?php echo $newPapers + $oldPapers; ?>)</p>
    <br><br>
    
    <h2>Users:</h2>
    <h3>New Registered Users Since Last Login: <?php echo $newUsers; ?></h3>
    <p>(Total users registered: <?php echo $newUsers + $oldUsers; ?>)</p>
    <br>
    
</div>