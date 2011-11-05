<?php    
    if ( !Element( "element_logged_in" ) ) {
        return false;
    }

    $albumid = $_POST[ "albumid" ];
    album_set_inactive( $albumid );
?>