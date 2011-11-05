<?php
    if ( !Element( "element_logged_in" ) ) {
        return false;
    }
    
    $bug_title = safedecode( $_POST[ "bug_title" ] );
    $bug_desc = safedecode( $_POST[ "bug_desc" ] );
    $bug_os = safedecode( $_POST[ "bug_os" ] );
    $bug_osversion = safedecode( $_POST[ "bug_osversion" ] );
    $bug_browser = $_POST[ "bug_browser" ];
    $bug_browserversion = $_POST[ "bug_browserversion" ];
    ReportBug( $bug_title , $bug_desc , $bug_os , $_SERVER[ 'HTTP_USER_AGENT' ] , $bug_osversion, $bug_browser, $bug_browserversion );
    img( 'images/nuvola/done.png' );
    
    bfc_start();
    ?>
    et('Bug Reported');
    <?php
    bfc_end();
?> Bug successfully reported. We will try to fix it as soon as possible.<br /><br />Thanks!