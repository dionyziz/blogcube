<?php
    if ( !Element( "element_logged_in" ) ) {
        return false;
    }

    $bfc->start();
    if ( !$user->Id() ) {
        ?>alert('You must be logged in to delete a comment!');<?php
    }
    else {
        $comment = New PostComment( $_POST[ 'cid' ] );
        if ( $comment->UserId() != $user->Id() ) {
            ?>alert('You can only delete your own comments!');<?php
        }
        else {
            DeleteComment( $comment->Id() );
        }
    }
    $bfc->end();
?>