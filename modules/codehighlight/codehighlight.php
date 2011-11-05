<?php
    include "modules/module.php";
    
    function CodeHighlight( $source , $lang , $highlightextralines = "" ) {
        // GeSHi , http://qbnz.com/highlighter/
        include_once "modules/codehighlight/geshi/geshi.php";
        
        $geshi = New GeSHi( $source , $lang );
        /* 
        $geshi->enable_classes();
        $geshi->set_overall_class("bc_hlcode");
        */
        if( is_array( $highlightextralines ) && count( $highlightextralines ) ) {
            $geshi->highlight_lines_extra( $highlightextralines );
        }
        $geshi->set_line_style( 'background-color: #fcfcfc;' );
        // $geshi->set_highlight_lines_extra_style( 'border: 1px solid black;' );
        $geshi->enable_line_numbers( GESHI_NORMAL_LINE_NUMBERS );

        return $geshi->parse_code();
    }
    
    /* 
    function CodeHighLightCSS() {
        $geshi = New GeSHi( ? , ? );
        return $geshi->get_stylesheet( false );
    }
    */
?>