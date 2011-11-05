<?php
    /*
        Water; BlogCube Debugging Module
        Developer: dionyziz
    */
    
    function Water_HandleError( $errno , $errstr , $errfile , $errline , $errcontext ) {
        global $water;

        $water->HandleError( $errno , $errstr , $errfile , $errline , $errcontext );
    }
    function debug_notice( $str ) {
        global $bfc;
        
        if ( isset( $bfc ) ) {
            $bfc->DebugNotice( $str );
        }
    }
    function bc_die( $error = "" ) {
        global $water;
        
        $water->ThrowException( $error );
    }
    function bc_assert( $condition ) {
        global $water;
        
        $water->Assert( $condition );
    }
    class Water {
        public function ThrowException( $message ) {
            $this->HandleException(New Exception( $message ));
        }
        public function HandleError( $errno , $errstr , $errfile , $errline , $errcontext ) {
            $chop = $this->chopfile( $errfile );
            switch ( $errno ) {
                case E_USER_ERROR:
                    $error = 'Fatal: ' . $errstr;
                    $this->FatalError(
                        $error
                    );
                case E_USER_WARNING:
                    $error = 'Warning: ' . $errstr;
                    $this->FatalError(
                        $error
                    );
                    break;
                case E_USER_NOTICE: // ignore
            }
        }
        public function HandleException( $exception ) {
            // since there has been no try/catch pair, this is a fatal exception
            $this->FatalError( $exception->getMessage() );
        }
        public function Assert( $expression ) {
            if ( !$expression ) {
                $this->ThrowException( 'Assertion failed' );
            }
        }
        public function LogError( $message , $time , $userid , $username , $userip , $request ) {
            global $errorlogs;

            if ( function_exists( 'bcsql_escape_string' ) ) {
                $request = bcsql_escape_string( $request );
                $message = bcsql_escape_string( $message );
            }
            else {
                $request = mysql_real_escape_string( $request );
                $message = mysql_real_escape_string( $message );
            }

            $sql = "INSERT INTO `$errorlogs`
                    ( `err_id` , `err_userid` , `err_description` , `err_ip` , `err_timestamp` , `err_page` )
                    VALUES( '' , '$userid' , '$message' , '$userip' , '$time' , '$request' );";
            if ( mysql_query( $sql ) === false ) {
                return false;
            }
            return mysql_insert_id();
        }
        public function MailError( $message , $time , $userid , $username , $userip , $request ) {
            global $version;

            $text = "Developers, \n"
             ."A bc_die() call was made today, on $time, while requesting the page $reqest\n"
             ."from the stable version of BlogCube ($version).\n";

            if( $userid ) {
                $text .= "The logged in user when this error occured was " . $username . " (" . $userid . ")\n";
            }
            else {
                $text .= "An anonymous user was using the system when this error occured.\n";
            }
            $text .= "The IP of the user was $userip.
            An error report has been formed and was saved as a record with id " . mysql_insert_id() . ".

            The reported error text was:
            
            \"$message\"
            
            Please look into the matter as soon as possible.
            
            Sincerely,
            The bc_die() Function,
            A Proud Member of Water.";

            return mail( "leaders@blogcube.net" , "bc_die() Error - $time" , $message );
        }
        public function FatalError( $message ) {
            global $user;
            global $debug_version;
            
            if ( function_exists( 'UserIp' ) ) {
                $userip = UserIp();
            }
            else {
                $userip = '(unknown)';
            }
            if ( function_exists( 'NowDate' ) ) {
                $nowdate = NowDate();
            }
            else {
                $nowdate = '0000-00-00 00:00:00';
            }

            $page = $_SERVER["REQUEST_URI"];

            if ( isset( $user ) && $user->Id() ) {
                $userid = $user->Id();
                $username = $user->Username();
            }
            else {
                $userid = 0;
                $username = '';
            }

            $logok = $this->LogError( $message , $nowdate , $userid , $username , $userip , $page );

            if( !$debug_version ) {
                $mailok = $this->MailError( $message , $nowdate , $userid , $username , $userip , $page );
            }
            else {
                $mailok = false;
            }
    
            $thisisabug = true;
            $watertrace = $this->Trace();
            $error = $message;

            $level = ob_get_level();
            for ( $i = 0 ; $i < $level ; ++$i ) {
                ob_end_clean();
            }

            include "elements/blogging/bugreporting/waterbug.php";
    
            exit();
        }
        public function Trace() {
            ob_start();
    
            ?><div id="bc_die_watertrace"><?php
            $this->callstack_dump_lastword();
            ?></div></div><?php
    
            return ob_get_clean();
        }
        private function get_all_functions() {
            static $memo;
            
            if ( !isset( $memo ) ) {
                $memo = get_defined_functions();
            }
            return $memo;
        }
        private function get_php_functions() {
            static $memo;
    
            if ( !isset( $memo ) ) {
                $allfunctions = $this->get_all_functions();
                $phpfunctions = $allfunctions['internal'];
                $phpfunctions[] = 'include';
                $phpfunctions[] = 'include_once';
                $phpfunctions[] = 'require';
                $phpfunctions[] = 'require_once';

                foreach ($phpfunctions as $function) {
                    $map[ $function ] = true;
                }
                $memo = $map;
            }
            
            return $memo;
        }
        private function callstack_lastword() {
            $backtrace = debug_backtrace();
            
            $i = count( $backtrace ) - 1;
            foreach ( $backtrace as $call ) {
                if ( strlen( $call[ 'file' ] ) < strlen('/var/www/vhosts/blogcube.net/httpdocs/') ) {
                    $lastword[ $i ][ 'revision' ] = phpversion();
                    $lastword[ $i ][ 'file' ] = '(internal blogcube system)';
                    $lastword[ $i ][ 'line' ] = '-';
                }
                else {
                    $chop = $this->chopfile( $call[ 'file' ] );
                    $lastword[ $i ][ 'revision' ] = $chop[ 'revision' ];
                    $lastword[ $i ][ 'file' ] = $chop[ 'file' ];
                    $lastword[ $i ][ 'line' ] = $call[ 'line' ];
                }
                $lastword[ $i ][ 'class' ] = $call[ 'class' ];
                $lastword[ $i ][ 'function' ] = $call[ 'function' ];
                $lastword[ $i ][ 'depth' ] = 0;
                $lastword[ $i ][ 'args' ] = $call[ 'args' ];
                $lastword[ $i ][ 'calltype' ] = $call[ 'type' ];
                --$i;
            }
            
            return $lastword;
        }
        private function callstack_html( $callstack ) {
            $functions = $this->get_php_functions();
    
            ?><div class="watertrace"><img src="images/silk/bug.png" alt="bug"> WaterTrace<br /><table class="callstack"><tr><td class="title">revision</td><td class="title">function</td><td class="title">source</td><td class="title">line</td></tr><?php
            for ( $i = 0 ; $i < count( $callstack ) ; ++$i ) {
                if ( isset( $callstack[ $i ] ) ) {
                    $info = $callstack[ $i ];
                    ?><tr><td class="revision"><?php
                    if ( isset( $info[ 'revision' ] ) ) {
                        echo $info[ 'revision' ];
                    }
                    ?></td><td class="function"><?php
                    if ( isset( $info[ 'depth' ] ) ) {
                        echo str_repeat( '&nbsp;' , $info[ 'depth' ] * 2 );
                    }
                    if ( !empty( $info[ 'class' ] ) ) {
                        echo $info[ 'class' ];
                        switch ( $call[ 'type' ] ) {
                            case '::':
                                ?>::<?php
                                break;
                            case '->':
                            default:
                                ?>-&gt;<?php
                                break;
                        }
                    }
                    if ( isset( $info[ 'function' ] ) ) {
                        $phpfunction = isset( $functions[ $info[ 'function' ] ] );
                        if ( $phpfunction ) {
                            ?><a href="http://www.php.net/<?php
                            echo $info[ 'function' ];
                            ?>"><?php
                        }
                        echo $info[ 'function' ];
                        if ( $phpfunction ) {
                            ?></a><?php
                        }
                    }
                    ?>(<?php
                    if ( isset( $info[ 'args' ] ) ) {
                        $j = 0;
                        $numargs = count( $info[ 'args' ] );
                        foreach ( $info[ 'args' ] as $arg ) {
                            ?> <?php
                            if ( is_object( $arg ) ) {
                                ?>[object]<?php
                            }
                            else if ( is_null( $arg ) ) {
                                ?>[null]<?php
                            }
                            else if ( is_resource( $arg ) ) {
                                ?>[resource: <?php
                                echo get_resource_type( $arg );
                                ?>]<?php
                            }
                            else if ( is_array( $arg ) ) {
                                ?>[array]<?php
                            }
                            else if ( is_scalar( $arg ) ) {
                                if ( is_bool( $arg ) ) {
                                    if ( $arg ) {
                                        ?>[true]<?php
                                    }
                                    else {
                                        ?>[false]<?php
                                    }
                                }
                                switch ( $info[ 'function' ] ) {
                                    case 'include':
                                    case 'include_once':
                                    case 'require':
                                    case 'require_once':
                                        $chop = $this->chopfile( $arg );
                                        img('images/silk/page_white_go.png', 'include', 'include', 16, 16);
                                        ?> <?php
                                        echo $chop[ 'file' ];
                                        break;
                                    default:
                                        if ( is_string( $arg ) ) {
                                            ?>"<?php
                                        }
                                        $argshow = substr( $arg , 0 , 30 );
                                        echo $argshow;
                                        if ( strlen( $arg ) > strlen( $argshow ) ) {
                                            ?>...<?php
                                        }
                                        if ( is_string( $arg ) ) {
                                            ?>"<?php
                                        }
                                }
                            }
                            ?> <?php
                            ++$j;
                            if ( $j != $numargs ) {
                                ?>,<?php
                            }
                        }
                    }
                    ?>)</td><td class="file"><?php
                    if ( isset( $info[ 'file' ] ) ) {
                        echo $info[ 'file' ];
                    }
                    ?></td><td class="line"><?php
                    if ( isset( $info[ 'line' ] ) ) {
                        echo $info[ 'line' ];
                    }
                    else {
                        ?>-<?php
                    }
                    ?></td></tr><?php
                }
            }
            ?></table></div><?php
        }
        private function callstack_dump_lastword() {
            $this->callstack_html( $this->callstack_lastword() );
        }
        private function chopfile( $filename ) {
            static $chopfiles; // memoize
            
            if ( !isset( $chopfiles[ $filename ] ) ) {
                $filename = substr( $filename , strlen( '/var/www/vhosts/blogcube.net/httpdocs/' ) );
                $filec = explode( '/' , $filename , 2 );
                $revision = $filec[ 0 ];
                $file = $filec[ 1 ];
                if ( substr( $file , 0 , strlen( 'elements/' ) ) == 'elements/' ) {
                    $file = substr( $file , strlen( 'elements/' ) );
                    $icon = 'images/silk/monitor.png';
                    $icontitle = 'Element';
                }
                else if ( substr( $file , 0 , strlen( 'modules/' ) ) == 'modules/' ) {
                    $file = substr( $file , strlen( 'modules/' ) );
                    $icon = 'images/silk/bricks.png';
                    $icontitle = 'Module';
                }
                if ( strtolower( substr( $file , -4 ) ) == '.php' ) {
                    $file = substr( $file , 0 , -4 );
                }
                
                $ret = array(
                    'revision' => $revision,
                    'file' => $file
                );
                if ( isset( $icon ) ) {
                    $ret[ 'file' ] = '<img src="' . $icon . '" alt="' . $icontitle . '" title="' . $icontitle . '" /> ' . $ret[ 'file' ];
                }
                $chopfiles[ $filename ] = $ret;
            }
            return $chopfiles[ $filename ];
        }
        public function __sleep() {
        }
    }

    $water = New Water();
    set_exception_handler( array( $water , 'HandleException' ) );
    set_error_handler( 'Water_HandleError' , E_ALL );
?>