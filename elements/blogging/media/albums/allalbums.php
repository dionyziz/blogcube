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
        ?><div class="outerdiv"><div id="album_<?php
        echo $i; ?>" onmouseover="javascript:focus_album( '<?php 
        echo $i; ?>' );" class="mainalbpicture"><?php
        $alb_mainpic = $this_album->main_picid();
        if ( $alb_mainpic == 0 ) {
            ?><a onclick="album_select( '<?php
            echo $this_album->Id();?>' , '<?php
            echo $i;?>' , '<?php
            echo $album_name;?>' );"><?php
            img( "images/nuvola/kpaint75.png" , "No main photo" , "You haven't set a main album picture", 75 , 75 , "border: 1px solid #8baed5;padding: 2px 2px 2px 2px;" );
            ?></a><?php
        }
        else {
            $mainpicclass = New photo( $alb_mainpic );
            $rsize = $mainpicclass->proportional_size( 100 , 90 );
            ?><a onclick="album_select( '<?php
            echo $this_album->Id();?>' , '<?php
            echo $i;?>' , '<?php
            echo $album_name;?>' );"><?php
            img( "download.bc?id=".$alb_mainpic."&iw=".$rsize[0]."&ih=".$rsize[1] , "Album: ".$album_name , "Album: ".$album_name , $rsize[0] , $rsize[1] , "border: 1px solid #8baed5;");
            ?></a><?php
        }
        /*
        if ( $userid == $user->Id() ) {
            ?><div id="album_options_<?php
            echo $i;?>" style="display:none;" class="album_publicity"><?php
            //include "elements/blogging/media/albums/albumpublicity.php";
            ?>
            </div><?php
        }
        */
        
        ?><br />
            <div style="cursor:pointer;display:inline;" onclick="album_select( '<?php
            echo $this_album->Id();?>' , '<?php
            echo $i;?>' , '<?php
            echo escapesinglequotes( $album_name );?>' );">
                <div id="name_<?php
                echo $i;?>" style="display:inline"><?php
                echo TextFadeOut( $album_name , New RGBColor( 51 , 102 , 153 ) , New RGBColor( 164 , 192 , 221 ) , 8 ); 
                ?>
                </div>
            </div>
        <div id="drpdown_<?php 
        echo $i;
        ?>" style="display:none"><?php
        DropDownMenu( array(
        array( 'caption' => 'Rename album' , 'js' => 'change_albname(\'' . $i . '\');' ),
        //array( 'caption' => 'Make default album picture' , 'js' => 'defaultmainpic(\'' . $this_album->id() . '\');' ),
        array( 'caption' => 'Delete album' , 'js' => 'delete_album( \'' . $this_album->id() . '\' , \''. $i . '\');' )
        ) );?>
        </div>
        <?php
            if ( $userid == $user->Id() ) {
        ?><div id="changename_<?php
            echo $i;?>" style="display:none">
            <input type="text" class="inbt" value="<?php
            echo $album_name;?>" id="editname_<?php
            echo $i;?>" onfocus="getstartalbname( '<?php
            echo $i;?>' );" onblur="savealbumname( '<?php
            echo $i;?>' , '<?php
            echo $this_album->Id();?>' );" style="width:75px"/>
        </div><?php
        }
        ?></div>
    </div><?php
    }
    if ( $userid == $user->Id() ) {    
        ?><div id="album_create" class="outerdiv">
            <div class="mainalbpicture" style="height: 130px;"><div style="display:table-cell"><?php
                img( "images/nuvola/photoalbums64.png" , "Create a new album" , "Create a new album" );
                ?></div><div style="display:table-cell;vertical-align:top;padding-left:5px;padding-right:5px">
                Create a new album:<br />
                <input type="text" onblur="javascript:save_albumname( '<?php
                echo $userid;
                ?>' );" id="newalbname" class="inbt"></input></div>
            </div>    
        </div><?php
    }
?>