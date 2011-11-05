<?php
    if ( !Element( "element_logged_in" ) ) {
        return false;
    }
        
    ?>
    <h3>Invitation Preview</h3>
    <div style="height:300px;width:500px;overflow:auto;"><br /><?php
    echo nl2br( CreateInvitation( $user->FirstName() , $user->LastName() , "12345" , "abcdefghijklmnoprstuvwxyz" ) );
    ?></div><?php
?>