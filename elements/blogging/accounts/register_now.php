<?php
    $username = $_POST[ "u" ];
    $password = $_POST[ "p" ];
    $email = $_POST[ "e" ];
    $invitationid = $_POST[ "i" ];
    $invitationcode = $_POST[ "c" ];
    $invitation = New Invitation( $invitationid );
    if( !$invitation->CheckValidy( $invitationcode ) ) {
        // invalid invitation id/code
        // reload register screen which should recognize that
        bfc_start();
        ?>
        dm( "accounts/register&iid=<?php
        echo $invitationid;
        ?>&icd=<?php
        echo $invitationcode;
        ?>" , "Validating..." );
        <?php
        bfc_end();
    }
    else {
        $name = $invitation->InvitedName();
        $names = explode( " " , $name );
        $firstname = $names[ 0 ];
        $lastname = $names[ 1 ]; // TODO: problem with spanish names?
        $regresult = Register( $invitationid , $invitationcode , $username , $password , $email , $firstname , $lastname , $invitation->InvitingUserId() );
        if( $regresult === -1 || $regresult === -3 || $regresult === -4 ) {
            ?><div id="reg_errormsg" style="display:none"><?php
            $_POST[ "n" ] = $username;
            include "elements/blogging/accounts/unamecheck.php";
            ?></div><?php
            bfc_start();
            ?>
            g( "ureg_ca" ).innerHTML = g( "reg_errormsg" ).innerHTML;
            <?php
            bfc_end();
        }
        elseif( $regresult === -2 ) {
            ?><div id="reg_errormsg" style="display:none"><?php
            img( "images/nuvola/error.png" );
            ?> Invalid</div><?php
            bfc_start();
            ?>
            g( "ureg_ce" ).innerHTML = g( "reg_errormsg" ).innerHTML;
            <?php
            bfc_end();
        }
        else {
            // Auth() should have been fired by now, so going to the login screen will bring up hola
            bfc_start();
            ?>
            et( "Account Created" );
            cp( "blogging/accounts/login" );
            cp( "blogging/topbar" );
            cp( "blogging/topbarright" );
            de( "topbar" , "blogging/topbar" , '' , '-' );
            de( "topbarright" , "blogging/topbarright" , '' , '-' );
            dm( "accounts/login" , "Account Created!" );
            <?php
            bfc_end();
        }
    }
?>