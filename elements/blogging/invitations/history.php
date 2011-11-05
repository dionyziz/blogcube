<?php
    if ( !Element( "element_logged_in" ) ) {
        return false;
    }

    h3( "My Invitations" , "invite64" );
?>
Invitations allow you to give BlogCube to your friends.    
<?php
    LinkList( Array( "More Information" => "javascript:doc('invitations')" ) );
?><br /><?php

    h4( "My Invitations History" );
?><ul>
<?php
    $history = GetInvitations($user->ID());
    if($history == false) {
        ?>
        <li>No invitations sent</li>
        <?php    
    }
    else {
        while( $historyitem = each($history) ) {
            $histinvitation = new Invitation($historyitem[1]);
            echo "<li>" . $histinvitation->InvitedName() . " (" . $histinvitation->InvitedEmail() . ")</li>";
        } 
    }
?>
</ul>