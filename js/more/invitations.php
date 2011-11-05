<?php
    include "js/js.php";
?>
var ERROR_IFNAME = "Please enter the first name of your friend";
var ERROR_ILNAME = "Please enter the last name of your friend";
var ERROR_IEMAIL = "Please enter your friend's email";

function invitefriend() {
    u1 = g( "invitation_fname" );
    u2 = g( "invitation_lname" );
    u3 = g( "invitation_email" );
    if ( !u1.value ) {
        a( ERROR_IFNAME );
        u1.focus();
    }
    else if ( !u2.value ) {
        a( ERROR_ILNAME );
        u2.focus();
    }
    else if ( !u3.value ) {
        a( ERROR_IEMAIL );
        e1.focus();
    }
    else {
        de( "invitefriend" , "blogging/invitations/invite/now&n=" + safeencode(u1.value) + " " + safeencode(u2.value) + "&e=" + safeencode( u3.value ) );
    }
    return false;
}

var ERROR_INVNAME = "Please type the username of the user to whom you want to assign the invitations";
var ERROR_NUMINV = "Please type the number of invitations to assign to the user";

function invassign() {
    u1 = g( "invusr" );
    u2 = g( "numinv" );
    if ( !u1.value ) {
        a( ERROR_INVNAME );
        u1.focus();
    }
    else if ( !u2.value ) {
        a( ERROR_NUMINV );
        u2.focus();
    }
    else {
        de('invass','blogging/invitations/assign&n='+safeencode(u2.value)+'&t='+safeencode(u1.value));
    }
}

function invhistory() {
    u1 = g( "histusr" );
    if ( !u1.value ) {
        a( ERROR_INVNAME );
        u1.focus();
    }
    else {
        de('invhist','blogging/invitations/admhistory&t='+safeencode(u1.value));
    }
}