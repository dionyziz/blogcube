<?php
    if ( !Element( "element_logged_in" ) ) {
        return false;
    }

    $albumid = $_POST[ "albumid" ];
    $album_newpubl = $_POST[ "newpubl" ];
    album_publ_set( $albumid , $album_newpubl );
?>