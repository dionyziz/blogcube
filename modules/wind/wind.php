<?php
    /* 
    Module: Math
    File: /modules/wind/wind.php
    Developer: Makis
    */

    include "modules/module.php";    
    
    class Wind {
        private $contacts = Array();
        private $userdata = Array();
        
        public function Add( $email, $name = '' ) {
            if ( strlen( $email ) == 0 || array_key_exists( $email, $this->contacts ) ) {
                return false;
            }
            $this->contacts[ $email ] = $name;
            $this->LookupDB( $email );
            return true;
        }
        
        public function Exists( $email ) {
            return array_key_exists( $email, $this->contacts );
        }
        
        public function Reset() {
            reset( $this->userdata );
            reset( $this->contacts );
        }
        
        public function Pop() {
            //return each( $this->contacts );
            if ( list( $key, $val ) = each( $this->userdata ) ) {
                return array( $key, $this->contacts[ $key ] );
            }
            return false;
        }
        
        public function Count() {
            return count( $this->contacts );
        }

        public function Sort() {
            arsort( $this->userdata );
        }

        public function Whois( $email ) {
            if ( !array_key_exists( $email, $this->contacts ) ) {
                return false;
            }
            return $this->contacts[ $email ];
        }
        
        public function LookupDB( $email ) {
            global $users;

            // note: $email should not contain any ' or \ characters anyway, so e-mail verification (and potential rejection)
            // would be more accurate than bcsql_escape --dionyziz
            $sqlq = "SELECT 
                    `user_id`, 
                    `user_username`, 
                    `user_firstname`, 
                    `user_lastname`
                FROM 
                    `$users`
                WHERE 
                    `user_email` = '" . bcsql_escape( $email ) . "'
                LIMIT 1;";
            /* AND `user_publicemail` = 'public'" . */
            $sqlr = bcsql_query( $sqlq );
            if( bcsql_num_rows( $sqlr ) ) {
                $thisuser = bcsql_fetch_array( $sqlr );
                $this->userdata[ $email ] = $thisuser;
                return true;
                 /*array( 
                    $thisuser[ "user_id" ], 
                    $thisuser[ "user_username" ], 
                    $thisuser[ "user_firstname" ], 
                    $thisuser[ "user_lastname" ]
                ); */
            } 
            else {
                $this->userdata[ $email ] = 0;
            }
        }
        
        public function Lookup( $email ) {
            if ( array_key_exists( $email, $this->userdata ) ) {
                $thisuser = $this->userdata[ $email ];
                if ( is_array( $thisuser ) ) {
                    return array( 
                        $thisuser[ "user_id" ], 
                        $thisuser[ "user_username" ], 
                        $thisuser[ "user_firstname" ], 
                        $thisuser[ "user_lastname" ]
                    );
                }
            }
        }
    }

    include "modules/wind/gmail.php";

?>