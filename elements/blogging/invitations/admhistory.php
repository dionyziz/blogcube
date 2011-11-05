<?php
    if ( !Element( "element_admin" ) ) {
        return false;
    }

    $historyof = $_POST[ "t" ];

    $histuser = GetUserByUsername($historyof);
    if( $histuser === false ) {
        bfc_start();
        ?>
        g('histusr').value = "";
        g('histusr').focus();
        <?php
        bfc_end();
        ?><br /><br /><?php
        h4("Error", "error");
        ?>
        Please verify that you have typed a valid BlogCube username.
        <?php
    }
    else {
        bfc_start();
    ?>
        g('histusr').value = "";
        g('invhistory').style.display = "none";
        g('histanc').innerHTML = "Display Again";
    <?php
        bfc_end();
        h4( "$historyof's Invitations History" );
        echo "<ul>";
        $history = GetInvitations($histuser->ID());
        if($history == false) {
            ?>
            <li>No invitations sent</li>
            <?php    
        }
        else {
            while( $historyitem = each($history) ) {
                $histinvitation = new Invitation($historyitem[1]);
                ?><li><?php
                echo $histinvitation->InvitedName();
                ?> (<?php
                echo $histinvitation->InvitedEmail();
                ?>)</li><?php
            }
        }
        echo "</ul>";
    } 
?>