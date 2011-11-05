<?php
    final class Wind_gmail_easy extends Wind_gmail { 
        private $stdout;
        private $stderr;
        private $return_value;
        private $gmail_perlfile = '/var/www/vhosts/blogcube.net/httpdocs/beta/internal/wind_gmail.pl'; //'/home/mkan/gmail.pl';
        public $ambiguities = 0;
        
        public function Wind_gmail_easy ( $user, $pass ) {
            /* Pipe */
            $descriptorspec = array(
               0 => array("pipe", "r"),  // stdin is a pipe that the child will read from
               1 => array("pipe", "w"),  // stdout is a pipe that the child will write to
               2 => array("pipe", "w") // stderr is a pipe that the child will write to
            );
            
            $process = proc_open('perl -w ' . $this->gmail_perlfile, $descriptorspec, $pipes);
            
            if (!is_resource($process)) {
                bc_die ("Can't execute " . $this->gmail_perlfile ."!");
            }
    
            fwrite($pipes[0], "$user\n$pass\n");
            fclose($pipes[0]);        // 0 => stdin
            
            $allcontacts = $this->stdout = stream_get_contents($pipes[1]);
            fclose($pipes[1]);        // 1 => stdout
    
            $errors = $this->stderr = stream_get_contents($pipes[2]);
            fclose($pipes[2]);        // 2 => stderr
            
            // It is important that you close any pipes before calling proc_close in order to avoid a deadlock
            $return_value = $this->return_value = proc_close($process);
            
            /* Parsing */
            if ( $return_value == 66 ) { // either login failure or changes in gmail's website
                bc_die ("<pre>Dear $user, command returned with exit-code $return_value.\nSTDERR:\n$errors\nSTDOUT:\n</pre>$allcontacts");
            }
            if ( $return_value != 0 ) {    // unexpected (execution timeout?)
                if ( $return_value != -1 ) { 
                    // i know this sucks, sorry, but it sometimes comes out with -1, always with proper execution
                    bc_die ("<pre>Dear $user, command unexpectedly terminated with exit-code $return_value.\nSTDERR:\n$errors\nSTDOUT:\n</pre>$allcontacts");
                }
            }
            
            $pieces = explode('<tr>', $allcontacts);
            foreach ( $pieces as $piece ) {
                $columns = explode('<td', $piece);
                
                if ( preg_match ( '/<b>(.*?)<\/b>/s' , $columns[2] , $matches ) ) {
                    $name = $matches[1];
                }
                
                if ( preg_match_all ( '/([0-9A-Za-z_+=.-]+@[0-9A-Za-z_.=-]{2,})/s' , $columns[3] , $matches , PREG_PATTERN_ORDER ) ) {
                    foreach ( $matches[1] as $val ) {
                        if ( !( $this->Add( $val, $name ) ) ) {
                            $this->ambiguities++;
                        }
                    }
                }
            }
        }
    }
?>