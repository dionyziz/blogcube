<?php
    if ( !Element( "element_logged_in" ) ) {
        return false;
    }

    if ( $_POST["reply"] == 1 ) {
        h3( "Reply to Message" , "mail_reply48" );
        $msgid = $_POST["reply_msgid"];
        $foldid = $_POST["reply_foldid"];
        $curmsg = New Message($msgid);
        $curfold = New MessageFolder($foldid);
        $userid = $user->Id();
        $cursenderid = $curmsg->MessageSenderID();
        $cursender = New User($cursenderid);
        if ($userid <> $curfold->FolderOwnerID()) {
            h3( "Access Denied" , "error64" );
            bc_die("You do not have access to this message. For any problems send a message to the administrator.");
        }
        else if ( $cursenderid == 0 ) {
            h3( "Error", "error64" );
            bc_die("You cannot reply to this message as it has been sent from the BlogCube System.");
        }
        $_POST["subj"] = $curmsg->MessageSubject();
        $_POST["recip"] = $cursender->Username();
    }
    else { h3( "Compose Message" , "mail_new48" ); }
?>

<table width="56%">
    <tr>
    <td class="ffield"><b>Subject:</b></td>
    <td class="ffield" align="right"><input type="text" value="<?php if ( isset($_POST["subj"]) ) { echo safedecode($_POST["subj"]); } ?>" id="message_subject" size="55" class="messagesubj"></td>
    <tr>
    <td class="messageerrfield" colspan="2" id="msg_ferror_1"><div id="msg_error_1" style="display:none;"></div></td>
    </tr>
    </tr>
    <tr>
    <td class="nfield"><b>Recipients:</b></td>
    <td class="nfield" align="right"><input type="text" value="<?php if ( isset($_POST["recip"]) ) { echo safedecode($_POST["recip"]); } ?>" id="message_recipients" size="55" class="messagerecip"></td>
    <tr>
    <td class="messageerrfield" colspan="2" id="msg_ferror_2"><div id="msg_error_2" style="display:none;"></div></td>
    </tr>
    </tr>
    <tr>
    <td class="nfield" colspan="2"><textarea type="text" id="message_content" cols="64" rows="10"><?php
        if ( $_POST["reply"] == 1 ) {
            $curbody = $curmsg->MessageText();
            $modbody = $cursender->Username() . " wrote:\n" . $curbody;
            $modbody = str_replace("\n","\r\n> ",$modbody);
            $modbody = "\n" . $modbody;
            echo $modbody;
        }
        else if ( isset($_POST["cont"]) ) {
            echo safedecode($_POST["cont"]);
        }
    ?></textarea></td>
    </tr>
</table>
<?php
    IconLL( Array(
        Array( "Send" , "javascript:msg_send(g('message_subject').value,g('message_recipients').value,g('message_content').value,'2');" , "mail_send" ) ,
        Array( "Save as Draft" , "javascript:msg_send(g('message_subject').value,g('message_recipients').value,g('message_content').value,'1');" , "save" ) , 
        Array( "Cancel" , "javascript:dm('messaging/panel');" , "discard" )
    ) );
?>
<br />
<div id="msg_status"></div>

<?php
    BCTip( "Seperate multiple recipients using a comma." );

    bfc_start();
?>
et( "Compose Message" );
<?php
bfc_end();
?>