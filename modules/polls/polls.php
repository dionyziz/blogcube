<?php

    /* 
    Module: Polls
    File: /modules/polls/polls.php
    Developer: Indy
    */
    
    include 'modules/module.php';
    
    
    function CreatePoll( $PollName , $PollClosingDate ) {
    
        global $user;
        global $polls;
    
        $PollUserId = $user->Id();
        $PollIp = UserIp();
        $PollDate = NowDate();
        $PollName = bcsql_escape( $PollName );
        $PollClosingDate = bcsql_escape( $PollClosingDate );
        $query = "INSERT INTO `$polls` ( `poll_id` , `poll_userid` , `poll_name` , `poll_ip` , `poll_date` , `poll_closingdate` ) VALUES ( '' , '$PollUserId' , '$PollName' , '$PollIp' , '$PollDate' , '$PollClosingDate' );";
        bcsql_query( $query );
    
    }
    
    class Poll {
        
        private $mId;
        private $mUserId;
        private $mName;
        private $mDate;
        private $mIp;
        private $mClosingDate;
        private $mPollOptionsRetrieved;
        
        public function Poll( $Constructor ) {
        
            global $polls;
        
            if ( !is_array( $Constructor ) ) {
                $Id = $Constructor;
                $Query = "SELECT * FROM `$polls` WHERE `poll_id` = '$Id' LIMIT 1;";
                $Sqlr = bcsql_query( $Query );
                $PollDetails = bcsql_fetch_array( $Sqlr );
            }
            else {
                $PollDetails = $Constructor;
            }
            $this->mId = $PollDetails[ 'poll_id' ];
            $this->mUserId = $PollDetails[ 'poll_userid' ];
            $this->mName = $PollDetails[ 'poll_name' ];
            $this->mDate = $PollDetails[ 'poll_date' ];
            $this->mIp = $PollDetails[ 'poll_ip' ];
            $this->mClosingDate = $PollDetails[ 'poll_closingdate' ];
            $this->mPollOptionsRetrieved = false;
            
        }
        
        public function DeletePoll() {
        
            global $polls;
            global $polloptions;
            global $pollvotes;
            
            $Id = $this->mId;
            $Query = "DELETE FROM `$polls` WHERE `poll_id` = '$Id' LIMIT 1;";
            bcsql_query( $Query );
            $Query = "DELETE FROM `$polloptions` WHERE `poption_pollid` = '$Id';";
            bcsql_query( $Query );
            $Query = "
                DELETE FROM 
                    `$pollvotes`
                WHERE
                    `pv_id` IN (
                            SELECT 
                                `pv_id`
                            FROM
                                `$pollvotes` , `$polloptions`
                            WHERE
                                `pv_polloptionid` = `poption_id` AND
                                `poption_pollid` = '$Id'
                    );";
            bcsql_query( $Query );
            
        }
        
        public function RenamePoll( $NewName ) {
        
            global $polls;
        
            $this->mName = $NewName;
            $NewName = bcsql_escape( $NewName );
            $Id = $this->mId;
            $Query = "UPDATE `$polls` SET `poll_name` = '$NewName' WHERE `poll_id` = '$Id' LIMIT 1;";
            bcsql_query( $Query );
            
        }
        
        public function RetrievePollOption() {
        
            global $polloptions;
            
            if ( !$this->mPollOptionsRetrieved ) {
                $Id = $this->mId;
                $Query = "SELECT * FROM `$polloptions` WHERE `poption_pollid` = '$Id';";
                $Sqlr = bcsql_query( $Query );
                $this->mPollOptionsRetrieved = true;
            }
            if ( $Row = bcsql_fetch_array( $Sqlr ) )
                return New PollOption( $Row );
            return $this->mPollOptionRetrieved = false;
            
        }
        
        public function IsActive() {
            
            global $polls;
            
            $ClosingDate = $this->mClosingDate;
            return DateDiff( $ClosingDate ) < 0 ;
            
        }
        
        public function CheckResults() {
        
            global $polloptions;
            global $pollvotes;
            
            // TODO
        
        }
        
        public function Id() {
            return $this->mId;
        }
        
        public function UserId() {
            return $this->mUserId;
        }
        
        public function Name() {
            return $this->mName;
        }
        
        public function Date() {
            return $this->mDate;
        }
        
        public function Ip() {
            return $this->mIp;
        }
        
        public function ClosingDate() {
            return $this->mClosingDate;
        }
        
        public function PollOptionsRetrieved() {
            return $this->mPollOptionsRetrieved;
        }
        
    }
    
    function CreatePollOption( $PollId , $Text ) {
        
        global $polloptions;
        
        $Text = bcsql_escape( $Text );
        $Ip = UserIp();
        $Date = NowDate();
        $Query = "INSERT INTO `$polloptions` ( `poption_id` , `poption_pollid` , `poption_text` , `poption_ip` , `poption_date` ) VALUES ( '' , '$PollId' , '$Text' , '$Ip' , '$Date' );";
        bcsql_query( $Query );
        
    }
    
    class PollOption {
    
        private $mId;
        private $mPollId;
        private $mText;
        private $mIp;
        private $mDate;
        
        public function PollOption( $Constructor ) {
        
            global $polloptions;
        
            if ( !is_array( $Constructor ) ) {
                $Id = $Constructor;
                $Query = "SELECT * FROM `$polloptions` WHERE `poption_id` = '$Id' LIMIT 1;";
                $Sqlr = bcsql_query( $Query );
                $poption_details = bcsql_fetch_array( $Sqlr );
            }
            else {
                $poption_details = $constructor;
            }
            $this->mId = $poption_details[ "poption_id" ];
            $this->mPollId = $poption_details[ "poption_pollid" ];
            $this->mText = $poption_details[ "poption_text" ];
            $this->mIp = $poption_details[ "poption_ip" ];
            $this->mDate = $poption_details[ "poption_date" ];
            
        }
        
        public function ChangeText( $NewText ) {
        
            global $polloptions;
            
            $Id = $this->mId;
            $this->mText = $NewText;
            $NewText = bcsql_escape( $NewText );
            $query = "UPDATE `$polloptions` SET `poption_text` = '$NewText' WHERE `poption_id` = '$Id' LIMIT 1;";
            bcsql_query( $query );
        
        }
        
        public function DeletePollOption() {
        
            global $polloptions;
            global $pollvotes;
            
            $Id = $this->mId;
            $query = "DELETE FROM `$polloptions` WHERE `poption_id` = '$Id' LIMIT 1;";
            bcsql_query( $query );
            $query = "DELETE FROM `$pollvotes` WHERE `pv_polloptionid` = '$Id' LIMIT 1;";
            bcsql_query( $query );
            
        }
        
        public function Vote( $UserId ) {
        
            global $pollvotes;
            
            $Id = $this->mId;
            $query = "SELECT COUNT(*) AS votescount FROM `$pollvotes` WHERE `pv_userid` = '$UserId' AND `pv_polloptionid` = '$Id';";
            $sqlr = bcsql_query( $query );
            $votesarray = bcsql_fetch_array( $sqlr );
            if ( $votesarray[ "votescount" ] > 0 ) {
                return false;
            }
            $Id = $this->mId;
            $Date = NowDate();
            $Ip = UserIp();
            $query = "INSERT INTO `$pollvotes` ( `pv_id` , `pv_userid` , `pv_polloptionid` , `pv_date` , `pv_ip` ) VALUES ( '' , '$UserId' , '$Id' , '$Date' , '$Ip' );";
            bcsql_query( $query );
            return true;
        
        }
        
        public function Id() {
            return $this->mId;
        }
        
        public function PollId() {
            return $this->mPollId;
        }
        
        public function Text() {
            return $this->mText;
        }
        
        public function Ip() {
            return $this->mIp;
        }
        
        public function Date() {
            return $this->mDate;
        }
    
    }
    
?>
