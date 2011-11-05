<?php
    if ( !Element( "element_logged_in" ) ) {
        return false;
    }

    $album_name = $_POST[ "albumname" ];
    $albumid = album_create( $album_name ,  "public" );
?>
