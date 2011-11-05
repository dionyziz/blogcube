<?php
    /*
    Database wrapper for prepared queries
    Developers: Jean, Dionyziz
    */
    
    final class BCPreparedQuery {
        private $mPrepared;
        private $mDb;
        
        public function BCPreparedQuery( $sql , $database ) { // constructor
            $this->mDb = $database;
            
            $sql = str_replace( "%" , "%%", $sql );//    escape % 
            
            $countbackslash = 0;
            $countquote = 0;
            for ( $i=0; $i<strlen($sql); $i++ ) {
                // Order is important
                if ( $sql[ $i ] == "?" && ( $countquote % 2 ) == 0 && ( $countbackslash % 2 ) == 0 ){ //replace ? for  
                    $sql = substr( $sql, "%s", $i, 1 );
                }
                                
                if ( $sql[ $i ] == "'" && ( $countbackslash % 2 ) == 0 ) { // count the quote when it's not escaped
                    $countquote++;
                }
                                
                if ( $sql[ $i ] == "\\" ) { // count consecutive backslashes
                    $countbackslash++;
                } else {
                    $countbackslash = 0;
                }
            }
            $this->mPrepared = $sql;
        }
        public function Execute( $bindings = array() , $types = '' ) {
            global $water; // debugging engine
            if ( !is_array( $bindings ) ) {
                $bindings = array( $bindings );
                
            }
            if ( count( $bindings ) != strlen( $types ) ) {
            
                $water->ThrowException( 'Number of parameters is different from number of types' );
            }
            
            $sqlbuild = array_map( array( $this , "Generate" ) , $types , $bindings ); // generate every binding
            array_unshift( $sqlbuild , $this->mPrepared ); // sql will be the first argument
            $resultsql = call_user_func_array( "sprintf" , $sqlbuild ); // call sprintf with the an array of arguments
                                    
            return $this->mDb->Query( $resultsql );
        }
        private function Generate( $type , $content ) {
            global $water; // debugging engine
            
            switch ( $type ) {
                case ' ': // reserved; empty argument
                    return '';
                case 'i': // integer
                    $content = mysql_real_escape_string( $content );
                    return $content;
                case 's': // string
                    $content = '\'' . mysql_real_escape_string( $content ) . '\'';
                    return $content;
                case 'd': // float
                    $content = mysql_real_escape_string( $content );
                    return $content;
                case 'b': // binary
                    $content = '\'' . mysql_real_escape_string( $content ) . '\'';
                    return $content;
                default: // invalid
                    $water->ThrowException( 'Invalid type during SQL preperation' );
            }
        }
    }
?>
