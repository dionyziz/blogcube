<?php
    include "allow.php";

    $invitation_identifier = $k[ 0 ];
    $inv_identifier = explode( "/" , $invitation_identifier );
    $invitationid = $inv_identifier[ 0 ];
    $invitationcode = $inv_identifier[ 1 ];
    
    $redirfields = Array();
    $redirfields[] = Array( "invitationid" , $invitationid );
    $redirfields[] = Array( "invitationcode" , $invitationcode );
    include "redir/redir.php";
?>