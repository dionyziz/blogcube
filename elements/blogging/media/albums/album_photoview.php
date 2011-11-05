<?php
    $photooid = $_POST[ "photooid" ];
    $divid = $_POST[ "divid" ];
    $photooclass = New photo( $photooid );
    $photowidth = $photooclass->width();
    if( $photowidth>400 ) {
        $dphotosize = $photooclass->proportional_size( 400 , $photooclass->height() );
    }
    else {
        $dphotosize[ 0 ] = $photooclass->width();
        $dphotosize[ 1 ] = $photooclass->height();
    }?><div style="display:table-cell;"><a href="download.bc?id=<?php
    echo $photooclass->Id();?>" target="_blank"><?php
    img( "download.bc?id=".$photooclass->Id()."&iw=".$dphotosize[ 0 ]."&ih=".$dphotosize[ 1 ] , "Photo: ".$photooclass->filename() , "Photo: ".$photooclass->filename() , $dphotosize[ 0 ] , $dphotosize[ 1 ] , "border: 1px solid #8baed5;" );
    ?></a></div><div style="display:table-cell;vertical-align:top;padding-left:5px">
    Filename: <a href="download.bc?id=<?php
    echo $photooclass->Id();?>" target="_blank"><?php
        echo $photooclass->filename();?></a><?php
    ?> <a href="" onclick="delete_photo( '<?php
    echo $photooid;?>' , '<?php
    echo $divid;?>' );return false"><?php
        img( "images/nuvola/delete.png" , "Photo deletion" , "Delete photo: ".$photooclass->filename() );?></a><br />
    Dimentions: <?php
        echo $photooclass->width()."x".$photooclass->height();?> pixels<br />
    Size: <?php     
        echo HumanSize( $photooclass->size() );?><br />
    <?php /*
    img( "images/nuvola/delete.png" , "Photo deletion" , "Delete photo: ".$photooclass->filename() );
    ?><a href="javascript:delete_photo( '<?php
    echo $photooid;?>' , '<?php
    echo $divid;?>' );">Delete photo</a>*/?>
</div>    