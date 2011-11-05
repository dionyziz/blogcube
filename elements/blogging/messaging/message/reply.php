<?php
    if ( !Element( "element_logged_in" ) ) {
        return false;
    }

    h3( "Reply to Message" , "mail_reply48" );
    $msgid = $_POST["reply_msgid"];
    $foldid = $_POST["reply_foldid"];
    $curmsg = New Message($msgid);
    $curfold = New MessageFolder($foldid);
    $userid = $user->Id();
    $cursenderid = $curmsg->MessageSenderID();
    if ($userid <> $curfold->FolderOwnerID()) {
        h3( "Access Denied" , "error64" );
        echo "You do not have access to this message. For any problems send a message to the administrator.";
    }
    else if ( $cursenderid == 0 ) {
        h3( "Error", "error64" );
        echo "You cannot reply to this message as it has been sent from the BlogCube System.";
    }
    else {
?>
        <table width="56%">
            <tr>
            <td class="ffield"><b>Subject:</b></td>
            <td class="ffield" align="right"><input type="text" value="Re: 
            <?php echo $curmsg->MessageSubject(); ?>
            " id ="message_subject" size="55"></td>
            </tr>
            <tr>
            <td class="nfield"><b>Recipients:</b></td>
            <td class="nfield" align="right"><input type="text" value="<?php 
                $cursender = New User($cursenderid);
                echo $cursender->Username();
            ?>
            " id ="message_recipients" size="55"></td>
            </tr>
            <tr>
            <td class="nfield" colspan="2"><textarea type="text" id="message_content" cols="52" rows="10"><?php
                $curbody = $curmsg->MessageText();
                $modbody = $cursender->Username() . " wrote:\n" . $curbody;
                $modbody = str_replace("\n","\r\n> ",$modbody);
                $modbody = "\n" . $modbody;
                echo $modbody;
            ?></textarea></td>
            </tr>
        </table>
        <table width="59%">
            <td style="width:48%;">
            <div id="msg_status">
            </td>
            <td align="right">
                <?php img('images/nuvola/schedule.png'); ?> <a href="javascript:msg_send(g('message_subject').value,g('message_recipients').value,g('message_content').value,'1');">Save As Draft</a>&nbsp;&nbsp;
                <?php img('images/nuvola/mail_send.png'); ?> <a href="javascript:msg_send(g('message_subject').value,g('message_recipients').value,g('message_content').value,'2');">Send</a>&nbsp;&nbsp;
                <?php img('images/nuvola/discard.png'); ?> <a href="javascript:dm('messaging/panel');">Cancel</a>
            </td>
        </table>
<?php
    }
bfc_start();
?>
et( "Reply to: <?php echo $curmsg->MessageSubject(); ?>" );
<?php
bfc_end();
?>