<?php
    /* 
    Module: Chat functions
    File: /modules/chat/chat.php
    Developer: Ro0t
    */
    include "modules/module.php";
    
    //Begin of class.
    class ChatRoom {
        // RoomInfo
        private $chan_id;
        private $chan_name;
        
        // Channel property's
        private $blog_id;
        private $chan_topic;
        private $chan_welcome;
        private $chan_noguest;
        
        // List of chatters in the channel
        private $chan_userlist;
        // The list of users who are owner of the channel
        private $chan_ownerlist;
        // The list of last messages
        private $chan_messagelist;

        public function __construct( $blog ) {
            //Class constructor (Code that will be loaded when the class is created)
            global $chatchannels, $chatusers, $chatmsg;
            
            //Check if we got a valid BlogID
            if ( ValidId( $blog ) ) {
                //I define the fields I want because I don't want them all
                $query = "SELECT
                            `chatchannel_id`,
                            `chatchannel_name`,
                            `chatchannel_topic`,
                            `chatchannel_welcome`,
                            `chatchannel_noguest`
                        FROM
                            `$chatchannels`
                        WHERE
                            `chatchannel_blogid` = '$blog'
                        LIMIT 1";
                        
                $result = bcsql_query( $query );
                
                //Check if the channel exists
                if ( bcsql_num_rows( $result ) == 0 ) {
                    bc_die( "Channel doesn't exist." );
                }
                
                $tobject = bcsql_fetch_array( $result );
                
                //Now return the results
                $this->chan_id = $tobject[ "chatchannel_id" ];
                $this->chan_name = $tobject[ "chatchannel_name" ];
                $this->blog_id = $blog;
                
                $this->chan_topic = $tobject[ "chatchannel_topic" ];
                $this->chan_welcome = $tobject[ "chatchannel_welcome" ];
                $this->chan_noguest= $tobject[ "chatchannel_noguest" ];
                
                //And as last, return the list of users currently in the channel
                $query = "SELECT
                            `chatuser_userid`,
                            `chatuser_isowner`
                        FROM
                            `$chatusers`
                        WHERE
                            `chatchannel_id` = '" . $channel_id . "'";
                
                $result = bcsql_query( $query );

                // And create a loop to store the userlist in our array
                while ( $tobject = bcsql_fetch_array( $result ) ) {
                    // And check if the user is an owner
                    if ( $tobject[ 'chatuser_isowner' ] = 'yes' ) {
                        // We got one :)
                        // Add him to the special list
                        $chan_ownerlist[] = $tobject[ 'chatuser_userid' ];
                    }
                    else {
                        // No owner, yust a chatter
                        $chan_userlist[] = $tobject[ 'chatuser_userid' ];
                    }
                }
                
                //And get the last 10-messages from the channel
                $query = "SELECT
                            `chatmsg_id`,
                            `chatmsg_msg`
                        FROM
                            `$chatmsg`
                        ORDER BY
                            `chatmsg_time` DESC
                        LIMIT 10";
                
                $result = bcsql_query( $query );
                $tobject = bcsql_fetch_array( $result );
                
                //And add them to the array
                $this->chanmessagelist[ $tobject[ 'chatmsg_id' ] ] = $tobject[ 'chatmsg_msg' ];
            }
            else {
                //Invalid BlogID
                bc_die( "Invalid BlogID" );
            }
        }

        //All functions for updating the database
        //------------------------------------------------
        private function get_permission() {
            //Check if the user has the permission to perform the wanted action
            //Get whether the user is the owner of the blog
            global $user;
            
            //Set result as false by default, if the SQL-handler isn't called.
            $result = false;
            
            // Simply loop through the whole array of users in the ownerlist
            // ( Remove one number from the count because we start from zero )
            for ( $count_loop = 0; $count_loop < count( $this->chan_ownerlist ); $count_loop++ ) {
                // Check if the ID is our UserID
                if ( $user->Id() == $this->chan_ownerlist[ $count_loop ] ) {
                    // We got it, valid :)
                    $result = true;
                    break;
                }
            }
            
            return $result;
        }

        public function update_welcome( $new_welcome ) {
            //Update the welcome message
            global $chatchannels;
            
            //Set result as false by default, if the SQL-handler isn't called.
            $result = false;
            
            // First check if the user has this permission
            if ( $this->get_permission( $this->chan_id ) ) {
                //We got writing permission for the welcome
                //Security
                $new_welcome = bcsql_escape( $new_welcome );
                //SQL
                $query = "UPDATE
                            `$chatchannels` (`chatchannel_welcome`)
                        VALUES
                            ('$new_welcome')
                        WHERE
                            `chatchannel_id` = '" . $this->chan_id . "'
                        LIMIT 1";
                        
                $result = bcsql_query( $query );

                //Check if success, if so update our variable
                if ($result) {
                    $this->chan_welcome = $new_welcome;
                }

                //Return our success/failure
                return $result;
            }
        }

        public function update_topic( $new_topic ) {
            //Update the topic
            global $chatchannels;
            
            //Set result as false by default, if the SQL-handler isn't called.
            $result = false;
            
            // First check if the user has this permission
            if ( $this->get_permission( $this->chan_id ) ) {
                //Security
                $new_topic = bcsql_escape( $new_topic );
                //We got writing permission for the welcome
                $query = "UPDATE
                            `$chatchannels` (`chatchannel_topic`)
                        VALUES
                            ('$new_topic')
                        WHERE
                            `chatchannel_id` = '" . $this->chan_id . "'
                        LIMIT 1";
                
                $result = bcsql_query( $query );

                //Check if success, if so update our variable
                if ( $result ) {
                    $this->chan_topic = $new_topic;
                }

                //Return our success/failure
                return $result;
            }
        }

        public function update_noguest( $choice ) {
            //Update whether unregisterd users can join the room
            global $chatchannels;
            
            // First check if the user has this permission
            if ( $this->get_permission( $this->chan_id ) ) {
                //We got writing permission for the welcome
                
                //Set result as false by default, if the SQL-handler isn't called.
                $result = false;
                
                //Security check
                if ( is_bool( $choice ) ) {
                    $query = "UPDATE
                                `$chatchannels` (`chatchannel_noguest`)
                            VALUES
                                ('$choice')
                            WHERE
                                `chatchannel_id` = '" . $this->chan_id . "'
                            LIMIT 1";
                    
                    $result = bcsql_query( $query );

                    //Check if success, if so update our variable
                    if ( $result ) {
                        $this->chan_noguest = $choice;
                    }
                }

                //Return our success/failure
                return $result;
            }
            else {
                return false;
            }
        }
        //------------------------------------------------

        //Adding data to the chatroome.
        //------------------------------------------------
        public function add_message( $message, $userid ) {
            global $chatmsg;
            
            //This code is reponsible for adding a message to the channel
            //Security
            $message = bcsql_escape( $message );
            
            // Get the current date
            $nowdate = NowDate();
            $query = "INSERT INTO
                        `$chatmsg`
                            (`chatmsg_chanid`,
                            `chatmsg_time`,
                            `chatmsg_userid`,
                            `chatmsg_msg`)
                    VALUES
                        ('" . $this->chan_id . "',
                        '$nowdate',
                        '$userid',
                        '$message')";
            
            $result = bcsql_query( $query );

            //Check if the message is succesfully stored
            if ( $result ) {
                //The function succeeded, now add it to an array
                //in this class.
                
                //Use the key from the mySQL-table
                $this->chan_messagelist[ bcsql_insert_id() ] = $message;
                //Return success
            }
            
            return $result;
        }
        //------------------------------------------------
        
        //All functions to retrieve information
        public function get_welcome() {
            //Return the welcome message
            return $this->chan_welcome;
        }
        
        public function get_topic() {
            //Return the topic
            return $this->chan_topic;
        }
        
        public function get_noguest() {
            //Return of guests are allowed.
            return $this->chan_noguest;
        }
        
        public function get_userlist() {
            //Return the userlist in an array
            return $this->chan_userlist;
        }
        
        public function get_ownerlist() {
            //Return the ownerlist in an array
            return $this->chan_ownerlist;
        }
        
        public function get_message( $message_id ) {
            //Return the message a user requests.
            return $this->chan_messagelist[ $message_id ];
        }
        //------------------------------------------------
    }
    //End of Class.
    //------------------------------------------------

?>