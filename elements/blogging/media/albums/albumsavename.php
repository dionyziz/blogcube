<?php
    if ( !Element( "element_logged_in" ) ) {
        return false;
    }

    $albumid = $_POST[ "albumid" ];
    $albumname = $_POST[ "albumname" ];
    update_album_name( $albumid , $albumname );
?>