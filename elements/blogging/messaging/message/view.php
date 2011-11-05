<?php
    if ( !Element( "element_logged_in" ) ) {
        return false;
    }

    $msgid = $_POST["msgid"];
    $foldid = $_POST["foldid"];
    $curmsg = New Message($msgid);
    $curfold = New MessageFolder($foldid);
    $userid = $user->Id();
    $newmsgs = GetNewMessages($userid);
    if ($userid <> $curfold->FolderOwnerID()) {
        h3( "Access Denied" , "error64" );
        ?>This folder is not owned by you, thus you do not have any access to it and its contents. For any problems send a message to the administrator<?php
    }
    elseif( $curfold->FolderID() <> $curmsg->MessageFolderID() ){
        h3( "Access Denied" , "error64" );
        ?>This message is not owned by you, thus you do not have any access to it and its contents. For any problems send a message to the administrator<?php
    }
    else {
?>
<table width="100%">
<tr><td class="ffield" width="20%"><?php echo $arrow; ?><b>Subject:</b></td><td class="ffield"><?php
    $cursubj = $curmsg->MessageSubject();
    if ( !$cursubj ) {
        img('images/nuvola/messagebox_warning.png');
        ?> Without subject<?php
    }
    else {
        echo $cursubj;
    }
?></td></tr>
<tr><td class="nfield" width="20%"><?php echo $arrow; ?><b>Date:</b></td><td class="nfield"><?php echo BCDate($curmsg->MessageDate()); ?></td></tr>
<tr><td class="nfield" width="20%"><?php echo $arrow; ?><b>From:</b></td><td class="nfield"><?php
    if ( $curmsg->MessageSenderID() == 0 ) {
        ?><b>BlogCube System</b><?php
    }
    else {
        $tempsndr = New User($curmsg->MessageSenderID());
        echo "<a href=\"javascript:dm('profile/profile_view&user=", $tempsndr->Username() , "');\">", $tempsndr->Username() ,"</a>";
    }
?></td></tr>
<tr><td class="nfield"><?php echo $arrow; ?><b>To:</b></td><td class="nfield"><?php
$temprec = $curmsg->NextRecipient();
if ( $temprec == null ) { //draft, not sent yet
    $isdraft = true;
    img('images/nuvola/messagebox_warning.png');
    ?> No recipients yet<?php
}
else {
    $tempusr = New User($temprec);
    ?><a href="javascript:dm('profile/profile_view&user=<?php
    echo $tempusr->Username();
    ?>');"><?php
    echo $tempusr->Username();
    ?></a><?php
    while ( $temprec = $curmsg->NextRecipient() ) {
        $tempusr = New User($temprec);
        ?>, <a href="javascript:dm('profile/profile_view&user=<?php
        echo $tempusr->Username();
        ?>');"><?php
        echo $tempusr->Username();
        ?></a><?php
    }
}
?></td></tr>
<tr><td colspan="2"><p><?php echo nl2br($curmsg->MessageText()); ?></p></td></tr>
<tr><td colspan="2" class="nfield" align="center">
<?php
    if ( $isdraft ) {
        img('images/nuvola/mail_new.png'); ?> <a href="javascript:dm('messaging/message/compose&subj=<?php 
        echo safeencode($curmsg->MessageSubject()); 
        ?>&cont=<?php 
        echo safeencode($curmsg->MessageText()); 
        ?>');">Amend</a>&nbsp; &nbsp; <?php
        img('images/nuvola/delete.png'); 
        ?> <a href="javascript:de('msg_msgview','blogging/messaging/message/delete&delete_msgid=<?php 
        echo $msgid; 
        ?>');">Delete</a>&nbsp;&nbsp; <?php
    }
    else {
        if ( $curmsg->MessageSenderID() != 0 ) {
            img('images/nuvola/mail_reply.png'); 
            ?> <a href="javascript:dm('messaging/message/compose&reply=1&reply_msgid=<?php 
            echo $msgid; 
            ?>&reply_foldid=<?php 
            echo $foldid; 
            ?>');">Reply</a>&nbsp;&nbsp; <?php 
        }
        img('images/nuvola/delete.png'); 
        ?> <a href="javascript:de('msgstatus','blogging/messaging/message/delete&delete_msgid=<?php 
        echo $msgid; 
        ?>');">Delete</a>&nbsp;&nbsp; <?php
        img('images/nuvola/schedule.png'); 
        ?> <a href="javascript:de('msgstatus','blogging/messaging/spam/report&spam_msgid=<?php 
        echo $msgid; 
        ?>');">Report as Spam</a><?php
    }
?>
</td>
</tr>
</table>
<div id="msgstatus"></div>
<?php
    }
bfc_start();
?>
cacheable = true;
et('Message: <?php echo $curmsg->MessageSubject(); ?>');
<?php
    if ( $curmsg->IsUnread() ) {
        $curmsg->MarkAsRead();
        ?>
        msg_unmark('mmsg_<?php echo $msgid; ?>');
        mfolder_nm('<?php echo $foldid; ?>','<?php echo $curfold->CountUnread(); ?>');
        msg_newmsgs('<?php echo $newmsgs-1; ?>');
        cp( "blogging/topbarright" );
        cp( "blogging/messaging/panel" );
        cp( "blogging/messaging/message/newslist" );
        de( "topbarright" , "blogging/topbarright" , '' , '-' );
        <?php
    }
bfc_end();
?>