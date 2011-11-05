<?php
    if ( !Element( "element_logged_in" ) ) {
        return false;
    }

    $id = $_POST["spam_msgid"];
    $curmsg = New Message($id);
    $curmsg->ReportAsSpam();
    global $user;
    $userid = $user->Id();
    $spamfolder = GetUserSpecialFolder($userid,"spam");
    if ( $curmsg->MoveToFolder($spamfolder) == false ) {
        img("images/nuvola/error.png");
        echo $curmsg->GetErrorText();
    }
    else {
        img('images/nuvola/done.png'); ?>Message successfully reported as spam. Thanks!<br /><?php
        img('images/nuvola/back.png'); ?><a href="javascript:de('msg_msgview','blogging/messaging/message/view&msgid=<?php echo $id; ?>&foldid=<?php echo $curmsg->MessageFolderID(); ?>');">Go Back</a><?php
    }
?>