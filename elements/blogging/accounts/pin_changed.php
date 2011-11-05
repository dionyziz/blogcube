<?php
    if ( !Element( "element_logged_in" ) ) {
        return false;
    }
    
    $bfc->start();
?>fadeout( "ao_pin_changed" );<?php
    $bfc->end();
    $user->SetPin( $_POST[ "pin" ] );
    img( "images/nuvola/button_ok.png" , "OK" , "PIN changed successfully" );
    
?> Pin changed