<?php
    if ( !Element( "element_logged_in" ) ) {
        return false;
    }

    $mediaid = $_POST[ "mediaid" ];
    $albumid = $_POST[ "albumid" ];
    set_album_mainpicid( $mediaid , $albumid );
?>