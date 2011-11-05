<?php
    if ( !Element( "element_logged_in" ) ) {
        return false;
    }

    $album_id = $_POST[ "albumid" ];
    $album = New album($album_id , 1);
    $albumtitle = $album->name();
    $photos = album_retrievephotos( $album_id );
    $photonum = count( $photos );
    ?><div style="cursor:pointer" onclick="wysiwyg_back();"><?php
    img( "images/nuvola/back.png" , "Go back to albums" , "Go back to albums" );
    ?></div><br />    
    <div class="wysiwyg_allphotos"><?php
    for( $i = 0; $i<$photonum; $i++ ) {
        $photooclass = $photos[ $i ];
        $rsize = $photooclass->proportional_size( 64 , 64 );
        ?><div class="wysiwyg_outerdiv">
        <div id="wysiwygphoto_<?php
        echo $i;?>" onmouseover="wysiwyg_focusphoto( '<?php
        echo $i;?>' );" onclick="wysiwyg_insert( '<?php
        echo $photooclass->Id();
        ?>' );" class="wysiwyg_photo"><?php
        img( "download.bc?id=".$photooclass->Id() , $photooclass->filename() , $photooclass->filename() , $rsize[ 0 ]  , $rsize[ 1 ] );
        ?><br /><?php
        echo $photooclass->filename();?>
        </div></div>
        
    <?php    
    }
    ?>
    </div>