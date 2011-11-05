<?php
    if( isset( $_POST[ "redir_blogid" ] ) ) {
        $blogid = $_POST[ "redir_blogid" ];
        if( $blogid ) {
            $blog = New Blog( $blogid );
            if( !$blog->Id() ) {
                bc_die( "Invalid redir_blogid" );
            }
        }
    }
    
    if( isset( $_POST[ "redir_invitationid" ] ) ) {
        $invid = $_POST[ "redir_invitationid" ];
        $invcode = $_POST[ "redir_invitationcode" ];
        if( $invid ) {
            $aftermatch = "accounts/register&iid=" . $invid . "&icd=" . $invcode;
            $_SESSION[ "aftermatch" ] = $aftermatch;
        }
    }

    if ( isset( $_POST[ "redir_dm" ] ) ) {
        $aftermatch = $_POST[ "redir_dm" ];
        $_SESSION[ "aftermatch" ] = $aftermatch;
    }
?>