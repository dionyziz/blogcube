<?php
    if ( !Element( "element_logged_in" ) ) {
        return false;
    }

    $id = $_POST["mfid"];
    $showmsgs = $_POST["showmsgs"];
    $mfolder_popup = Array();
    $FolderAttr = New MessageFolder($id);
    $FolderAttr->SetExpanded();
    global $user;
    $userid = $user->Id();
    if ( $FolderAttr->FolderOwnerID() != $userid ) {
        img('images/nuvola/messagebox_warning.png'); ?>This folder is not owned by you, thus you cannot see its content.
    <?php
    }
    else {
        $trashfoldid = GetUserSpecialFolder($userid,"trash");
        $i=0;
        while($curfoldid = $FolderAttr->GetFolder()) {
            $mfolder_popup[$i] = PopupMenu( Array (
                Array( "Rename Folder", "javascript:mfolder_rename(" . $curfoldid . ");" ) ,
                Array( "Delete Folder", "javascript:mfolder_delete(" . $curfoldid . ");" ) ,
                Array( "Create Subfolder", "javascript:mfolder_add(" . $curfoldid . ");" )
            ) );
            if ( $curfoldid != $trashfoldid ) {
                $curfold = New MessageFolder($curfoldid);
                img('images/nuvola/mfolder.png'); ?> <a href="javascript:mfolder_sh('mfolder_<?php echo $curfoldid; ?>','<?php echo $curfoldid; ?>','<?php echo $showmsgs; ?>','<?php echo $curfold->FolderName(); ?>');" <?php PopupAnchor( $mfolder_popup[$i] ); ?>><?php
                echo $curfold->FolderName(), "(", $curfold->IsExpanded(), ")";
                $unreadcount = $curfold->CountUnread();
                if ( $unreadcount > 0 ) {
                    ?><div id="mfolder_<?php echo $curfoldid; ?>_nm" class="inline"><?php echo "<b>(", $unreadcount, ")</b>" ?></div><?php
                }
                ?></a><blockquote><div style="display:none" id="mfolder_<?php echo $curfoldid; ?>" /></blockquote><?php
                if ( $curfold->IsExpanded() == "yes" ) {
                    bfc_start();
                    ?>
                    mfolder_sh('mfolder_<?php echo $curfoldid; ?>','<?php echo $curfoldid; ?>','<?php echo $showmsgs; ?>','<?php echo $curfold->FolderName(); ?>');
                    <?php
                    bfc_end();
                }
            }
            $i++;
        }
        if ($showmsgs != "false") {
            $i = 0;
            while($curmsgid = $FolderAttr->GetMessage()) {
                $msg_popup[$i] = PopupMenu( Array (
                    Array( "Reply...", "#" ) ,
                    Array( "Delete...", "#" )
                ) );
                $curmsg = New Message($curmsgid);
                $sender = New User($curmsg->MessageSenderID());
                $senderid = $sender->Id();
                $d = $curmsg->IsUnread();
                ?> <div id="mmsg_<?php echo $curmsgid; ?>" <?php if (( $d == true ) && ( $senderid != $userid )) { echo "style=\"font-weight: bold;\""; } ?>><?php img('images/nuvola/email.png'); ?> <a href="javascript:de('msg_msgview','blogging/messaging/message/view&msgid=<?php echo $curmsgid; ?>&foldid=<?php echo $id; ?>');" <?php PopupAnchor( $msg_popup[$i] ); ?>><?php
                $cursubj = $curmsg->MessageSubject();
                if ( !$cursubj ) {
                    echo "<i>Without subject</i>";
                }
                else {
                    echo $cursubj;
                }
                ?></a></div><?php
                $i++;
            }
        }
    }
?>