<?php
    if ( !Element( "element_logged_in" ) ) {
        return false;
    }

    $logdate = $_POST[ 'item' ];
    $where = $_POST[ 'r' ];
    if ( isset( $logdate ) && isset( $where ) ) {
        if ( $contents = IRCLogView( $where, str_replace( '..' , '' , $logdate ) ) ) {
            ?><center><h4>Log of <?php echo $logdate; ?></h4></center><?php
            echo $contents;
        }
    }
?>