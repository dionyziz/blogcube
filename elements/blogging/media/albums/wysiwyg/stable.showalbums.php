<?php 
    if ( !Element( "element_logged_in" ) ) {
        return false;
    }

    $user_albums = albums_retrieve_albums( $user->Id() );
    $albums_num = count( $user_albums );?>
    <div id="allalbumsin"><?php
    for( $i=0; $i<$albums_num; $i++ ) {
        $this_album = $user_albums[ $i ];
        $album_name = $this_album->name();
        $alb_mainpic = $this_album->main_picid();
            ?><div class="outer_allalbums">
                <div id="wysiwygalbum_<?php
                    echo $i;?>" class="album_inside" onmouseover="javascript:wysiwyg_focusalbum( '<?php
                    echo $i;?>' );" onclick="javascript:wysiwyg_activalbum( '<?php
                    echo $this_album->Id();?>' );"><?php
                    if ( $alb_mainpic == 0 ) {
                        img( "images/nuvola/kpaint64.png" , "Album: ".$album_name , "Album: ".$album_name, 64 , 64 );
                    }
                    else {
                        $mainpicclass = New photo( $alb_mainpic );
                        $rsize = $mainpicclass->proportional_size( 64 , 64 );
                        img( "download.bc?id=".$alb_mainpic."&iw=".$rsize[0]."&ih=".$rsize[1] , "Album: ".$album_name , "Album: ".$album_name , $rsize[0] , $rsize[1] );
                    }
                    ?><br /><?php
                    echo TextFadeOut( $album_name , New RGBColor( 51 , 102 , 153 ) , New RGBColor( 164 , 192 , 221 ) , 6 ,6 );
                ?></div>
            </div><?php
    }
    ?></div>