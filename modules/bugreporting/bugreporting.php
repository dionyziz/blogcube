<?php

    /* 
    Module: BugReporting
    File: /modules/bugreporting/bugreporting.php
    Developer: Indy
    */
    
    include "modules/module.php";
    
    function ReportBug( $BugName , $BugDescription , $BugOs , $BugUserAgent , $BugOsVersion, $UserBrowser, $UserBrowserVersion ) {
    
        global $user;
        global $bugs;
        
        $UserIp = UserIp();
        $UserId = $user->Id();
        $BugName = bcsql_escape( $BugName );
        $BugDescription = bcsql_escape( $BugDescription );
        $BugOs = bcsql_escape( $BugOs );
        $UserBrowser = bcsql_escape( $UserBrowser );
        $UserBrowserVersion = bcsql_escape( $UserBrowserVersion );
        $BugUserAgent = bcsql_escape( $BugUserAgent );
        $BugOsVersion = bcsql_escape( $BugOsVersion );
        $NowDate = NowDate();
        $query = "INSERT INTO `$bugs` (  `bug_id` , `bug_name` , `bug_description` , `bug_os` , `bug_userip` , `bug_userid` , `bug_userbrowser` , `bug_userbrowserversion` , `bug_useragent` , `bug_osversion` , `bug_date` , `bug_fixedby` , `bug_status` , `bug_assigned` ) VALUES ( '' , '$BugName' , '$BugDescription' , '$BugOs' , '$UserIp' , '$UserId' , '$UserBrowser' , '$UserBrowserVersion' , '$BugUserAgent' , '$BugOsVersion' , '$NowDate' , 0 , 'new' , '0' );";    
        bcsql_query( $query );
        return bcsql_insert_id();
        
    }
    
    class Bug {
    
        private $mName;
        private $mDescription;
        private $mOs;
        private $mUserIp;
        private $mUserBrowser;
        private $mUserBrowserVersion;
        private $mUserId;
        private $mId;
        private $mUserAgent;
        private $mOsVersion;
        private $mNowDate;
        private $mFixedBy;
        private $mStatus;
        private $mAssigned;
        
        public function Bug( $construct ) {

            global $bugs; 
            
            if( !is_array( $construct ) ) {
                $BugId = $construct;
                $query = "SELECT * FROM `$bugs` WHERE `bug_id` = '$BugId' LIMIT 1;";
                $sqlr = bcsql_query( $query );
                $bugdetails = bcsql_fetch_array( $sqlr );
            }
            else
                $bugdetails = $construct;
            $this->mName = $bugdetails[ "bug_name" ];
            $this->mDescription = $bugdetails[ "bug_description" ];
            $this->mOs = $bugdetails[ "bug_os" ];
            $this->mUserIp = $bugdetails[ "bug_userip" ];
            $this->mUserBrowser = $bugdetails[ "bug_userbrowser" ];
            $this->mUserBrowserVersion = $bugdetails[ "bug_userbrowserversion" ];
            $this->mUserId = $bugdetails[ "bug_userid" ];
            $this->mUserAgent = $bugdetails[ "bug_useragent" ];
            $this->mOsVersion = $bugdetails[ "bug_osversion" ];
            $this->mId = $bugdetails[ "bug_id" ];
            $this->mNowDate = $bugdetails[ "bug_date" ];
            $this->mFixedBy = $bugdetails[ "bug_fixedby" ];
            $this->mStatus = $bugdetails[ "bug_status" ];
            $this->mAssigned = $bugdetails[ "bug_assigned" ];
            
        }
        
        public function FixBug( $UserId ) {
        
            global $bugs;
            
            $Id = $this->mId;
            $query = "UPDATE `$bugs` SET `bug_fixedby` = '$UserId' WHERE `bug_id` = '$Id' LIMIT 1;";
            bcsql_query( $query );
            $this->mFixedBy = $UserId;
            
        }
        
        public function SetStatus( $Status ) {
            
            global $bugs;
            
            $this->mStatus = $Status;
            $Id = $this->mId;
            $Query = "UPDATE `$bugs` SET `bug_status` = '$Status' WHERE `bug_id` = '$Id' LIMIT 1;";
            bcsql_query( $Query );
        }
        
        public function Assign( $UserId ) {
            
            global $bugs;
            
            bc_assert( ValidId( $UserId ) );
            $this->mAssigned = $UserId;
            $Id = $this->mId;
            $Query = "UPDATE `$bugs` SET `bug_assigned` = '$UserId' WHERE `bug_id` = '$Id' LIMIT 1;";
            bcsql_query( $Query );
        }
        
        public function Name() {
            return $this->mName;
        }

        public function Description() {
            return $this->mDescription;
        }
        
        public function Os() {
            return $this->mOs;
        }
        
        public function UserIp() {
            return $this->mUserIp;
        }
        
        public function UserBrowser() {
            return $this->mUserBrowser;
        }

        public function UserBrowserVersion() {
            return $this->mUserBrowserVersion;
        }

        public function UserId() {
            return $this->mUserId;
        }

        public function UserAgent() {
            return $this->mUserAgent;
        }
        
        public function OsVersion() {
            return $this->mOsVersion;
        }
        
        public function NowDate() {
            return $this->mNowDate;
        }
        
        public function Id() {
            return $this->mId;
        }
        
        public function FixedBy() {
            return $this->mFixedBy;
        }
        
        public function Status() {
            return $this->mStatus;
        }
        
        public function Assigned() {
            return $this->mAssigned;
        }
    }
    
    function BugSearch( $BugId = 0 , $BugName = "" , $BugDescription = "" , $BugOs = "" , $BugUserAgent = "" , $BugOsVersion = "" , $BugUserId = 0 , $BugUserIp = "" , $BugUserBrowser = "" , $BugUserBrowserVersion = "" , $BugFromDate = "", $BugUntilDate = "" , $BugFixedBy = -1 , $Offset = 0 , $Length = 0 ) {
    
        global $bugs;
        
        $BugName = bcsql_escape( $BugName );
        $BugOs = bcsql_escape( $BugOs );
        $BugUserAgent = bcsql_escape( $BugUserAgent );
        $BugOsVersion = bcsql_escape( $BugOsVersion );
        $BugUserBrowser = bcsql_escape( $BugUserBrowser );
        $BugUserBrowserVersion = bcsql_escape( $BugUserBrowserVersion );
        $BugDescription = bcsql_escape( $BugDescription );
        $where_condition = "";
        if ( $BugId != 0 )
            $where_condition .= " AND `bug_id` = '$BugId'";
        if ( $BugName != "" )
            $where_condition .= " AND `bug_name` LIKE '%$BugName%'";
        if ( $BugDescription != "" )
            $where_condition .= " AND `bug_description` LIKE '%$BugDescription%'";
        if ( $BugOs != "" )
            $where_condition .= " AND `bug_os` = '$BugOs'";
        if ( $BugUserAgent != "" )
            $where_condition .= " AND `bug_useragent` = '$BugUserAgent'";
        if ( $BugOsVersion != "" )
            $where_condition .= " AND `bug_osversion` = '$BugOsVersion'";
        if ( $BugUserId != 0 )
            $where_condition .= " AND `bug_userid` = '$BugUserId'";
        if ( $BugUserIp != "" )
            $where_condition .= " AND `bug_userip` = '$BugUserIp'";
        if ( $BugUserBrowser != "" )
            $where_condition .= " AND `bug_userbrowser` = '$BugUserBrowser'";
        if ( $BugUserBrowserVersion != "" )
            $where_condition .= " AND `bug_userbrowserversion` = '$BugUserBrowserVersion'";
        if ( $BugFixedBy != -1 )
            $where_condition .= " AND `bug_fixedby` = '$BugFixedBy'";
        if ( $BugFromDate != "" )
            $where_condition .= " AND `bug_date` > '$BugFromDate'";
        if ( $BugUntilDate != "" )
            $where_condition .= " AND `bug_date` <= '$BugUntilDate'";
        if ( $where_condition == "" )
            $query = "SELECT * FROM `$bugs` ORDER BY `bug_date` DESC";
        else {
            $where_condition = substr( $where_condition , 5 , strlen( $where_condition ) - 5 );
            $query = "SELECT * FROM `$bugs` WHERE $where_condition ORDER BY `bug_date` DESC";
        }
        if ( $Length != 0 )
            $query .= " LIMIT $Offset , $Length";
        $query .= ";";
        $results = bcsql_query( $query );
        return $results;
        
    }
    
    function bug_retrieve( $db_result ) {
    
        if ( $row = bcsql_fetch_array( $db_result ) ) {
            $Bug = New Bug( $row );
            return $Bug;
        }
        return false;
        
    }
    
    function CountRetrievedBugs( $DatabaseResult ) {
        
        return bcsql_num_rows( $DatabaseResult ) ;
        
    }
    
    function CountBugs() {
    
        global $bugs;
        
        $query = "SELECT COUNT(*) AS bugs FROM `$bugs`;";
        $sqlr = bcsql_query( $query );
        $fetched_bugs = bcsql_fetch_array( $sqlr );
        return $fetched_bugs[ "bugs" ];
    
    }
    
?>
