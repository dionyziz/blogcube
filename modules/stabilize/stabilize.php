<?php
    /*
    Module: Stabilization Script
    File: /modules/stabilize/stabilize.php
    Developers: feedWARd
    */
    
    include "modules/module.php";
    include "modules/stabilize/database.php";
    include "modules/stabilize/svnlock.php";
    
    function Stabilize($public = false) {
        global $revision;
        global $debug_version;
        global $db_access;
        global $user;
        
        $httpdocs = "/var/www/vhosts/blogcube.net/httpdocs/";
        $sourcedir = $httpdocs . "beta";
        $targetdir = $httpdocs . "r" . $revision;
        if ( !$debug_version ) {
            bc_die("Stabilization is allowed only during debug version");
        }
        $branchfile = $targetdir . "/branch.php";
        
        /* locks the SVN repository to prevent commits during stabilization */
        svnlock();

        /* database stabilization */
        $stable_access = $db_access[ 'stable_stabilize' ];
        if ( !isset( $stable_access ) ) {
            bc_die( 'Stabilization: Could not read access codes to databases' );
        }
        $stable_database = New BCDatabase(
            'localhost' ,
            $stable_access[ 'username' ] ,
            $stable_access[ 'password' ] ,
            $stable_access[ 'db' ]
        );
        if ( !$stable_database->Connect() ) {
            bc_die( 'Stabilization: Connection to Stable failed: ' . $stable_database->Error() );
        }
        
        $queries = StabilizeDatabase();
        foreach ($queries as $query) {
            $stable_database->Query($query);
        }

        /* copies beta source code to stable directory */
        echo nl2br( "Source: $sourcedir\nTarget: $targetdir\n" );
        if ( !(@is_dir( $targetdir )) )
            @mkdir( $targetdir, 0777 );
        $excludepaths = array(
                "$sourcedir/branch.php",
                "$sourcedir/images/generated/math_"
            );
        $cptree = New CopyTree( $sourcedir, $targetdir, $excludepaths, array('.svn') ); //the third parameter will contain certain full paths to be excluded, such as specific elements
        $cptree->CopyLayer( 2 ); //Layer #2) Overwrite: Copy all files OVERWRITING existing ones.
        
        /* set debug version variable to false */
        $handle = fopen($branchfile,"w");
        $phpcode =    '<?php
                        $debug_version = false;
                    ?>';
        fwrite($handle,$phpcode);
        fclose($handle);
        
        /* mapping */
        if ( $public ) {
            $maptarget = "";
        }
        else {
            $maptarget = " paris";
        }
        passthru("sudo /var/www/vhosts/blogcube.net/httpdocs/beta/modules/stabilize/./configeditor.php " . $revision . $maptarget);
        
        /* restores SVN */
        svnunlock();
        
        mail(    'stabilize@blogcube.net',
            '[blogcube-stabilize] ' . $revision,
            'BlogCube Stabilization was performed today, on ' . NowDate() . ' by developer ' . $user->Username() . "\n" .
            'http://blogcube.net/ is now running revision ' . $revision,
            'From: ' . strtolower( $user->Username() ) . '@blogcube.net'
        );
    }
?>