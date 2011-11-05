<?php
    /* 
    Module: Filters
    File: /modules/filters/filters.php
    Developer: dionyziz
    */
    include "modules/module.php";
    
    function css_filter( $str ) {
        /*
            Decrease loading time by removing unnecessary CSS whitespace
            In a CSS file, approximately the 35% of the data stored is whitespace.
        */
        global $nofilters;
        
        if( $nofilters ) 
            return $str;

        $rtn = $str;
        // tabs + newlines + winnewlines become spaces so that we can filter them out later
        $rtn = preg_replace( "#([\t\n\r])#" , " " , $rtn );
        $rtn = trim( $rtn );
        // remove /* CSS Comments */
        $rtn = preg_replace( "#([/][\*](.+?)[\*][/])#" , "" , $rtn );
        // remove spaces around CSS operators
        // e.g. body { background-color: white; }
        // will become body{background-color:white;}
        // perhaps we need to check for single quotation marks and url
        // and not replace within those as in background-image: url('test (image).jpg'); shouldn't become background-image:url('test(image).jpg');
        // TODO
        $rtn = preg_replace( "#[ ]*(\{|\}|\:|\(|\)|;)[ ]*#" , "\\1" , $rtn );
        return $rtn;
    }
    
    function js_filter( $str ) {
        /*
            make JS code as compact as possible for decreasing download time
            (whitespace usually takes up approximately 38% of the JS source code file size)
        */
        global $nofilters;
        
        if ( !$str )
            return;
            
        if( $nofilters ) 
            return $str;
            
        // remove all comments starting with //
        // but make sure that // inside quotations such as "Check out http://www.mozilla.org" are not counted as comments
        // this should be converted to a perl regular expression soon
        // for speed
        // TODO
        $alllines = split( "\n" , $str );
        $rtn = "";
        $i = 0;
        do {
            $alllines[ $i ] = trim( $alllines[ $i ] );
            $thisline = $wholeline = $alllines[ $i ];
            $insq = $indq = $inesc = false;
            for ( $j = 0 ; $j < strlen( $thisline ) ; $j++ ) {
                $c2 = substr( $thisline , $j , 2 );
                $c = substr( $c2 , 0 , 1 );
                switch( $c ) {
                    case "\"":
                        if ( $inesc ) {
                            $inesc = false;
                            break;
                        }
                        if ( !$insq )
                            $indq = !$indq;
                        break;
                    case "'":
                        if ( $inesc ) {
                            $inesc = false;
                            break;
                        }
                        if ( !indq )
                            $insq = !$insq;
                        break;
                    case "\\":
                        if ( $insq || $indq ) 
                            $inesc = !$inesc;
                        break;
                    default:
                        if ( $c2 == "//" && !$indq && !$insq ) {
                            $aftercomment = strtolower( trim( substr( $thisline , $j + 2 ) ) );
                            switch ( $aftercomment ) {
                                case "<<<<blogcube":
                                    $insection = 2;
                                    break;
                            }
                            if( $insection != 1 ) {
                                $wholeline = substr( $thisline , 0 , $j );
                                $j = strlen( $thisline );
                                if( $insection == 2 ) {
                                    $insection = 1;
                                }
                                else { 
                                    $insection = 0;
                                }
                            }
                            else {
                                $insection = 0;
                            }
                            break;
                        }
                }
            }
            if ( $wholeline )
                $rtn .= $wholeline . "\n";//$alllines[ $i ] . "\n";
        } while ( array_key_exists( ++$i , $alllines ) );
        /*
            this needs to be eventually fixed
            to **avoid replacement within strings**
            ("doubled quoted" or 'single quoted')
            TODO
        */
        // replace all tabs and newlines with spaces so that we can filter them out
        // if they are not necessary
        $rtn = preg_replace( "#(\t|\n|\r)#" , " " , $rtn );
        // remove all /* multilines comments */ (even inside strings, watch that!)
        $rtn = preg_replace( "#([/][\*](.+?)[\*][/])#" , "" , $rtn );
        // remove all spaces around operators
        // e.g. This + That will turn to This+That
        // as-many-spaces-as-you-want + operator + as-many-spaces-as-you-want => operator
        // something seems to be wrong when replacing This * That, spaces are not trimmed there (?)
        // we also need to make sure there are no replacements within strings/quotationmarks-surrounded-areas
        // TODO
        $rtn = preg_replace( "#[ ]*(\+|-|\\|\*|/|,|;|\=|\=\=|\{|\}|\(|\)|&&|&|\|\||\||<|>|<\=|>\=|\[|\])[ ]*#" , "\\1" , $rtn );
        return $rtn;
    }
    
    function html_filter_real( $src , $strict = false ) {
        /* 
            function html_filter(): Minimizes download time 
            for given source code by stripping out unnecessary stuff
        */
        global $nofilters;
        
        if( $nofilters )
            return $src;
            
        $space = " ";
        $dspace = $space . $space;
    
        // remove <!-- HTML Comments -->
        $src = preg_replace( "#([\<][!][-][-]((.|\n|\r|\t)+?)[-][-][\>])#" , "" , $src );
        
        $src = preg_replace( "#([\t\n\r])#" , " " , $src );
        $src = trim( $src );
        
        //replace all double-triple-.. spaces with single ones
        while ( strpos( $src , $dspace ) !== false ) {
            $src = str_replace( $dspace , $space , $src );
        }
        
        //strip unnecessary (?) spaces between tags
        if( $strict ) {
            $src = str_replace( "> <" , "><" , $src );
        }
        
        return $src;    
    }
    
    function html_filter( $src ) {
        return html_filter_real( $src );
    }
    
    function html_filter_strict( $src ) {
        return html_filter_real( $src , true );
    }
?>