<?php
    include "images/dynamic/image.php";
    
    $nummsgs = $_GET[ "n" ];
    $res = @imagecreatefrompng( "images/silk/page_white.png" );
    imagealphablending( $res , false );
    imagesavealpha( $res , true );
    if( ValidId( $nummsgs ) ) {
        $nummsgs = intval( $nummsgs );
        $w = imagesx( $res );
        $h = imagesy( $res );

        $tc = imagecolorallocate( $res , 35 , 53 , 179 );
        if( $nummsgs > 999 ) {
            $xpos = 3;
            $tx = "";
        }
        else if( $nummsgs > 99 ) {
            $xpos = 0;
            $ypos = 3;
            $fsize = 1;
            $tx = $nummsgs;
        }
        else if( $nummsgs > 9 ) {
            $xpos = 2;
            $ypos = 3;
            $tx = $nummsgs;
            $fsize = 1;
        }
        else {
            $xpos = 5;
            $ypos = 1;
            $tx = $nummsgs;
            $fsize = 3;
        }
        if( $tx ) {
            imagestring( $res , $fsize , $xpos , $ypos , $tx , $tc );
        }
    }
?>