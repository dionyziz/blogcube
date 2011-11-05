<?php
    switch( $msub ) {
        case "invitations":
            // in the form of http://invitations.blogcube.net/?invitationid/invitationcode=
            $k = array_keys( $_GET );
            if( count( $k ) != 1 ) {
                bc_die( "Invalid invitation link" );
            }
            include "redir/invitation.php";
            exit();
            break;
        case "www":
            header( "Location: $systemurl" );
            break;
        default:
            // check if $msub is the name of a blog
            if( $blog = GetBlogByName( $msub ) ) {
                include "redir/blog.php";
                exit();
            }
            else {
                include "redir/invalidblog.php";
                exit();
            }
    }
?>