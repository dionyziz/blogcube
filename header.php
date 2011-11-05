<?php
    /*
        Developer: dionyziz
    */
    
    /* this file is included by all directly-called files (bc files):
        - index.bc
        - img.png.bc
        - bc.js.bc
        - style.css.bc
        - js.bc
    */
    
    // this file, the water debugging file, HAS to be included before ANYTHING else
    include "modules/water/water.php";

    // provide access to $_SESSION variables
    session_start();
    
    /*
        -** Configuration Start **-
    */
    /* set this to true to add a delay of loading when testing the system locally
    to check how the AJAX downloading system works. Make sure it's always
    false when running on the server; = true HONESTLY means slooo~w, so don't change */
    $slowloading = false;
    
    include "branch.php";

    // the home domain of blogcube
    if ( $debug_version ) {
        $domainname = "blogcube.net/beta";
    }
    else {
        $domainname = "blogcube.net";
    }

    // blogcube version followed by codename in quotation marks; we should find a way to display SVN revision when using the debug version: TODO
    $version = "2.0 \"Paris\"";
    /*     will for sure disallow access to all databases if set to true
        do NOT change this: readonly means ALL dbases are offline and this INCLUDES css templates for main blog (blogcube blogging interface)); only set to true
        UNLESS you're doing some serious maintainance which requires all database to be offline
    */
    $readonly = false;
    // set to true to send unfiltered HTML/js/CSS code; true means slow, and valuable code resources (js) relieved to the client, so prefer not to change
    $nofilters = true; 
    /*
        -** Configuration End **-
    */
    $system = "BlogCube"; // BlogCube, if you change up, all logos and names will be screwed up

    $systemurl = "http://$domainname/";
    $systemdir = "/var/www/vhosts/blogcube.net/httpdocs/";
    if( $debug_version ) {
        $systemdir .= "beta/";
    }
    // $uploaddir = $systemdir . "uploads/";
    /* 
        this variable is read by includes
        so that they are not directly accessible by the user
        if a user tries to open up an include file, he/she will
        get an access denied message, since the include should 
        check if allow is true. Also passing allow=1 as via
        get won't work since we check using the === operator
        (check for value and type) 
    */
    $allow = true;
    
    $reserved = Array(
        "blog" , "invitations" , "cube" , "blogcube" ,
        "accounts" , "profiles" , "staff" , "hades" ,
        "guardian" , "turing" , "news" , "help" ,
        "doc" , "documentation" , "admin" , "mysql" , 
        "database" , "db" , "dbase" , "access" ,
        "servers" , "status" , "blogger" );

    // calls for getting some oftenly used HTML code and bfc functions
    include "gui.php";
    
    // this should be changed by /modules/users when auth() if the user is logged in
    // for now, assume that the user is anonymous, until her identity is checked
    $anonymous = true;

    include "permissions.php";

    // this file will set the variables containing the username, password, host, and database name
    include "db_access_codes.php";
    
    // modules includes
    include "modules/modules.php";
    
    if( file_exists( "revision.php" ) ) {
        include "revision.php";
    }

    $doctype = "<" . "?xml version=\"1.0\" encoding=\"UTF-8\"?" . ">\n" .
"<!DOCTYPE html PUBLIC\n" . 
"  \"-//W3C//DTD XHTML 1.0 Strict//EN\"\n" .
"  \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\">";
?>