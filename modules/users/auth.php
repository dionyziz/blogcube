<?php
    // authentication script
    // function auth() checks if the username/password passed
    // correspond to a user in the database
    // this function is called on startup, so you can assume $user has been set
    
    function auth() {
        global $s_username;
        global $s_password;
        global $users;
        global $user;
        global $anonymous;
        global $readonly;
        
        $errortype = 0;
        // if we're in readonly mode, we can't use the database so just don't allow any logins
        if ( $readonly ) {
            $login = false;
        }
        else {
            // s_username and s_password are session variables which contain
            // the username and the md5 hash of the password the user typed respectively
            // these should have already been escaped when the user submitted the login form
            // and the password should also have been md5-hashed.
            if( isset( $_SESSION[ "s_username" ] ) && isset( $_SESSION[ "s_password" ] ) ) {
                $s_username = $_SESSION[ "s_username" ];
                $s_password = $_SESSION[ "s_password" ];
                
            }
            else {
                $s_username = $s_password = "";
            }
            
            if ( $s_username && $s_password ) {
                // the user has tried to login using a username/password combination
                // check if that user exists in the database
                // no worries for the user having passed s_username and s_password using GET or POST
                // since we get it directly from $_SESSION
                // removed:  AND    `user_password`='$s_password'
                $sql = "SELECT
                        *
                    FROM
                        `$users`
                    WHERE
                        `user_username`='$s_username'
                    LIMIT 1;";
                $sqlr = bcsql_query( $sql );
                // there's a user
                if ( bcsql_num_rows( $sqlr ) ) {
                    // get user info
                    $sqluser = bcsql_fetch_array( $sqlr );
                    // construct user class for global variable $user
                    $user = New User( $sqluser );
                            //    check turing
                        if (isset($_SESSION[ "s_turingnumber" ]) && $_SESSION[ "s_turingnumber" ] >= 0 ) {
                            $s_turingnumber = $_SESSION[ "s_turingnumber" ];
                        } else {
                            $s_turingnumber = -2;
                        }
                        if ( isset( $_SESSION[ "turingvalue" ] ) ) {
                            $turingvalue = $_SESSION[ "turingvalue" ];
                            unset( $_SESSION[ "turingvalue" ] ); // no using the same turingvalue more than once  
                        } else {
                            $turingvalue = -1;
                        }
                        
                        if ( $user->FailedLogins() < 3 || ( (int)$s_turingnumber == (int)$turingvalue ) ) {
                            
                            // check pass after checking turing
                            if ( $user->PasswordHash() == $s_password ){
                                // we want to store the date at which the user was last active
                                // do that now
                                $nowdate = NowDate(); // GMT
                                $sql = "UPDATE
                                            `$users`
                                        SET
                                            `user_lastactive`='$nowdate'
                                        WHERE
                                            `user_id`='" . $user->Id() . "'
                                        LIMIT 1;";
                                bcsql_query( $sql );
                                // we're logged in!
                                unset( $_SESSION[ "turingenabled" ] );
                                if ( $user->FailedLogins() > 0){
                                    $user->RemoveFailedLogins();
                                }
                                
                                $login = true;
                                            
                            } else {
                                // wrong pass
                                $user->AddFailedLogin();
                                if ( $user->FailedLogins() >= 3){
                                    $_SESSION[ "turingenabled" ]= true;
                                } else {
                                    unset( $_SESSION[ "turingenabled" ] );
                                }
                                $login = false;
                                $errortype = 2; // pass 
                            }
                    } else {
                            //wrong turing
                            $_SESSION[ "turingenabled" ]= true;
                            $login = false;
                            $errortype = 3; // turing
                        }
                }
                else {
                    // no user with such username
                    $login = false;
                    $errortype = 1; // user
                    //User::AddFailedLogin( $s_username );
                }
            }
            else {
                // the user hasn't typed a username and/or password
                $login = false;
                if (!$s_username) {
                    $errortype = 1; // user or both user and pass 
                } else {
                    $errortype = 2; // pass
                }
                
            }
        }
        if ( !$login ) {
            // invalid username/password combination
            $s_username = "";
            $s_password = "";
            // construct global $user object with anonymous (default) user info
            $user = New User( Array() );
        }
        // and let the rest of the script know that the user hasn't logged in
        $anonymous = !$login;
        return $errortype;
    }
?>