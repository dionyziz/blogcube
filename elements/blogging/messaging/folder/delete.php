<?php
    if ( !Element( "element_logged_in" ) ) {
        return false;
    }

    $curfoldid = $_POST["foldid"];
    $curfold = New MessageFolder($curfoldid);
    global $user;
    $userid = $user->Id();
    //prevents user from deleting folders that he does not own
    if ( $curfold->FolderOwnerID() != $userid ) {
        img('images/nuvola/error.png'); ?> Procedure failed. <?php
    }
    //prevents user from deleting special folders
    else if ( $curfold->FolderSpecial() != "none" ) {
        img('images/nuvola/error.png'); ?> Procedure failed. <?php
    }
    else {
        $curfold->RemoveFolder();
        img('images/nuvola/done.png'); ?> Folder "<?php echo $curfold->FolderName(); ?>" successfully deleted. <?php
    }
?>