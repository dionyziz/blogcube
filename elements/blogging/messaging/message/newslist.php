<?php
    if ( !Element( "element_logged_in" ) ) {
        return false;
    }

    $newmsgs = Array();
    $newmsgs = GetNewMsgIDs($user->Id());
?>
Here's a list of the unread messages:<br /><br />
<table width="100%">
    <tr>
        <td><b>Subject</b></td>
        <td><b>Sender</b></td>
        <td><b>Date</b></td>
    </tr>
    <?php
        for($i=0;$i<count($newmsgs);$i++) {
            $curmsg = New Message($newmsgs[$i]);
            ?>
            <tr>
                <td><a href="javascript:de('msg_msgview','blogging/messaging/message/view&msgid=<?php echo $newmsgs[$i]; ?>&foldid=<?php echo $curmsg->MessageFolderID(); ?>');"><?php echo substr($curmsg->MessageSubject(),0,25); ?></a></td>
                <td><?php
                $cursender = New User($curmsg->MessageSenderID());
                echo $cursender->Username();
                ?></td>
                <td><?php echo BCDate($curmsg->MessageDate()); ?></td>
            </tr>
            <?php
        }
    ?>
</table>