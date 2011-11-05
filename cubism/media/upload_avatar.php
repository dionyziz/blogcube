<?php 
    //make control size and mime type
    
    $file_numbers = count( $_FILES );
    // get the keys in the $_FILES array (i.e. <input type="file"> NAMES, and what we'd write $_FILES[ here ])
    // and store them in a new array, named $userfiles
    $avatars = array_keys( $_FILES );
    $m1 = $totalsize = 0;
    
    $avatarfile = $_FILES[ "userfile" ];
    $tempname = $avatarfile[ "tmp_name" ];
    $totalsize = $avatarfile[ "size" ];
    $avatarcreated = $deceptiontrial = $maxsize = false;
    if( !is_uploaded_file( $tempname ) ) {
        $deceptiontrial = true;
    }
    else if ( $avatarfile[ 'size' ] > 16*1024*1024 ) {
        $maxsize = true;
    }
    else {
        // get the original filename
        $realname = $avatarfile[ 'name' ];
        // open the file and read its contents into the $contents variable
        //reading contents for creating the avatar through the binary
        $fp = fopen( $tempname , "r" );
        $contents = fread( $fp , filesize( $tempname ) );
        fclose( $fp );
            
        // submit the avatar to the database and save it to its permanent location
        //returns the last avatar id or false if there is not a valid avatar image format
        $newavatarid = create_avatar( $realname , $tempname , $contents );
        $avatarcreated = $newavatarid !== false;
    }
    
    ?><html><head><title></title></head><body onload="avatardone();"><div id="avatarhtm"><?php
    if( $avatarcreated ) {
        $this_avatar = New Avatar( $newavatarid );
        $showingavatars = true;
        bc_ob_section();
        include "elements/blogging/profile/avatar.php";
        $avatarhtml = bc_ob_fallback();
        $avatarhtml = html_filter( $avatarhtml );
        echo $avatarhtml;
    }
    ?></div><br /><br /><script type="text/javascript"><?php
    bc_ob_end_flush();
    bc_ob_section();
    ?>function avatardone(){<?php
    if( $avatarcreated ) {
        ?>parent.avataruploaded();<?php
    }
    else {
        // error
        ?>parent.avataruploaderror();<?php
    }
    ?>}<?php
    echo bc_ob_fallback();
    ?></script></body></html>