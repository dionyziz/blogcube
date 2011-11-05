<?php
    if ( !Element( "element_logged_in" ) ) {
        return false;
    }

    //use an image to show albums
    $userid = $_POST[ "userid" ];
    //for testing purposes users can just view their albums
    $userid = $user->Id();
    $num_rows = count( albums_retrieve_albums( $userid ) );
    h3( "My Albums" , "albumview64" );
    if ( $num_rows == 0 ) {
        include "elements/blogging/media/albums/firstalbumupload.php";
    }
    else {
        include "elements/blogging/media/albums/albums_list.php";
    }
?>