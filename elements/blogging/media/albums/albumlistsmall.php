<?php
    if ( !Element( "element_logged_in" ) ) {
        return false;
    }

    if (!isset($albums)) {
        $albums = albums_retrieve_albums( $_POST['userid'] );
        $album_num = count( $albums );
    }
    for ( $i = 0; $i<$album_num; $i++ ) {
        $this_album = $albums[ $i ];
        $album_name = $this_album->name();
    ?><div id="minialbum_<?php
        echo $i;?>" onclick="javascript:minialbumfocus( '<?php
        echo $this_album->Id();?>' , '<?php
        echo $i;?>' , '<?php
        echo $album_name;?>' );" class="minialbpreview"><?php
        $alb_mainpic = $this_album->main_picid();?><div class="thisispreview"><?php
        if ( $alb_mainpic == 0 ) {
            img( "images/nuvola/kpaint75.png" , "No main photo" , "Album: ".$album_name , 75 , 75 );
        }
        else {
            $mainpicclass = New photo( $alb_mainpic );
            $rsize = $mainpicclass->proportional_size( 100 , 90 );
            img( "download.bc?id=".$alb_mainpic."&iw=".$rsize[0]."&ih=".$rsize[1] , "Album: ".$album_name , "Album: ".$album_name , $rsize[0] , $rsize[1] );
        }?><div id="smalllistname_<?php
        echo $i; ?>"><?php
        echo TextFadeOut( $album_name , New RGBColor( 51 , 102 , 153 ) , New RGBColor( 164 , 192 , 221 ) , 10 );?>
        </div>
        </div>
    </div><?php
    }
?>