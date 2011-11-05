<?php
    include "modules/module.php";
    
    // function WYSIWYG
    function BlogCute( $srctext , $level = 0 ) {
        bc_ob_section();
        
        $text = $srctext;
        
        /* while( ) {
            
        } */
        
        // $text = nl2br( $text );
        $firstletter = substr( $text , 0 , 1 );
        $allbutfirst = substr( $text , 1 , strlen( $text ) );
        if ( chrislatin( $firstletter ) ) {
            // to add a "big style" letter, we require it to be a latin letter (simple ASCII, not unicode)
            ?><span class="firstletter"><?php
            echo strtoupper( $firstletter );
            ?></span><?php
            echo $allbutfirst;
        }
        else {
            echo $text;
        }
        $returnval = bc_ob_fallback();
        $returnval = preg_replace( "#[ ]{2}#" , "&nbsp;&nbsp;" , $returnval );
        return $returnval;
    }
    
    function BlogCuteTag( $tagname , $tagcontents ) {
        
    }
    
    function WYSIWYG2BlogCute( $wysiwyghtm ) {
        return $wysiwyghtm;
    }
?>