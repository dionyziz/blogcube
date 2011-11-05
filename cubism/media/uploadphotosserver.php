<?php 
    //make control size and mime type
    $file_numbers = count( $_FILES );
    // get the keys in the $_FILES array (i.e. <input type="file"> NAMES, and what we'd write $_FILES[ here ])
    // and store them in a new array, named $userfiles
    $photos = array_keys( $_FILES );
    $m1 = $totalsize = 0;
    $albumid = $_POST[ "albumid" ];
    $photo = $_FILES[ "userfile" ];
    $tempname = $photo[ "tmp_name" ];
    $totalsize = $photo[ "size" ];
    $photocreated = $deceptiontrial = $maxsize = false;
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
            
        // submit the photo to the database and save it to its permanent location
        //returns the last avatar id or false if there is not a valid avatar image format
        $mediaid = add_photo( $albumid , $realname , $tempname );
        $photocreated = $mediaid !== false;
        //$photocreated = true;
    }
    ?><html><head><title></title>
    <script type="text/javascript"><?php
    bc_ob_end_flush();
    bc_ob_section();
    ?>function photodone(){<?php
    if( $photocreated ) {
        ?>parent.photouploaded( '<?php 
        echo $albumid; ?>' );<?php
    }
    else {
        // error
        ?>parent.photouploaderror();<?php
    }
    ?>}<?php
    echo bc_ob_fallback();
    ?></script>
    </head><body onload="photodone();"><div id="photohtml" style="display:none"><?php
    if( $photocreated ) {
        $this_photo = New Photo( $mediaid );
        //$this_photoname = $this_photo->filename();
        //$showingavatars = true;
        bc_ob_section();
        $albumsnew = album_retrievephotos( $albumid );    
        $i = count( $albumsnew );
        $i--;
        //bc_die( "i: ".$i );
        include "elements/blogging/media/albums/thispicture.php";
        $photohtml = bc_ob_fallback();
        $photohtml = html_filter( $photohtml );
        echo $photohtml;
        //$rsize = $this_photo->proportional_size( 100 , 100 );
        //img( "download.bc?id=".$this_photo->Id()."&iw=".$rsize[0]."&ih=".$rsize[1] , "Photo: ".$this_photoname , "Photo: ".$this_photoname , $rsize[0] , $rsize[1] , "border: 1px solid #8baed5;" );
    }
    ?></div><br /><br /></body></html>