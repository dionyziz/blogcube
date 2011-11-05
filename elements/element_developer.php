<?php
    if ( !Element( "element_logged_in" ) ) {
        return false;
    }
    
    if( !$user->IsDeveloper() ) {
        img( "images/nuvola/error64.png" , "Error" );
        ?>This page cannot be displayed, because access to it has been restricted.<?php
        return false;
    }
?>
