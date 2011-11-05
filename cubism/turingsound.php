<?php

// Only regenerate turing when turing value is cleared (after each try)
// This way it can share the number with other representations of the turing number  
if ( !isset( $_SESSION[ 'turingvalue' ] ) ) { 
    $rnum = mt_rand( 0 , 999999 );
    $_SESSION[ 'turingvalue' ] = $rnum;
} else {
    $rnum = $_SESSION[ 'turingvalue' ];
}
$flitepath = "/usr/local/bin/flite";

if ( is_numeric( $rnum ) ) {
    $sn1 =  (int)( $rnum /100000 ) ;
    $sn2 =  (int)( ( $rnum /10000 ) % 10) ;
    $sn3 =  (int)( ( $rnum /1000 ) % 10) ;
    $sn4 =  (int)( ( $rnum /100 ) % 10) ;
    $sn5 =  (int)( ( $rnum /10 ) % 10) ;
    $sn6 =  (int)( $rnum % 10 ) ;
    $snumber = sprintf( "%d ,,,, %d ,,,, %d ,,,, %d ,,,, %d ,,,, %d" , $sn1 , $sn2 , $sn3 , $sn4 , $sn5 , $sn6 );
    $text = "The number you nead to enter is: " . $snumber .", i repeat: " . $snumber;
} else {
    $text = "A security error has occured. Please contact us about this error.";
}
$audiopath = "/tmp/";
$file = "turingwave" . UniqueTimeStamp();

shell_exec( "$flitepath -t \"$text\" -o $audiopath$file.wav" );

header( "Content-type: audio/x-wav" );
header( "Content-Disposition: attachment;filename=turing" . UniqueTimeStamp() . ".wav" );

echo file_get_contents( $audiopath . $file . ".wav" );
         
 //delete temporary file
 @unlink( $audiopath . $file . ".wav");
   

   



?>