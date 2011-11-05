<?php
    $postid = $_GET["id"];
    if ( !($anonymous) && ValidId( $postid ) ) {
        if (!GetPost( $postid ) ) {
            bc_die( "Invalid post id passed while editing: " . $postid );
        }
        echo BlogCute( $post->Text() );
    }
    else {
        /* about:blank */
        ?><html></html><?php
    }
?>