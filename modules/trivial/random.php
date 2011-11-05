<?php
    // due to Laurentiu Tanase <expertphp@yahoo.com>, version 1.1
    
    class Random {
        private $_vcrs;
        private $_vnum;
        private $_vnot;
    
        /**
         * Constructor
         *
         * Set default values
         *
         * @param  string  $crs   Characters output
         * @param  bool    $num   With number
         * @param  bool    $not   With not number
         */
    
        public function Random( $crs = false , $num = false , $not = false ) {
            $this->_vnum = $num;
            $this->_vnot = $not;
            if( !$crs ){
                $this->_vcrs = "0123456789".
                    "abcdefghijklmnopqrstuvwxyz".
                    "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
            }
            else {
                $this->_vcrs = $crs;
                if( ( $num || $not ) && !$this->_rec( $crs , $num , $not ) ){
                    if( $num && $not ) 
                        $err = "number or not number";
                    else if( $num )
                        $err = "number";
                    else if( $not )
                        $err = "not number";
                    else 
                        $err = "comparation";
                    trigger_error( "Class Random - String input [ " . $crs . " ] doesn't have " . $err , E_USER_ERROR );
                }
            }
        }
    
        /**
         * Compare input string
         *
         * @access  private
         * @param   string   $str      Characters input
         * @param   bool     $number   Have number
         * @param   bool     $notnum   Have not number
         * @return  bool     If have number or/and not number
         */
    
        private function _rec( $str , $number = true , $notnum = true ) {
            $cnt = strlen( $str );
            $set1 = $set2 = false;
            if( $number || $notnum ){
                for( $i = 0; $i < $cnt; $i++ ) {
                    if( $str{ $i } === strval( intval( $str{ $i } ) ) ) 
                        $set1 = true;
                    else 
                        $set2 = true;
                    if( $set1 && $set2 )
                        break;
                }
                if( $number && $notnum )
                    return ( $set1 && $set2 );
                else if( $number ) 
                    return $set1;
                else if( $notnum ) 
                    return $set2;
                else 
                    return true;
            }
            else 
                return true;
        }
    
        /**
         * Generate random characters
         *
         * @access  public
         * @param   int      $len   Length of the string you want generated
         * @return  string   Random characters
         */
    
        public function get( $len ){
            if( !( is_int( $len ) && $len > 0 ) ) 
                return $this->_vcrs;
            $ret = "";
            $cnt = strlen( $this->_vcrs ) - 1;
            for ( $i = 0 ; $i < $len ; $i++ )
                $ret .= $this->_vcrs{ rand( 0 , $cnt ) };
            if( $this->_vnum || $this->_vnot) 
                return $this->_rec( $ret , $this->_vnum , $this->_vnot ) ? $ret : Random::get( $len );
            else 
                return $ret;
        }
    }
?>