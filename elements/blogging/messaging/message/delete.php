<?php
    if ( !Element( "element_logged_in" ) ) {
        return false;
    }

    $id = $_POST["msgid"];
    $userid = $user->Id();
    $trashfolder = GetUserSpecialFolder($userid,"trash");
    $curmsg = New Message($id);
    if ( $curmsg->MoveToFolder($trashfolder) == false ) {
        img("images/nuvola/error.png");
        echo $curmsg->GetErrorText();
    }
?>