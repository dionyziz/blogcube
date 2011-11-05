<?php
    if ( !Element( "element_admin" ) ) {
        return false;
    }

    $assignto = $_POST[ "t" ];
    $numberofinvitations = $_POST[ "n" ];
    $targetuser = GetUserByUsername( $assignto );
    bfc_start();
    if( $targetuser === false ) {
        ?>
        g('invusr').value = "";
        g('invusr').focus();
        <?php
        bfc_end();
        ?><br /><br /><?php
        h4( "Error" , "error" );
        ?>Please verify that you have typed a valid BlogCube username.
        <?php
    }
    else if ( !ValidNNI( $numberofinvitations ) ) {
        ?>
        g('numinv').value = "2";
        g('numinv').focus();
        g('numinv').select();
        <?php
        bfc_end();
        ?><br /><br /><?php
        h4( "Error" , "error" );
        ?>Please make sure that you have typed a non-negative integer invitations number.
        <?php
    }
    else {
        if( $targetuser->Id() == $user->Id() ) {
            ?>
            cp('blogging/hola');
            cp('blogging/invitations/invitations');
            <?php
        }
        ?>
        g('invusr').value = g('numinv').value = "";
        g('invassign').style.display = "none";
        g('assianc').innerHTML = "Assign Again";
        <?php
        bfc_end();
        $targetuser->AssignInvitations( $numberofinvitations );

        h4( "Invitations Assigned" , "done" );
        ?>
        Your invitations have been assigned. <br /><?php
            echo $targetuser->Username();
        ?> now has <?php
            echo $targetuser->InvitationsLeft();
        ?> invitations in total.
        <?php echo $arrow; ?><a href="javascript:void(g('invass').style.display='none'+(g('invassign').style.display='')+((g('assianc').innerHTML='Cancel Invitation Assignment')?'':'')+((g('invusr').focus())?'':''));">Assign More?</a>
        <?php
    }
?>