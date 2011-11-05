<?php
    if ( !Element( "element_logged_in" ) ) {
        return false;
    }

    $curfoldid = $_POST["foldid"];
    $newfoldname = $_POST["foldname"];
    $curfold = New MessageFolder($curfoldid);
    global $user;
    $userid = $user->Id();
    if ( $curfold->FolderOwnerID() != $userid ) {
        img('images/nuvola/error.png'); ?> Procedure failed. <?php
    }
    else {
        $curfold->AddFolder($newfoldname);
        img('images/nuvola/done.png'); ?> Folder successfully added. <?php
        //TODO: add the folder in the foldview using js
    }
?>