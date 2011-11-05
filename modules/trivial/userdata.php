<?php
    include "modules/module.php";
    
    // private
    function GetUserBrowser() {
        global $userbrowser_name;
        global $userbrowser_version;
        global $userbrowser_detected;
        
/*        $browser = array (
            "MSIE",            // parent
            "OPERA",
            "MOZILLA",        // parent
            "NETSCAPE",
            "FIREFOX",
            "SAFARI",
            "K-MELEON"
        );
        
        $info[ "browser" ] = "OTHER";
        
        foreach ( $browser as $parent ) {
            $s = strpos( strtoupper( $_SERVER[ 'HTTP_USER_AGENT' ] ) , $parent );
            $f = $s + strlen( $parent );
            $version = substr( $_SERVER[ 'HTTP_USER_AGENT' ] , $f , 5 );
            $version = preg_replace( '/[^0-9,.]/' , '' ,$version );
            
            if ( strpos( strtoupper( $_SERVER[ 'HTTP_USER_AGENT' ] ) , $parent ) ) {
                $userbrowser_name = $parent;
                $userbrowser_version = $version;
            }
        } */
        $b = UserAgentParse( $_SERVER[ 'HTTP_USER_AGENT' ] );
        $userbrowser_name = strtoupper( $b[ 'browser' ] );
        $userbrowser_version = $b[ 'browser_ver' ];
        $userbrowser_detected = true;
    }
    
    function UserBrowser() {
        global $userbrowser_detected;
        global $userbrowser_name;
        
        if ( !$userbrowser_detected ) {
            GetUserBrowser();
        }
        return $userbrowser_name;
    }
    
    function UserBrowserVersion() {
        global $userbrowser_detected;
        global $userbrowser_version;
        
        if ( !$userbrowser_detected ) {
            GetUserBrowser();
        }
        return $userbrowser_version;
    }

    function UserIp() {
        if( isset( $_SERVER[ "HTTP_CLIENT_IP" ] ) && $_SERVER[ "HTTP_CLIENT_IP" ] ) {
            return $_SERVER[ "HTTP_CLIENT_IP" ];
        } 
        else {
            return $_SERVER[ "REMOTE_ADDR" ];
        }
    }
?>