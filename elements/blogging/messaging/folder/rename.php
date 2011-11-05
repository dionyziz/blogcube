<?php
    if ( !Element( "element_logged_in" ) ) {
        return false;
    }

    $curfoldid = $_POST["foldid"];
    $newfoldname = safedecode($_POST["newname"]);
    $curfold = New MessageFolder($curfoldid);
    global $user;
    $userid = $user->Id();
    if ( $curfold->FolderOwnerID() != $userid ) {
        img('images/nuvola/error.png'); ?> Procedure failed. <?php
    }
    else {
        $curfold->RenameFolder($newfoldname);
        img('images/nuvola/done.png'); ?> Folder successfully renamed. <?php
    }
?>