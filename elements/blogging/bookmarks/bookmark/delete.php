<?php
    if ( !Element( "element_logged_in" ) ) {
        return false;
    }

    $userid = $user->Id();

    $curbookmark = New Bookmark($_POST["bmid"]);
    if ( $curbookmark->UserId() == $userid ) {
        $curbookmark->DeleteBookmark();
    }
    else {
        bc_die("Die!");
    }
?>