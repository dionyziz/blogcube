<?php 
    if ( !Element( "element_logged_in" ) ) {
        return false;
    }

    function PublicityStatus( $value ) {
        global $publ_icons;
        
        $publ_stat = array ( "public" => "Everyone",
                            "ffriends" => "Friends of Friends",
                            "friends" => "Friends",
                            "private" => "Me only"
                            );
        $kp = array_keys( $publ_stat );
        $alloptions = "";
        $selectedopt = 0;
        for ( $j=0; $j<count( $publ_stat ); $j++ ) {
            $alloptions .= "<option value=\"" . $kp[ $j ] . "\"";
            if ( $value == $kp[ $j ] ) {
                $alloptions .= " selected=\"selected\"";
                $selectedopt = $j;
            }
            $alloptions .= " style=\"background-image:url('images/nuvoe/publicity_"
                        . $publ_icons[ $j ]
                        . ".png');background-position:left;background-repeat:no-repeat;padding-left:18px;\">" 
                        . $publ_stat[ $kp[ $j ] ] 
                        . "</option>";
        }
        ?>
        <select id="albumpublicity_<?php
        echo $j;?>" onchange="album_publ( '<?php
        echo $albumid;?>' , '<?php
        echo $j;?>' );" style="padding-left:18px;background-repeat:no-repeat;background-position:left;background-image:url('images/nuvoe/publicity_<?php
        echo $publ_icons[ $selectedopt ];
        ?>.png');">
        <?php
        echo $alloptions;
        ?></select><?php
    }
    
    ?><div id="tabs">
        <div id="allialbis" title="All albums" onclick="javascript:allalbclick();" class="tabview"><?php
            img( "images/silk/images.png" , "All albums" , "All albums" );?> All albums
        </div>
        <div id="albumname" title="All photos" onclick="javascript:albiclick();" class="tabview"> <?php 
            img( "images/silk/folder_image.png" , "Album's photos" , "Album's photos" );
            ?> <div id="albumshowname" style="display:inline">Album's photos</div>
        </div>
        <div id="albiphoto" title="Photo" class="tabview"><?php
            img( "images/silk/image.png" , "Photo" , "Photo" );?> Photo
        </div>
    </div>
    <div id="allalbumsparent" style="display:block;background-color: #f7f7ff;">
        <div id="allalbums" class="allalbumsio"><?php
            $albums = albums_retrieve_albums( $userid );
            $album_num = count( $albums );
            include "elements/blogging/media/albums/allalbums.php";
        ?>
        </div>
<?php
        /*
        if ( $userid == $user->Id() ) {    
        ?><div class="mainalbpicture" style="height:130px;"><div style="display:table-cell"><?php
                img( "images/nuvola/photoalbums64.png" , "Create a new album" , "Create a new album" );
                ?></div><div style="display:table-cell;vertical-align:top;padding-left:5px;padding-right:5px">
                Create a new album:<br />
                <input type="text" onblur="javascript:save_albumname( '<?php
                echo $userid;
                ?>' );" id="newalbname" class="inbt"></input></div>
            </div>    
        <?php
        }
        */
?>
    </div><!--
        
        start of second tab 
        
    --><div id="allalbumphotos" class="allalbumphotos" style="display:none;"<?php //vertical-align:middle;"?>>
        <div id="albumlistsmall" class="inallalbphotos">
        <?php
            include "elements/blogging/media/albums/albumlistsmall.php";
        ?>
        </div>
        <div id="photosofalbum" class="photosofalbum"></div>
    </div>
<!--
    start of third tab
-->
    <div id="photo" style="display:none" class="photo">
        <div id="photoview" style="padding-top:3px;padding-left:2px;padding-right:2px;padding-bottom:3px;"></div>
    </div>
<!--
    this div has a picture for creating a new album, to show this and adding it dinamically to the new photodiv
--><?php 
    /*
    <div id="noalbpicture" style="display:none"><?php
        img( "images/nuvola/kpaint75.png" , "No main photo" , "No main photo" , 75 , 75 );
        ?>
    </div>
    */
    $bfc->start();
    ?>
    focus_tab( "allialbis" );
    disable_tab( "albumname" );
    disable_tab( "albiphoto" );
    et( "My albums" );
    var album_num = <?php
    echo $album_num;?>;
    <?php
    $bfc->end();
    ?>