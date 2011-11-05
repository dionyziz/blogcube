<?php
    if ( !Element( "element_logged_in" ) ) {
        return false;
    }

    $this_photoid = $this_photo->Id();
    $this_photoname = $this_photo->filename();
    $this_photoext = $this_photo->extension();
    ?><div id="outerphotodiv_<?php 
    echo $i;?>" class="outerphotodiv"><div id="photo_<?php
    echo $i;
    ?>" onmouseover="photo_hover('<?php
    echo $i;
    ?>');" class="thisphview" style="width:120px;height:140px;<?php
    if ( $thismainpic == $this_photoid ) {
        ?>background-color:#99ccff;<?php
    }
    ?>"><?php
    $rsize = $this_photo->proportional_size( 100 , 100 );
    ?><a onclick="javascript:photo_select( '<?php
    echo $this_photoid; ?>' , '<?php
    echo $i;?>' );"><?php
    img( "download.bc?id=".$this_photoid."&iw=".$rsize[0]."&ih=".$rsize[1] , "Photo: ".$this_photoname , "Photo: ".$this_photoname , $rsize[0] , $rsize[1] , "border: 1px solid #8baed5;" );
    ?></a><br />
    <div style="display:inline;"><div style="display:inline;" onclick="photo_select( '<?php
    echo $this_photoid; ?>' , '<?php
    echo $i;?>' );"><?php
        echo TextFadeOut( $this_photoname , New RGBColor( 51 , 102 , 153 ) , New RGBColor( 164 , 192 , 221 ) , 10 );
    ?></div></div>
    <div id="dropdownmenu_<?php
    echo $i;?>" style="display:none"><?php
    DropDownMenu( array(
    array( 'caption' => 'Make default picture' , 'js' => 'defaultmainpic(\'' . $this_photo->Id() . '\' , \''. $albumid . '\' , \''. $i . '\');' ),
    array( 'caption' => 'Delete this photo' , 'js' => 'delete_photo(\'' . $this_photo->Id() . '\' , \''. $i . '\');' )
    //array( 'caption' => 'Delete album' , 'js' => 'delete_album( \'' . $this_album->id() . '\' , \''. $i . '\');' )
    ) );?>
    </div>
    </div></div>