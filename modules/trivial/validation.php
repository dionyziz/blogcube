<?php
    include "modules/module.php";
    
    function ValidInteger( $x ) {
        if( is_numeric( $x ) ) {
            return ( intval( $x ) == $x );
        }
        else {
            return false;
        }
    }
    
    function ValidNonNegativeInteger( $x ) {
        if( is_numeric( $x ) ) {
            $z = intval( $x );
            return ( ( $z == $x ) && ( $z >= 0 ) );
        }
        else {
            return false;
        }
    }
    
    function ValidNNI( $x ) {
        // alias
        return ValidNonNegativeInteger( $x );
    }
    
    function ValidPI( $x ) {
        // Valid Positive Integer
        return ValidId( $x );
    }
    
    function ValidId( $x ) {
        if( is_numeric( $x ) ) {
            $z = intval( $x );
            return ( ( $z == $x ) && ( $z > 0 ) );
        }
        else {
            return false;
        }
    }
    
    function ValidIP( $ip ) { // blame ch-world
        // returns true if the passed parameter $ip is a valid IP address.
        return ereg("(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9])\.(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9]|0)\.(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9]|0)\.(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[0-9])", $ip);
    }

    function ValidIPRange( $ip ) { // blame ch-world
        // returns true if the passed parameter $ip is a valid IP address.
        return ereg("(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9])\.(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9]|0|\*)\.(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9]|0|\*)\.(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[0-9]|\*)", $ip);
    }
    
    function ValidEmail( $email ) { // blame ch-world
        return ereg( "^[a-zA-Z0-9\._-]+".
            "@".
            "([a-zA-Z0-9][a-zA-Z0-9-]*[a-zA-Z0-9]\.)+".
            "([a-zA-Z]+\.)?".
            "([a-zA-Z]+)$", $email );
    }
    
    function ValidICQ( $icqnumber ) { // blame ch-world
        $icqnumber = str_replace( "-" , "" , $icqnumber );
        if( ValidId( $icqnumber ) ) {
            // yes it's a number, positive and integer =)
            if( ( $icqnumber >= 10000 ) && ( $icqnumber <= 2147483646 ) ) {
                return true;
            } 
            else {
                return false;
            }            
        } 
        else {
            return false;
        }
    }
?>