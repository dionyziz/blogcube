<?php
    if ( !Element( "element_logged_in" ) ) {
        return false;
    }

    h3( "My Invitations" , "invite64" );
?>
Invitations allow you to give BlogCube to your friends.    
<?php
    LinkList( Array( "More Information" => "javascript:doc('invitations')" ) );
?><br />

<h4>You have <?php echo $user->InvitationsLeft(); ?> invitation<?php
    if( $user->InvitationsLeft() > 1 ) {
        ?>s<?php
    }
    ?> left!</h4><?php
    $links = Array();
    if( $user->InvitationsLeft() ) {
        $links[ "Invite a Friend" ] = "javascript:de('main','blogging/invitations/invite/new','','Please hold...');";
    }
    $links[ "My Invitations History" ] = "javascript:de('main','blogging/invitations/history','','Please hold...');";
    LinkList( $links );
    bfc_start();
?>
    cacheable = true;
    pl( "blogging/invitations/invite/new" );
    et( "My Invitations" );
<?php
    bfc_end();
?>