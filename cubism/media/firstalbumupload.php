<?php 
    //make control size and mime type
    
    $file_numbers = count( $_FILES );
    // get the keys in the $_FILES array (i.e. <input type="file"> NAMES, and what we'd write $_FILES[ here ])
    // and store them in a new array, named $userfiles
    $photos = array_keys( $_FILES );
    $m1 = $totalsize = 0;
    //$albumid = $_POST[ "albumid" ];
    $photo = $_FILES[ "userfile" ];
    $tempname = $photo[ "tmp_name" ];
    $totalsize = $photo[ "size" ];
    $avatarcreated = $deceptiontrial = $maxsize = false;
    if( !is_uploaded_file( $tempname ) ) {
        $deceptiontrial = true;
    }
    else if ( $avatarfile[ 'size' ] > 16*1024*1024 ) {
        $maxsize = true;
    }
    else {
        // get the original filename
        $realname = $photo[ "name" ];
        // open the file and read its contents into the $contents variable
        //reading contents for is not needed for albums photos as they are not being resized
        //$fp = fopen( $tempname , "r" );
        //$contents = fread( $fp , filesize( $tempname ) );
        //fclose( $fp );
        $album_name = gmdate( "l jS F Y" );
        $albumid = album_create( $album_name , "public" );
        $mediaid = add_photo_toalbum( $realname , $tempname , $albumid );
        set_album_mainpicid( $mediaid, $albumid );            
        // submit the photo to the database and save it to its permanent location
        //$mediaid = add_photo( $albumid , $realname , $tempname );
        $photocreated = $newphotoid !== false;
    }
    ?><html><head><title></title></head><body onload="albumcreated();"><div id="photohtm"><?php
    if( $photocreated ) {
        $this_photo = New Photo( $mediaid );
        $this_photoname = $this_photo->filename();
        //$showingavatars = true;
        bc_ob_section();
        //include "elements/blogging/media/albums/thispicture.php";
        $avatarhtml = bc_ob_fallback();
        $avatarhtml = html_filter( $avatarhtml );
        echo $avatarhtml;
        //$rsize = $this_photo->proportional_size( 100 , 100 );
        //img( "download.bc?id=".$this_photo->Id()."&iw=".$rsize[0]."&ih=".$rsize[1] , "Photo: ".$this_photoname , "Photo: ".$this_photoname , $rsize[0] , $rsize[1] , "border: 1px solid #8baed5;" );
    }
    ?></div><br /><br /><script type="text/javascript"><?php
    bc_ob_end_flush();
    bc_ob_section();
    ?>function albumcreated(){<?php
    if( $photocreated ) {
        ?>parent.dm( 'media/albums/albums' );<?php
    }
    else {
        // error
        ?>parent.photouploaderror();<?php
    }
    ?>}<?php
    echo bc_ob_fallback();
    ?></script></body></html>