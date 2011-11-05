<?php
    final class Wind_gmail_csv extends Wind_gmail { 
        private $csv;
        private $debug = '';
        public $ambiguities = 0;
        
        public function Wind_gmail_csv ( $csv ) {
            // Parsing of a comma-separated list goes here
            if ( strlen($csv) < 1 )
                return false;
            $this->csv = $csv;
            
            $csv = preg_replace( array( '@".*?"@s', '@\r?\n@s' ), array( '', "\n" ), $csv );
            list( $header, $body ) = explode( "\n", $csv, 2 );
            $headers = explode( ',', $header );
/*            foreach ($headers as $field) {
                $this->debug .= $field . ", ";
            }
            $this->debug .= "\n";
            $this->debug .= "----------\n"; */
            
            $namespace = array_search( 'Name', $headers );
            if ($namespace === false) 
                bc_die( "Invalid CSV! No `Name` found.<br />\n" . "<pre>$csv</pre>");
            $emailspace = array();
            if ( $tempkey = array_search( 'E-mail', $headers )) $emailspace[] = $tempkey;
            if ( $tempkey = array_search( 'Section 1 - Email', $headers )) $emailspace[] = $tempkey;
            if ( $tempkey = array_search( 'Section 2 - Email', $headers )) $emailspace[] = $tempkey;
            if ( $tempkey = array_search( 'Section 3 - Email', $headers )) $emailspace[] = $tempkey;

            $pieces = explode( "\n", $body );
            foreach ($pieces as $piece) {
                $fields = explode( ',', $piece );
/*                foreach ($fields as $field) {
                    $this->debug .= $field . ", ";
                }
                $this->debug .= "\n"; */

                if ( array_key_exists( $namespace, $fields ) ) $name = $fields[ $namespace ];
                
                foreach ($emailspace as $this_emailspace) {
                    if ( array_key_exists( $this_emailspace, $fields ) ) {
                        $email = $fields[ $this_emailspace ];
                        if ( strlen( $email ) > 0 )
                            if (!( $this->Add( $email, $name ) ))
                                $this->ambiguities++;
                    }
                }
            }
            
        }
        
        public function ShowCSV() {
            return $this->csv;
        }
        
        public function ShowDebug() {
            return $this->debug;
        }
    }
?>