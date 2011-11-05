<?php
    include "js/js.php";
?>

function toggle_wind_gmail_easy () {
    if ( g( "wind_gmail_easy" ).style.display == "none" ) {
        g( "wind_gmail_easy" ).style.display = "";
        g( "wind_gmail_csv" ).style.display = "none";
        g( "user" ).focus();
    }
    else
        g( "wind_gmail_easy" ).style.display = "none";
}

function toggle_wind_gmail_csv () {
    if ( g( "wind_gmail_csv" ).style.display == "none" ) {
        g( "wind_gmail_csv" ).style.display = "";
        g( "wind_gmail_easy" ).style.display = "none";
        g( "csv" ).focus();
    }
    else
        g( "wind_gmail_csv" ).style.display = "none";
}

function toggle_wind_profile () {
    if ( g( "wind_profile" ).style.display == "none" ) {
        g( "wind_profile" ).style.display = "";
    }
    else
        g( "wind_profile" ).style.display = "none";
}

function wind_geasy_submit() {
    if (g('user').value && g('pass').value) {
        de('wind','blogging/friends/import_do&user='+safeencode(g('user').value)+'&pass='+safeencode(g('pass').value)+'&type=geasy','');
    }
}

function wind_geasy_key(e) {
    if ( e ) {
        event = e;
    }
    if ( event.keyCode == 13 ) {
        wind_geasy_submit();
    }
}