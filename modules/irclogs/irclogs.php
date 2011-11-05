<?php
    include "modules/module.php";
    include("modules/irclogs/irccolorclass.php");

    $logdirpath = "/home/dionyziz/irclogs/";
    
    function IRCLogDateCmp( $a, $b ) {
            $month = array(
                    'Jan' => 1,
                    'Feb' => 2,
                    'Mar' => 3,
                    'Apr' => 4,
                    'May' => 5,
                    'Jun' => 6,
                    'Jul' => 7,
                    'Aug' => 8,
                    'Sep' => 9,
                    'Oct' => 10,
                    'Nov' => 11,
                    'Dec' => 12
            );
            $a_yr = substr( $a, -4, 4 );
            $b_yr = substr( $b, -4, 4 );
            if ( $a_yr < $b_yr ) return 1;
            elseif ( $a_yr > $b_yr ) return -1;
    
            $a_m = substr( $a, -7, 3 );
            $b_m = substr( $b, -7, 3 );
            if ( $month[ $a_m ] < $month[ $b_m ] ) return 1;
            elseif ( $month[ $a_m ] > $month[ $b_m ] ) return -1;
    
            $a_d = substr( $a, -9, 2 );
            $b_d = substr( $b, -9, 2 );
            if ( $a_d < $b_d ) return 1;
            elseif ( $a_d > $b_d ) return -1;
            return 0;
    }
    function IRCLogList( $where ) {
        global $logdirpath;
        global $permissions_logs;
        global $user;
        switch( $where ) {
            case 'alpha':
            case 'blogcube':
            case 'developers':
                if ( !in_array( $user->Username() , $permissions_logs[ $where ] ) )
                    bc_die( "Restricted" );
                break;
            default:
                return false;
        }

        $files = glob( $logdirpath . "$where.log.*" );
        $return = array();
        foreach( $files as $file ) {
            $return[] = substr( $file, -9 );
        }
        usort( $return, "IRCLogDateCmp" );
        
        return $return;
    }
    function IRCLogView( $where, $logdate ) {
        global $logdirpath;
        global $permissions_logs;
        global $user;
        if ( strlen( $logdate ) != 9 )
            return false;
        switch( $where ) {
            case 'alpha':
            case 'blogcube':
            case 'developers':
                if ( !in_array( $user->Username() , $permissions_logs[ $where ] ) )
                    return false; // Restricted
                break;
            default:
                return false;
        }
        
        $IRCasHTML = array();
        $IRCasText = explode( "\n", file_get_contents( $logdirpath . "$where.log." . $logdate ) );
        foreach ( $IRCasText as $irctext ) {
            $c = new IRCtoHTML( $irctext );
            $IRCasHTML[] = $c->GetHTML();
        }
        return nl2br( implode( "\n", $IRCasHTML ) );
    }
?>