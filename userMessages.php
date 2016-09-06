<?php
require_once 'model/messagesDB.php';
$userMessages = getMessages($_SESSION['username']);

?>

<h2>Messages:</h2>
<div class='messageList'>
    <?php if (count($userMessages) == 0) {
        echo "<p>You have no messages at this time!</p>";
    } else {
        forEach ($userMessages as $message) {
            echo "<p class='messageLineHeader'>Sent on ".date("F j, Y, g:i a", 
                    strtotime($message['whenSent']))." by the Editor:</p>";
            echo "<p class='messageLineHeader'>Re: ".$message['title']."</p>";
            echo "<p class='messageLine'>".$message['message']."<p><hr>";
        }
    }
    ?>
</div>
<div class='messageAdmin'>
    <p class='tip'>Please use the following form to contact the administrator.  They
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

