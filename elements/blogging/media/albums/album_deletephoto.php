<?php
    if ( !Element( "element_logged_in" ) ) {
        return false;
    }

    $photoid = $_POST[ "photoid" ];
    album_photo_setinactive( $photoid );
?>