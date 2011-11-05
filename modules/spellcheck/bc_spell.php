<?php
    /* 
    Module: SpellCheck
    File: /modules/spellcheck/bc_pspell.php
    Developer: Dionyziz
    Collection of easy-to-use functions to access the aspell spellchecker
    in a fast and handy way, which use direct aspell command line executes
    */
        
    function bc_spell_start( $words ) {
        global $bc_spell_wordcorrect;
        global $bc_spell_wordsuggestions;
        
        if( !is_array( $words ) ) {
            bc_die( "bc_spell: bc_spell_start() first and only parameter should be an array" );
        }
        
        $bc_spell_wordcorrect = Array();
        $bc_spell_wordsuggestions = Array();
        
        $tempfile = tempnam( "/tmp", "blogcube_spell_" );
        $aspellcommand= "cat $tempfile | /usr/local/bin/aspell -a";
        
        $fp = fopen( $tempfile , "w" );
        if( !$fp ) {
            bc_die( "bc_spell: Failed to create temporary file for spellchecking on bc_spell_start()" );
        }
        fwrite( $fp , "!\n" );
        for( $i = 0 ; $i < count( $words ) ; $i++ ) {
            if( strpos( $words[ $i ] , "\n" ) !== false ) {
                bc_die( "bc_spell: Each word in the wordlist passed to bc_spell_start() must not contain any line breaks" );
            }
            fwrite( $fp , "^" . $words[ $i ] . "\n" );
        }
        fclose( $fp );
        
        $return = shell_exec( $aspellcommand );
        unlink( $tempfile );
        
        $mistakes = 0;
        $rlines = explode( "\n" , $return );
        for( $i = 0 ; $i < count( $rlines ) ; $i++ ) {
            $line = $rlines[ $i ];
            switch( substr( $line , 0 , 1 ) ) {
                case "&":
                    $corrections = explode( " " , $line );
                    $word = $corrections[ 1 ];
                    $sugstart = strpos( $line , ":" ) + 2;
                    $suggestions = substr( $line , $sugstart );
                    $sugarray = explode( ", " , $suggestions );
                    $wordcorrect[ $word ] = false;
                    $wordsuggestions[ $word ] = $sugarray;
                    $mistakes++;
                    break;
                case "#":
                    $wordcorrect[ $word ] = false;
                    $wordsuggestions[ $word ] = Array();
                    $mistakes++;
                    break;
                default:
                    $wordcorrect[ $word ] = true;
                    $wordsuggestions[ $word ] = Array();
            }
        }
        return $mistakes;
    }
    function bc_spell_check( $word ) {
        global $bc_spell_wordcorrect;
        
        return $bc_spell_wordcorrect[ $word ];
    }
    function bc_spell_suggest( $word ) {
        global $bc_spell_wordsuggestions;
        
        return $bc_spell_wordsuggestions[ $word ];
    }
?>