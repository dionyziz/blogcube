<?php
    include "js/js.php";
?>
/*
var ao_str_resource_exists;

function ao_no() {
    if ( g( "ao_old" ).value.length >= 4 && g( "ao_pwd" ).value.length >= 4 ) {
        g( "ao_rtp" ).disabled = false;
        g( "ao_rtp" ).value = "";
    }
    else
        g( "ao_rtp" ).disabled = true;
}

function ao_show_strength() {
    var ao_old_password;
    if ( g( "ao_pwd" ).value != ao_old_password )
        password_changed = true;
    if ( !g( "ao_rtp" ).disabled ) {
        if ( password_changed ) {
            g( "ao_strength" ).style.display = "";
            de( "ao_strength" , "blogging/accounts/password_strength&password=" + g( 'ao_pwd' ).value , "" , "-" );
            g( "ao_invalid" ).style.display = "none";
        }
    }
    else {
        g( "ao_strength" ).style.display = "none";
        g( "ao_invalid" ).style.display = "";
    }
    ao_old_password = g( "ao_pwd" ).value;
    alert('c');
}

function ao_password_changed() {
    if ( ao_str_resource_exists )
        clearTimeout( ao_str_resource );
    ao_str_resource = setTimeout( "ao_show_strength(); alert('a');" , 1000 );
    ao_str_resource_exists = true;
}

function ao_pwd_changing() {
    if ( g( "ao_pwd" ).value.length >= 4 && g( "ao_pwd" ).value == g( "ao_rtp" ).value && g( "ao_rtp" ).value != "" ) {
        g( "ao_strength" ).style.display = "none";
        de( "ao_pwd_changed" , "blogging/accounts/password_changing&old_pwd=" + g( 'ao_old' ).value + "&new_pwd=" + g( 'ao_pwd' ).value , "" , "-" );
    }
}
*/

function ao_pwd_change() {
    g("ao_pwd_changebtn").style.display = "none";
    g("ao_pwd_changed").style.display = "";
    de2( "ao_pwd_changed" , "blogging/accounts/password_changing" , {'old_pwd': g( 'ao_old' ).value , 'new_pwd' : g( 'ao_pwd' ).value} );
}

function ao_newpwd_check() {
    newpwd = g("ao_pwd").value;
    rtppwd = g("ao_rtp").value;
    if ( newpwd == rtppwd ) {
        g("ao_match").style.display = "none";
        g("ao_pwd_changebtn").style.display = "";
    }
    else {
        if ( rtppwd.length > 0 ) g("ao_match").style.display = "";
        g("ao_pwd_changebtn").style.display = "none";
    }
}

var ao_timeoutid;
var ao_beforelen;
function ao_oldpwd_before() {
    ao_beforelen = g("ao_old").value.length;
}

function ao_oldpwd_check() {
    if ( ao_beforelen != g("ao_old").value.length ) {
        clearTimeout(ao_timeoutid);
        curpwd = g("ao_old");
        if ( Md5.md5(curpwd.value) != ao_old_password ) {
            g("ao_typing").style.display="";
            g("ao_validity").style.display = "none";
            g("ao_done1").style.display = "none";
            ao_timeoutid = setTimeout("ao_oldpwd_rcheck();",1000);
        }
        else {
            ao_oldpwd_rcheck();
        }
    }
}

function ao_oldpwd_rcheck() {
    curpwd = g("ao_old");
    g("ao_typing").style.display="none";
    if ( Md5.md5(curpwd.value) != ao_old_password ) {
        g("ao_validity").style.display = "";
        g("ao_pwd").disabled = true;
        g("ao_rtp").disabled = true;
        g('ao_strength').style.display = "none";
        g('ao_too_short').style.display = "none";
    }
    else {
        g("ao_done1").style.display = "";
        g("ao_validity").style.display = "none";
        g("ao_pwd").disabled = false;
        Strength.strength('ao_pwd','ao_too_short','ao_rtp','ao_strength');
    }
}

function ao_pin_changing() {
    if ( g( "ao_pin" ).value != "default" && g( "ao_pin" ).value.length > 0 )
        de( "ao_pin_changed" , "blogging/accounts/pin_changed&pin=" + g( 'ao_pin' ).value , "" , "-" );
    else
        g( "ao_pin" ).value = "default";
}

function ao_show_password() {
    if ( g( "ao_password" ).style.display == "none" ) {
        g( "ao_password" ).style.display = "";
        g( "ao_distantblogging" ).style.display = "none";
        g( "ao_old" ).focus();
    }
    else
        g( "ao_password" ).style.display = "none";
}

function ao_show_distantblogging() {
    if ( g( "ao_distantblogging" ).style.display == "none" ) {
        g( "ao_distantblogging" ).style.display = "";
        g( "ao_password" ).style.display = "none";
    }
    else
        g( "ao_distantblogging" ).style.display = "none";
}


/* Register form javascript code follows: */

function reg_pwd_check() {
    if ( g("reg_pwd").value == g("reg_pwd2").value ) {
        g("reg_pwd_correct").style.display = "";
    }
    else {
        g("reg_pwd_correct").style.display = "none";
    }
}

