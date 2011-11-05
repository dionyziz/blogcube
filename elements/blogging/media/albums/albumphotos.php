<?php    
    if ( !Element( "element_logged_in" ) ) {
        return false;
    }

    //testing, if there's no main album picture there occurs an error
    $albumthing = $_POST[ "albthink" ];
    $point = $_POST[ "point" ];
    if ( $point==1 ) {    
        $album = New album( $albumthing , 1 );
    }
    else {
        $album = New album( $albumthing , 2 );
    }
    $albumphotos = album_retrievephotos( $album->id() );
    $num_rows = count( $albumphotos );
    $thismainpic = $album->main_picid();
    for ( $kl = 0; $kl < $num_rows; $kl++ ) {
        $this_photo = $albumphotos[ $kl ];
        $this_photoid = $this_photo->Id();
        if ( $thismainpic == $this_photoid ) {    
            $showmain = $kl;
        }
    }
    //end of testing
    $bfc->start(); ?>
    mainpicdivid = <?php
    if ( !isset ( $showmain ) ) {
        ?>-1<?php
    }
    else {
        echo $showmain;
    }?>;<?php
    $bfc->end();
?><div id="hereareallphotos" class="hereareallphotos"><?php
    if ( $num_rows != 0 ) {
        for ( $i = 0; $i < $num_rows; $i++ ) {
            $this_photo = $albumphotos[ $i ];
            $albumid = $album->id();
            include "elements/blogging/media/albums/thispicture.php";
        }
    }
    else { ?><div id="albumsnophotos"><?php
        img( "images/nuvola/photos64.png" , "Empty album" , "Empty album" );
        ?> The album contains no photos!</div><?php
    }
        //<div id="newphoto" class="inline" style="display:none"></div>
        ?>
    </div>
    <div style="border:1px solid #8baed5;width:280px;padding-left:3px;padding-right:1px;">
        <div style="text-align:left"><?php
            img( "images/nuvola/upload64.png" , "Upload a photo" , "Upload a photo" );
            ?>
            <span style="color:#3b5ea5">Upload your photo...</span><br /><br />
            <div id="photouperror" style="display:none"></div>
            <iframe src="cubism.bc?g=media/uploadphotoalbums&albumid=<?php
            echo $album->Id();
            ?>" frameborder="no" id="alb_photoupl" style="height:50px;width:280px;"></iframe>
            <div id="uploadanims" style="display:none;background-color:#f7f7ff;width:150px;height:150px;">
                Uploading...<br />
                <img src="images/uploading.gif" alt="Please wait..." title="Uploading..." />
            </div>
        </div>
    </div>
    