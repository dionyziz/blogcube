<?php
    function Element( $path )
    {
        // BlogCube globals; don't over do it
        global $user;
        global $systemurl;
        global $debug_version;
        global $revision;
        global $water;
        global $bfc;
        global $evc;
        global $anonymous;
        global $domainname;
        global $arrow;
        global $permissions_logs; //temporary -> it needs to be read-only, so it'll probably become a class
            
        return include $evc->GetElement( $path );
    }

    final class ElementsVersionControl {
        private $mElements;
        
        public function RegisterVersionedElement( $element ) {
            $this->mElements[ $element ] = true;
        }
        public function RegisterVersionedElementGroup( $elementgroup ) {
            // TO DO
        }
        public function VersionControl() {
            $this->mElements = array();
        }
        public function GetElement( $element ) {
            global $debug_version;
            
            $ret = 'elements/';
            if ( isset( $this->mElements[ $element ] ) ) {
                if ( $debug_version ) {
                    $ret .= $element . '.php';
                }
                else {
                    $lastslash = strrpos( $element , '/' );
                    $filename = substr( $element , $lastslash + 1 );
                    $path = substr( $element , 0 , $lastslash );
                    $ret .= $path . '/stable.' . $filename . '.php';
                }
            }
            else {
                $ret .= $element . '.php';
            }
            bc_assert( file_exists( $ret ) );
            return $ret;
        }
    }
    
    $evc = New ElementsVersionControl();
    
    include 'modules/versioncontrol/elements.php';
?>