<?php
    # Developer: dionyziz
    
    include "modules/module.php";
    
    $shortmonths = Array( "Jan" , "Feb" , "Mar" , "Apr" ,
                  "May" , "Jun" , "Jul" , "Aug" ,
                  "Sep" , "Oct" , "Nov" , "Dec" );
    
    $months = Array( "January" , "February" , "March" , "April" ,
             "May" , "June" , "July" , "August" ,
             "September" , "October" , "November" , "December" );

    define( 'BCT_BIG_BANG' , '2005-10-25 17:56:29' ); // GMT
    define( 'BCT_OFFSET' , 64 ); // BCT years
    define( 'BCT_FACTOR' , 4 * PI );

    function BCT2GMT( $bct /* :SQL DATETIME */ ) {
        $bigbang = strtotime( BCT_BIG_BANG , 0 );
        $bcta = explode("-", $bct, 2);
        $bct = ($bcta[ 0 ] - BCT_OFFSET) . "-" . $bcta[ 1 ];
        $time = strtotime( $bct , 0 );
        $gmt = $time;
        $gmt -= $bigbang;
        $gmt /= BCT_FACTOR;
        $gmt += $bigbang;
        return date( "Y-m-d H:i:s" , $gmt );
    }

    function GMT2BCT( $gmt /* :SQL DATETIME */ ) {
        $bigbang = strtotime( BCT_BIG_BANG , 0 );
        $time = strtotime( $gmt , 0 );
        $bct = $time;
        $bct -= $bigbang;
        $bct *= BCT_FACTOR;
        $bct += $bigbang;
        $years = date( "Y" , $bct );
        $years = $years + BCT_OFFSET;
        return $years . date( "-m-d H:i:s" , $bct );
    }

    function UniqueTimeStamp() {
        $Asec = explode( " " , microtime() );
        $Amicro = explode( "." , $Asec[ 0 ] );
        return $Asec[ 1 ] . substr( $Amicro[ 1 ] , 0 , 4 );
    }

    function HumanDate( $date ) {
        global $shortmonths;
        
        ParseDate( $date , $year , $month , $day , $hour , $minute , $second );
        return $day . " " . $shortmonths[ $month - 1 ] . " $year, $hour:$minute:$second";
    }
    
    function HumanSolDate( $date ) {
        global $shortmonths;
        
        ParseSolDate( $date , $year , $month , $day );
        return $day . " " . $shortmonths[ $month - 1 ] . " $year";
    }
    
    function ParseDate( $date , &$year , &$month , &$day , &$hour , &$minute , &$second ) {
        if ( !$date || $date == "0000-00-00 00:00:00" )
            return;
            
        $dateElements = split(' ', $date);
        ParseSolDate( $dateElements[ 0 ] , $year , $month , $day );
        
        $dateTimeElements = split(':', $dateElements[ 1 ] );
        
        $hour     = $dateTimeElements[ 0 ];
        $minute = $dateTimeElements[ 1 ];
        $second = $dateTimeElements[ 2 ];
    }

    function ParseSolDate( $date , &$year , &$month , &$day ) {
        if ( !$date || $date == "0000-00-00" )
            return;
            
        $dateDateElements = split('-', $date );
        $year     = $dateDateElements[ 0 ];
        $month     = $dateDateElements[ 1 ];
        $day     = $dateDateElements[ 2 ];
    }
    
    function NowDate() {
        return gmdate("Y-m-d H:i:s", time() );
    }
    
    function NowSolDate() {
        return gmdate("Y-m-d", time() );
    }
    
    function DateDiff( $olddate = "0000-00-00 00:00:00" , $recentdate = "0000-00-00 00:00:00" ) {
        // returns the number of seconds elapsed between $olddate and $recentdate, or the number of seconds from $olddate until now, if $recentdate is not passed
        if( $olddate == "0000-00-00 00:00:00" )
            return false;
            
        if( $recentdate == "0000-00-00 00:00:00" )
            $recentdate = NowDate();
        
        $recentdatesec = strtotime( $recentdate );
        $olddatesec = strtotime( $olddate );
        
        return $recentdatesec - $olddatesec;
    }
    
    function FullDate( $datetime ) {
        // $datetime in SQL DATETIME format
        return date( "l, F d, Y" , strtotime( $datetime ) );
    }
    
    function BCDate( $datetime ) {
        $diff = DateDiff( $datetime );
        if( $diff < 0 ) {
            $diff = -$diff;
        }
        if( $diff < 60 ) {
            return "a few seconds ago";
        }
        $minute = 60;
        $hour = $minute * 60;
        if( $diff < $hour ) {
            $minutes = Div( $diff , $minute );
            if( $minutes < 5 ) {
                return "a few minutes ago";
            }
            if( $minutes < 10 ) {
                return "10 minutes ago";
            }
            if( $minutes < 15 ) {
                return "15 minutes ago";
            }
            if( $minutes < 25 ) {
                return "20 minutes ago";
            }
            if( $minutes < 35 ) {
                return "half an hour ago";
            }
            if( $minutes < 50 ) {
                return "45 minutes ago";
            }
            return "an hour ago";
        }
        if( $diff < $hour * 23 ) {
            $hours = Div( $diff , $hour );
            if( $hours == 1 ) {
                return "an hour ago";
            }
            return Div( $diff , $hour ) . " hours ago";
        }
        $day = $hour * 24;
        if( $diff < $day * 2 ) {
            return "yesterday";
        }
        if( $diff < $day * 6 ) {
            return Div( $diff , $day ) . " days ago";
        }
        $week = $day * 7;
        if( $diff < $week + $day ) {
            return "a week ago";
        }
        $xdatetime = explode( " " , $datetime );
        return HumanSolDate( $xdatetime[ 0 ] );
    }
?>