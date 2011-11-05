<?php    
    function utf8_substr( $string , $start , $len , $byte = 3 ) {
        // stolen from http://www.php.net/manual/en/function.substr.php
        $str    = "";
        $count  = 0;
        $str_len = strlen($string);
        for ( $i = 0 ; $i < $str_len ; ++$i ) {
            if ( ( $count + 1 - $start ) > $len ) {
                $str  .= "...";
                break;
            }
            else if ( ( ord( substr( $string , $i , 1 ) ) <= 128 ) && ( $count < $start ) ) {
                ++$count;
            }
            else if ( ( ord( substr( $string , $i , 1 ) ) > 128 ) && ( $count < $start ) ) {
                $count = $count + 2;
                $i = $i + $byte - 1;
            }
            else if ( ( ord( substr( $string , $i , 1 ) ) <= 128 ) && ( $count >= $start ) ) {
                $str  .= substr( $string , $i , 1 );
                ++$count;
            }
            else if ( ( ord( substr( $string , $i , 1 ) ) > 128 ) && ( $count >= $start ) ) {
                $str .= substr( $string , $i , $byte );
                $count = $count + 2;
                $i = $i + $byte - 1;
            }
        }
        return $str;
    }

    function pluralize( $singular , $number ) {
        if ( $number > 0 ) {
            if ( $number > 1 ) {
                return $number . ' ' . $singular . 's';
            }
            else {
                return $number . ' ' . $singular;
            }
        }
        else {
            return 'No ' . $singular . 's';
        }
    }
    function myurlencode( $str ) {
        // deprecated, use safeencode instead
        $str = urlencode( $str );
        $str = str_replace( "+" , "%20" , $str );
        return $str;
    }
    
    function mystrtolower( $str ) {
        return strtolower( $str );
    }
    
    function escapedoublequotes( $str ) {
        return str_replace( '"' , '\\"' , str_replace( '\\' , '\\\\' , $str ) );
    }
    
    function escapesinglequotes( $str ) {
        $str = str_replace( '\'' , '\\\'' , str_replace( '\\' , '\\\\' , $str ) );
        $str = str_replace( "\n" , '\\n' , $str );
        $str = str_replace( "\r" , '\\r' , $str );
        return $str;
    }

    function safedecode( $str ) { // deprecated
        return $str;
    }
    
    function safeencode( $str ) {
        return urlencode( $str );
    }
    
    function chrislatin( $c ) {
        // check if a character is ASCII/latin
        switch( strtolower( $c ) ) {
            case 'a': case 'b': case 'c': case 'd': case 'e': case 'f': case 'g': case 'h':
            case 'i': case 'j': case 'k': case 'l': case 'm': case 'n': case 'o': case 'p':
            case 'q': case 'r': case 's': case 't': case 'u': case 'v': case 'w': case 'x':
            case 'y': case 'z': 
                return true;
            default:
                return false;
        }
    }
    
    function ContainsSingleQuotes( $str ) {
        return ( strpos( $str , "'" ) !== false );
    }
    
    function ContainsDoubleQuotes( $str ) {
        return ( strpos( $str , '"' ) !== false );
    }

    function RemoveExtension( $filename ) {
        $dotposition = strrpos( $filename , ".");
        if( $dotposition === false ) {
            return $filename;
        }
        $filename = substr( $filename , 0 , $dotposition );    
        return $filename;
    }
?>