<?php
    if ( !Element( "element_logged_in" ) ) {
        return false;
    }

    $userid = $user->Id();
    
    $curbookmark = New Bookmark($_POST["bmid"]);
    if ( $curbookmark->UserId() == $userid ) {
        $curbookmark->ChangeLabel($_POST["bmlabel"]);
    }
    else {
        bc_die("Die!");
    }
?>