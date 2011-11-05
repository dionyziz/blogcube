<?php
    /*
        Developer: dionyziz
        Server-side part of bfc
    */

    include "modules/bfc/opt.php";

    class BFC {
        /*
            allows dynamic code execution (JSON)
            by inserting a special element
            with id bfc_(target)_(id)
            This special element is parsed by the
            ajax downloader (/js/ajax) and its contents
            are executed as if normal javascript code
            this allows us to load certain javascript code on-the-fly
            (or, in simple words, have some piece of code that loads
            all other pieces of code as requested)
        */
        private $mCode;
        private $mElementId;
        private $mInBfc;
        private $mDebug;
        private $mLibraries;
        private $mDependencies;
        private $mLibrariesDebug;
        private $mCurrentlyFiltering;

        public function bfc($id) {
            $this->mCode = '';
            $this->mElementId = $id;
            $this->mDebug = false;
            $this->mLibrariesDebug = array();
            $this->mDependencies = array();
            $this->mLibraries = array();
        }
        public function DebugNotice( $str ) {
            global $debug_version;
            global $bfc_debuggers;
            global $user;
            
            if ( $debug_version && in_array($user->Username(), $bfc_debuggers) ) {
                if ( $this->mInBfc ) {
                    $bfcformat = $this->mCurrentlyFiltering;
                    $bfcexclusive = false;
                    $this->end();
                }
                $this->start( false , false );
                ?>Debug.Notice('<?php
                echo escapesinglequotes( $str );
                ?>');<?php
                $this->end();
                if ( $bfcexclusive === false ) {
                    $this->start( false , $bfcformat );
                }
            }
        }
        public function start( $enabledebugging = false , $enablefiltering = true ) {
            global $debug_version;
            
            if ( $this->InBfc ) {
                bc_die( "Already in BFC!" );
            }
            if ( $enabledebugging && $debug_version ) {
                $this->mDebug = true;
            }
            $this->mCurrentlyFiltering = $enablefiltering;
            $this->mInBfc = true;
            bc_ob_end_flush();
            bc_ob_start( "bfc_ob" );
        }
        public function end() {
            bc_ob_end_flush();
            
            if ( !$this->mInBfc ) {
                bc_die( "Not in BFC!" );
            }
            $this->mInBfc = false;
            bc_ob_start( "html_filter" );
        }
        public function exec() {
            if ( $this->mDebug ) {
                ?><br /><b>The following BFC code would have been executed:</b><br /><?php
                ?><div style="font-size:110%"><?php
                echo CodeHighlight( $this->mCode , "Javascript" );
                ?></div><?php
            }
            else {
                if ( !empty( $this->mCode ) ) {
                    ?><div id="bfc_<?php
                    echo $this->mElementId;
                    ?>" style="display:none"><?php
                    ob_end_flush();
                    
                    echo htmlspecialchars( $this->mCode );
                    
                    ob_start( "html_filter" );
                    ?></div><?php
                }
            }
        }
        public function ob( $src ) {
            if ( $this->mCurrentlyFiltering ) {
                $src = js_filter( $src );
            }
            $this->mCode .= $src;
        }
        public function LoadLibraries( $lib ) {
            if ( !empty( $lib ) ) {
                $lib = strtolower( $lib );
                $libs = explode( ',' , $lib );
                foreach ( $libs as $library ) {
                    $library = strtr( $library , '..' , '' );
                    $library = trim( $library );
                    if ( !empty( $library ) && $this->mLibraries[ $library ] ) {
                        // $debug = $this->mLibrariesDebug[ $library ];
                        $this->start();
                        include "js/libs/" . $library . ".php";
                        ?>BFC.LibraryLoaded('<?php
                        echo $library;
                        ?>');<?php
                        $this->end();
                    }
                }
            }
        }
        public function RegisterLibrary( $lib , $debug = false ) {
            // serves three purposes: 
            // first, only registered libraries should have the ability to be loaded
            // second, defines a variable with the library name ucfirst to be modified
            // by the library to store its functions
            // third, check if a library file actually exists (we could also do a server-side javascript parsing here, if possible)

            global $debug_version;

            $lib = strtolower( $lib );
            if ( $this->mLibraries[ $lib ] ) {
                bc_die( "Library $lib already registered" );
            }
            if ( !file_exists( "js/libs/$lib.php" ) ) {
                bc_die( "Library $lib doesn't exist" );
            }
            $this->mLibraries[ $lib ] = true;
            if ( $debug_version && $debug ) {
                $this->mLibrariesDebug[ $lib ] = true;
            }
        }
        public function GetRegisters() {
            $ret = '';
            
            foreach ( $this->mLibraries as $library => $isregistered ) {
                if ( $isregistered ) {
                    ?>var <?php
                    echo ucfirst( $library );
                    ?> = {};<?php
                }
            }
        }
        public function GetDebugLibraries() {
            $ret = array();

            foreach( $this->mLibrariesDebug as $library => $isdebug ) {
                if ( $isdebug )    {
                    $ret[] = $library;
                }
            }

            return $ret;
        }
        public function Depend( $elementid , $lib ) {
            if ( !$this->mLibraries[ $lib ] ) {
                bc_die( "Dependency on non-registered library $lib" );
            }
            $this->mDependencies[ $elementid ][ $lib ] = true;
        }
        public function GetDependencies() {
            $ret = '';

            foreach( $this->mDependencies as $elementid => $librariesarray ) {
                foreach( $librariesarray as $library => $depends ) {
                    if ( $depends ) {
                        $ret .= 'BFC.Depend("' . $elementid . '", "' . $library . '");';
                    }
                }
            }
            return $ret;
        }
    }

    function bfc_start() {
        // deprecated; use $bfc->start() instead
        global $bfc;
        
        $bfc->start();
    }
    
    function bfc_end() {
        // deprecated; use $bfc->end() instead
        global $bfc;

        $bfc->end();
    }
    
    function bfc_ob( $src ) {
        // called by PHP's output buffering system
        global $bfc;
        
        $bfc->ob( $src );
    }
?>