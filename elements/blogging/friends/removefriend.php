<?php
    if ( !Element( "element_logged_in" ) ) {
        return false;
    }
    
    $frienduserid = $_POST[ 'frid' ];
    //echo $frienduserid . "  " . $userid;
    $res = RemoveFriend( $frienduserid, $user->Id() );
    if ( $res == 1 ) {
        img( "images/nuvola/done.png" ); 
        ?> User was successfully removed from your friends.<?php
    }
    else {
        img( "images/nuvola/error.png" );
        ?> User is not a friend of yours!<?php
    }
?>