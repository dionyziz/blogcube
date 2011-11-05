<?php 
    if ( !Element( "element_logged_in" ) ) {
        return false;
    }
    
    $avatid = $_POST[ "avatarid" ];
    $avmediaid = $_POST[ "avatarmediaid" ];
    $thisavatar = New Avatar( $avatid );
    if( $thisavatar->userid() == $user->Id() ) {
        $thisavatar->avatar_make_default();
    }
    echo $avatid; 
    ?> is now your default avatar.<?php
    $bfc->start();
    ?>cp('blogging/hola')<?php
    $bfc->end();
?>