<?php
    if ( !Element( "element_logged_in" ) ) {
        return false;
    }

    h3( "My Messages" , "email64" );
    global $user;
    $userid = $user->Id();
    $newmsgs = GetNewMessages( $userid );
    $frootid = GetUserRoot( $userid );
    if ( $newmsgs > 0 ) { 
        ?>
        <table align="center" width="100%"><tr><td>
        <p align="center"><div id="msg_newmsgs" class="newmessages"></div></p>
        </td></tr></table><br />
        <?php
    }
?>
<div id="hmpanel" style="display: none;" ></div>
<div id="mpanel_status">
<?php
    if ( $_POST["msent"] == 1 ) {
        img('images/nuvola/done.png');
        ?> Message successfully sent.<?php
    }
?>
</div>
<?php
    $allmessageids = Array();
    function AddMessage( $mid , $fid , $isfirst = false ) {
        global $allmessageids;
        $mobj = New Message($mid);
        $mfold = $mobj->MessageFolderID();
        $munread = $mobj->IsUnread();
        $msg_popup[$mid] = PopupMenu( Array (
            Array( "Reply", "javascript:dm('messaging/message/compose&reply=1&reply_msgid=" . $mid . "&reply_foldid=" . $mfold . "');") ,
            Array( "Delete Message", "javascript:msg_delete(" . $mid . ");" )
        ) );
        $allmessageids[] = $mid;
        ?>
        <div class="messagesmallcard <?php
            if ( $isfirst == true ) {
                echo "messagefirst";
            }
        ?>" id="messagesmallcard_<?php
        echo $mid;
        ?>" onmouseover="messagecardover(<?php
        echo $mid;
        ?>)" onmouseout="messagecardout(<?php
        echo $mid;
        ?>)">
        <?php
        if ( $munread ) {
        img( "images/silk/email.png" );
        }
        else {
        img( "images/silk/email_open.png" );
        }
        ?> <a href="javascript:dm('messaging/message/view&msgid=<?php
        echo $mid;
        ?>&foldid=<?php
        echo $fid;
        ?>');" <?php PopupAnchor( $msg_popup[$mid] ); ?>><?php
        if ( $munread ) echo "<b>";
        if ( $mobj->MessageSubject() == "" ) {
            echo "<i>No subject</i>";
        }
        else {
            echo $mobj->MessageSubject();
        }
        if ( $munread ) echo "</b>";
        ?></a>
        </div>
        <div class="messagecard" id="messagecard_<?php
            echo $mid;
        ?>">
        <b>Subject:</b> <?php
        echo $mobj->MessageSubject();
        ?><br />
        <b>Date:</b> <?php
        echo BCDate($mobj->MessageDate());
        ?><br />
        <b>Sender:</b> <?php
        $msender = New User($mobj->MessageSenderID());
        echo $msender->Username();
        ?><br /><br />
        <?php
        echo str_replace("<br />","",substr($mobj->MessageText(),0,80));
        if ( strlen($mobj->MessageText()) > 80 ) echo "...";
        ?>
        </div>
        <?php
    }
    $trashfid = GetUserSpecialFolder( $userid , "trash" );
    function AddFolder( $ftree , $isroot = false , $isfirst = false ) {
        global $user;
        global $allmessageids;
        global $trashfid;
        global $allfolders;

        $fobj = $ftree->GetFolder(); // New MessageFolder( $fid );
        $fid = $fobj->FolderID();
        $userid = $user->Id();
        if ( $fid == $trashfid ) return 0;
        $mfolder_popup[ $fid ] = PopupMenu( Array (
            Array( "Rename Folder", "javascript:mfolder_rename(" . $fid . ");" ) ,
            Array( "Delete Folder", "javascript:mfolder_delete(" . $fid . ");" ) ,
            Array( "Create Subfolder", "javascript:mfolder_add(" . $fid . ");" )
        ) );
        $allmessageids[] = "f" . $fid;
        ?><div class="messagesfolder<?php
            if ( $isroot == true ) echo "root";
            if ( $isfirst == true ) echo " messagefirst";
        ?>" id="allmfolder_<?php
            echo $fid;
        ?>" onmouseover="messagecardover('f<?php
        echo $fid;
        ?>')" onmouseout="messagecardout('f<?php
        echo $fid;
        ?>')"><?php
        if ( $fobj->CountMessages() + $fobj->CountFolders() == 0 ) {
            img( "images/silk/folder_error.png" , "folder" , "Empty Messages Folder" );
        }
        else {
            img( "images/silk/folder.png" , "folder" , "Messages Folder" );
            ?>
            <a style="cursor: default;" href="javascript:mfolder_sh('mfolder_<?php echo $fid; ?>');" <?php PopupAnchor( $mfolder_popup[$fid] ); ?>><?php
        }
        ?> <div class="inline" id="mfolder_<?php
            echo $fid;
        ?>_name" style="color:#3979C1; cursor: default;"><?php
        if ( $isroot == true ) echo "My Messages";
        else echo $fobj->FolderName();
        ?></div><?php
        $funread = $fobj->CountUnread();
        if ( $funread == 0 ) {
            ?></a><?php
        }
        else { //show unread
            ?>
            <div id="mfolder_<?php
            echo $fid;
            ?>_nm" class="inline"><b>(<?php
            echo $funread;
            ?>)</b></div></a><?php
        }
        ?>
        <div id="mfolder_<?php
        echo $fid;
        ?>" style="margin-top: 3px;<?php
        if ( $isroot == false ) {
            ?> display: none;<?php
        }
        ?>"><?php
        $k = true;
        while( $cfid = $ftree->Child() ) {
            AddFolder( $cfid , false , $k );
            if ( $k == true ) $k = false;
        }
        while( $cmid = $fobj->GetMessage() ) {
            AddMessage( $cmid , $fid , $k );
            if ( $k == true ) $k = false;
        }
        ?></div>
        </div>
        <div class="messagecard" id="messagecard_f<?php //messagecard
            echo $fid;
        ?>" style="height: 45px;"><?php
            if ( $fobj->CountMessages() + $fobj->CountFolders() == 0 ) {
                ?>
                This folder is empty; drag-n-drop a message to move it here
                <?php
            }
            else {
                echo $fobj->CountFolders(); ?> <b>folders</b><br /><?php
                echo $fobj->CountMessages(); ?> <b>messages</b> (<?php
                echo $funread ?> <b>unread</b>)    <?php
            }
        ?></div>
        <?php
    }
    $allfolders = Messaging_FetchUserTree( $userid );
    AddFolder( $allfolders[$frootid] , true , true );
    ?><br /><?php
    img("images/nuvola/mail_new.png");
?> <a href="javascript:dm('messaging/message/compose');">Compose Message</a><br />
<?php
    img("images/silk/magnifier.png");
?> <a href="javascript:dm('messaging/filter/panel');">Manage Filters</a>
<?php
    $bfc->start();
    ?>
    allmessageids = new Array('<?php
    if ( count($allmessageids) == 1 ) {
        ?>1'); allmessageids[0] = <?php
        echo $allmessageids[0];
        ?>;<?php
    }
    else {
        echo implode("','", $allmessageids);
        ?>');<?php
    }?>
    et('Messaging');
    <?php
    if ( $newmsgs > 0 ) {
        ?>msg_newmsgs(<?php echo $newmsgs; ?>);<?php
    }
    $bfc->end();
?>