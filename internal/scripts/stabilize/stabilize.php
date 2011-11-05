<?php
    $unstable_path = "/var/www/vhosts/blogcube.net/httpdocs/beta/";
    $stable_path = "/var/www/vhosts/blogcube.net/httpdocs/";
    
    // 1. backup old stable version
    $httpdocs = "/var/www/vhosts/blogcube.net/httpdocs/";
    
    $exclude = Array(
        $httpdocs . "wiki" ,
        $httpdocs . "sql" ,
        $httpdocs . "beta" ,
        $httpdocs . "plesk-stat"
    );
    
    $backupto = $httpdocs . "../" . NowDate();
    
    CopyTree( "/var/www/vhosts/blogcube.net/httpdocs" , "/var/www/vhosts/blogcube.net/httpdocs/backup" );
    
    // 2. create list of old stable files
    // 3. SVN lock
    // 4. create list of new stable files
    // 5. copy new stable files that do not exist in the old stable version with the same name and remove from list of files to be copied
    // 6. generate SQL structure modification commands to be executed
    // 7. redirect to a "temporarily unavailable" page *
    // 8. backup existing database
        // connect to stable database
        // SELECT data from all tables
        // print to a plaintext file? (or a remote database?)
    // 9. upgrade stable database structure
    // 10. delete all old stable files
    // 11. copy new stable files in list
    // 12. remove "temporarily unavailable" page redirection *
    // 13. SVN unlock
?>