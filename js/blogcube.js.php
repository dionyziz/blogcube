<?php
    /* 
        Developer: dionyziz
    */
    // this module sends beforehand all necessary javascript code to begin with
    
    header( "Content-type: text/javascript" );
    
    bc_ob_start( "js_filter" );
    // serves debugging purposes so better be included before anything else so that we can use debug methods
    include_once "js/debug.php";

    require_once "js/xmlhttp.php";
    
    // this desperately needs to be included before the ajax include
    require_once "js/bfc/bfc.php";
    
    require_once "js/general.php";
    require_once "js/ajax.php";
    require_once "js/cache.php";
    
    include_once "js/windows.php";
    include_once "js/fade.php";
    include_once "js/time.php";
    include_once "js/dyncss.php";
    include_once "js/gui.php";
    // include_once "js/libs/posts.php";
    // include_once "js/md5.php"; // lib

    // include all files in js/more
    include_once "js/more/account_options.php";
    include_once "js/more/blogs.php";
    // include_once "js/more/bookmarks.php"; // lib
    include_once "js/more/bugreporting.php";
    include_once "js/more/comments.php";
    include_once "js/more/documentation.php";
    include_once "js/more/earth.php";
    // include_once "js/more/friends.php"; // lib
    include_once "js/more/invitations.php";
    include_once "js/more/math.php";
    include_once "js/more/media_albums.php";
    include_once "js/more/messaging.php";
    include_once "js/more/posts.php";
    include_once "js/more/user_configuration.php";
    include_once "js/more/users.php";
    include_once "js/more/wind.php";
    include_once "js/more/wysiwyg.php";
    include_once "js/more/dropdown.php";
    // include_once "js/more/googleads.php";
    
    /* 
    $js_more = "js/more";
    $dir_res = opendir( $js_more );
    
    while ( $file = readdir( $dir_res ) ) {
        if ( !is_dir( $js_more . "/" . $file ) ) {
            // include "more/" . $file;
        }
    }

    closedir( $dir_res );
    */
    
     bc_ob_end_flush();
?>