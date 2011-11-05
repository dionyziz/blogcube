<?php
    if ( !Element( "element_logged_in" ) ) {
        return false;
    }

    $n = $_POST[ "n" ];
    $e = $_POST[ "e" ];
    $n = safedecode( $n );
    
    if( ValidEmail( $e ) ){
        /* 
        echo "Invited's name: " . $n . "<br />";
        echo "invited's mail: " . $e . "<br />";
        */
        
        Invite( $n, $e );
        ?><table><tr><td><img src="images/nuvola/done.png" /></td><td><h4>Your invitation has been sent</h4></td></tr></table><br />
        If your friend accepts your invitation, you'll be notified and he will be added to your friends list so that you can stay in touch.<br /><br /><?php
        $links = Array();
        if( $user->InvitationsLeft() ) {
            $links[ "Invite another friend" ] = "javascript:dm('invitations/invite/new','Please hold...');";
        }
        $links[ "Back to Invitations" ] = "javascript:dm('invitations/invitations');";
        LinkList( $links );
        bfc_start();
        ?>
        g("invform").style.display = 'none';
        cp( "blogging/hola" );
        cp( "invitations/invitations" );
        <?php
        bfc_end();
    }
    else {
        ?><table><tr><td><img src="images/nuvola/error.png" /></td><td><h4>Invalid E-mail</h4></td></tr></table><br />
        The e-mail address you entered seems to be incorrect. Please make sure you have entered a valid e-mail address.<?php
    }
?>