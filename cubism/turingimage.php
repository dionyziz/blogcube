<?php

// Only regenerate turing when turing value is cleared (after each try)
// This way it can share the number with other representations of the turing number  
if ( !isset( $_SESSION[ 'turingvalue' ] ) ) { 
    $rnum = mt_rand( 0 , 999999 );
    $_SESSION[ 'turingvalue' ] = $rnum;
} else {
    $rnum = $_SESSION[ 'turingvalue' ];
}
header( "Content-type: image/png" );


$im = @imagecreate( 110 , 20 ) or die( "Cannot Initialize new GD image stream" );
$bc = imagecolorallocate( $im , 255 , 255 ,55 );
imagecolortransparent ( $im , $bc );
$textcolor = imagecolorallocate( $im , 0 , 0 , 0 );

$sn1 =  (int)( $rnum /100000 ) ;
$sn2 =  (int)( ( $rnum /10000 ) % 10) ;
$sn3 =  (int)( ( $rnum /1000 ) % 10) ;
$sn4 =  (int)( ( $rnum /100 ) % 10) ;
$sn5 =  (int)( ( $rnum /10 ) % 10) ;
$sn6 =  (int)( $rnum % 10 ) ;

$snumber = sprintf( "%d %d %d %d %d %d" , $sn1 , $sn2 , $sn3 , $sn4 , $sn5 , $sn6 );
  
  imagestring( $im , 5 , 5 , 2 , $snumber , $textcolor );

for( $i = 0 ; $i < 5 ; $i++ ) {
    $x1 = rand( 1 , 100 );
    //$y1 = rand( 15 , 20 );
        //$xo = rand( 30 , 40 );
        $yo = rand( 50 , 90 );
    //    imageline( $im , $x1 , $y1 , ( $x1 + $xo ) , ( $y1 + $yo ), $textcolor );
    imagearc( $im , 55 , 20  , ( 110 - $x1 ) , $yo , 180 , 360 , $textcolor );
}

imagepng( $im );
?>