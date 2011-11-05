<?php
    if( $_SERVER[ 'SERVER_ADDR' ] != UserIp() ) {
        bc_die( "Restricted access" );
    }
?>