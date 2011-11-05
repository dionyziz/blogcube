<?php
    /* 
    Developer: feedWARd
    */
    
    function UserAgentParse( $uas ) {
        $rep = array( "; " => ";" );
        $uas = strtr($uas,$rep);
        $ser = Array( "#\([a-zA-Z0-9.:;-\s]*\)#" );
        $rep = Array( "" );
        $uasnopar = preg_replace( $ser , $rep , $uas );        
        $userstuff = Array(
            "browser" => "UNKNOWN",
            "browser_ver" => "UNKNOWN",
            "os" => "UNKNOWN",
            "language" => "UNKNOWN",
            "security" => "UNKNOWN",
            "platform" => "UNKNOWN"
        );
        $parenthesis = Array();
        $uasspacetok = Array(); //UserAgent string without the parentheses tokenized by space.
        $pos = 0;
        $spacetok = strtok( $uasnopar , " " );
        while ( $spacetok ) {
            $uasspacetok[$pos] = $spacetok;
            $spacetok = strtok(" ");
            $pos++;
        }
        $parenth = strtok( $uas , "(" );
        $parenth = strtok( ")" );
        $pos = 0;
        $par = strtok( $parenth , ";" );
        while ( $par ) {
            $parenthesis[ $pos ] = $par;
            $par = strtok( ";" );
            $pos++;
        }
        $opera_attr = explode("/",$uasspacetok[0]);
        $firefox_attr = explode("/",$uasspacetok[count($uasspacetok)-1]);
        if ( $opera_attr[0] == "Opera" ) { //BROWSER: OPERA
            //Opera/9.00 (Windows NT 5.1; U; en)
            $userstuff[ "browser" ] = "Opera";
            $userstuff[ "browser_ver" ] = $opera_attr[1];
            $userstuff[ "language" ] = $parenthesis[ 2 ];
            switch ( $parenthesis[ 1 ] ) {
                case "U":
                    $userstuff[ "security" ] = "Strong";
                    break;
                case "I":
                    $userstuff[ "security" ] = "Weak";
                    break;
                case "N":
                    $userstuff[ "security" ] = "None";
            }
        }
        else if ( $firefox_attr[0] == "Firefox" ) { //BROWSER: MOZILLA FIREFOX (Firefox)
            $userstuff[ "browser" ] = "Firefox";
            $userstuff[ "browser_ver" ] = $firefox_attr[1];
            $userstuff[ "language" ] = $parenthesis[ 2 ];
            $userstuff[ "platform" ] = $parenthesis[ 0 ];
            //security
            switch ( $parenthesis[ 1 ] ) {
                case "U":
                    $userstuff[ "security" ] = "Strong";
                    break;
                case "I":
                    $userstuff[ "security" ] = "Weak";
                    break;
                case "N":
                    $userstuff[ "security" ] = "None";
            }
        }
        else if ( $uasspacetok[1] == "Opera" ) { //BROWSER: OPERA (Identified as Mozilla)
            $userstuff[ "browser" ] = "Opera";
            $userstuff[ "browser_ver" ] = $uasspacetok[2];
            $userstuff[ "language" ] = $parenthesis[1];
            $userstuff[ "platform" ] = $parenthesis[0];
            //security
            switch ( $parenthesis[ 1 ] ) {
                case "U":
                    $userstuff[ "security" ] = "Strong";
                    break;
                case "I":
                    $userstuff[ "security" ] = "Weak";
                    break;
                case "N":
                    $userstuff[ "security" ] = "None";
            }            
        }
        else { //BROWSER: MICROSOFT INTERNET EXPLORER (MSIE)
            $secsptok = strtok( $parenthesis[ 1 ] , " " );
            $userstuff[ "browser" ] = $secsptok;
            $secsptok = strtok( " " );
            $userstuff[ "browser_ver" ] = $secsptok;
        }
        //operating system
        if ( $userstuff[ "browser" ] == "Opera" ) $parpos = 0;
        else $parpos = 2;
        switch ( $parenthesis[ $parpos ] ) {
            case "Windows NT 5.1":
                $userstuff[ "os" ] = "Windows XP";
                break;
            case "Windows NT 5.0":
                $userstuff[ "os" ] = "Windows 2000";
                break;
            case "Win 9x 4.90":
                $userstuff[ "os" ] = "Windows ME";
                break;
            case "Win98":
                $userstuff[ "os" ] = "Windows 98";
                break;
            case "Win95":
                $userstuff[ "os" ] = "Windows 95";
                break;
            case "WinNT4.0":
                $userstuff[ "os" ] = "Windows NT 4.0";
                break;
            case "WinNT3.51":
                $userstuff[ "os" ] = "Windows NT 3.11";
                break;
            case "Win3.11":
                $userstuff[ "os" ] = "Windows 3.11";
                break;
            case "68K":
                $userstuff[ "os" ] = "MacOS 68K";
                break;
            case "PPC":
                $userstuff[ "os" ] = "MacOS PPC";
                break;
            default:
                $userstuff[ "os" ] = $parenthesis[ $parpos ];
        }
        $ostok = strtok( $parenthesis[ $parpos ], " " );
        if ( strtolower( $ostok ) == "linux" ) {
            $userstuff[ "platform" ] = "Linux"; 
        }
        return $userstuff;
    }
?>