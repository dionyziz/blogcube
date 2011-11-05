<?php
    define( PI , 3.14159265358979 );

    function Div( $number , $divisor ) {
        return ( $number - ( $number % $divisor ) ) / $divisor;
    }
?>