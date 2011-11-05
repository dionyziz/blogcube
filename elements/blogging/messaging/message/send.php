<?php
    if ( !Element( "element_logged_in" ) ) {
        return false;
    }

    $subject = safedecode($_POST["msg_subject"]);
    $recipients = safedecode($_POST["msg_recipients"]);
    $content = safedecode($_POST["msg_content"]);
    $type = safedecode($_POST["msg_type"]);
    global $user;
    $userid = $user->Id();
    $error = "";
    if ( $type == 2 ) {
        $reciptok = strtok($recipients,",");
        while ($reciptok) {
            $currecip = GetUserByUsername($reciptok);
            if ( !$currecip ) { $error .= "User " .  $reciptok . " does not exist!<br />"; }
            else if ( $currecip->Id() == $user->Id() ) {
                $error .= "You cannot send a message to yourself<br />";
            }
            $reciptok = strtok(",");
        }
        if ( $error != "" ) {
            bfc_start();
            ?>msg_showerror('<?php echo $error; ?>',2);<?php
            bfc_end();
        }
        else {
            $MsgID = CreateMessage($subject,$content,$type);
            $msg = New Message($MsgID);
            $reciptok = strtok($recipients,",");
            while ($reciptok) {
                $currecip = GetUserByUsername($reciptok);
                $msg->AddRecipient($currecip->Id());
                $reciptok = strtok(",");
            }
            $msg->SendMessage();
            $reciptok = strtok($recipients,",");
            while ($reciptok) {
                $currecip = GetUserByUsername($reciptok);
                if ( $currecip->Id() != $userid ) {
                    //filters the message on every recipient
                    $curmsg = New Message($MsgID);
                    $curmsg->SetFolderID($currecip->Id());
                    //if msg isn't spam, filter it (CheckForSpams automatically moves it to spam folder)
                    if ( !$curmsg->CheckForSpams($currecip->Id()) ) $curmsg->CheckForFilters($currecip->Id());
                    $reciptok = strtok(",");
                }
            }
            $selfcheck = GetUserByUsername($recipients);
            if ( $selfcheck->Id() != $userid ) { 
                img('images/nuvola/done.png'); ?> Message successfully sent.<?php
                bfc_start();
                ?>
                cp('messaging/panel');
                dm('messaging/panel&msent=1');
                <?php
                bfc_end();
            }
        }
    }
    else {
        $MsgID = CreateMessage($subject,$content,$type);
        img('images/nuvola/done.png'); ?> Message successfully saved.<?php
        bfc_start();
        ?>
        cp('messaging/panel');
        dm('messaging/panel&msent=1');
        <?php
        bfc_end();
    }
?>