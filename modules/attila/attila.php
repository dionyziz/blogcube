<?php
    /* 
    Module: Bans
    File: /modules/bans/bans.php
    Developer: Jnfoot
    */
    include "modules/module.php";

    // Please use $bans as your table name, do not use $bans for storing the array containing your bans. --dionyziz
    // Done
    $bansArray = array();
    
    if( !isset( $iswhitelist ) || !$iswhitelist ) {
        //check current user ban status
        GetBan($user->ID(), true);
    
        if (count($bansArray) > 0) {
            bc_die("You are not allowed to BlogCube at the moment. Reason:".$bansArray[0]->Reason);
        }
    }
    
    class Ban {
        var $ban_id;
        var $banned_IP;
        var $banned_user_id;
        var $banning_admin_id;
        var $ban_start;
        var $ban_end;
        var $ban_reason;
        var $ban_active;
        
        function Id() {
            return $this->ban_id;
        }
        
        function IP() {
            return $this->banned_IP;
        }
        
        function User_Id() {
            return $this->banned_user_id;
        }
        
        function Admin_Id() {
            return $this->banning_admin_id;
        }
        
        function BanStart() {
            return $this->ban_start;
        }
        
        function BanEnd() {
            return $this->ban_end;
        }
        
        function Reason() {
            return $this->ban_reason;
        }
        
        function BanActive() {
            return $this->ban_active;
        }
        
        function Ban($bcsql_array) {
            $ban_id = $bcsql_array['ban_id'];
            $banned_IP = $bcsql_array['ban_ip'];
            $banned_user_id = $bcsql_array['ban_userid'];
            $banning_admin_id = $bcsql_array['ban_adminid'];
            $ban_start = $bcsql_array['ban_starttime'];
            $ban_end = $bcsql_array['ban_endtime'];
            $ban_reason = $bcsql_array['ban_reason'];
            $ban_active = $bcsql_array['ban_isactive'];
        }
        
        function RemoveBan() {
            global $bans;
        
            $result = bcsql_query("UPDATE `".$bans."` 
                                    SET `ban_isactive` = 'no' 
                                    WHERE `ban_id` = '".$BanID."'");
            if (bcsql_num_rows($result)) {
                return true;
            } 
            else {
                return false;
            }
        }
    }
    
    function GetBan($IPorID, $onlyactive = true) {
        global $bans;
        global $bansArray;
        
        $bansArray = array(); // you used to have $bans here, which caused an error
        $rban = array();
        
        switch(true) {
            case ValidIP($IPorID):
                $ipex = explode(".",$IPorID);
                $query = "SELECT * FROM `".$bans."` 
                            WHERE `ban_ip` 
                            IN ('".$IPorID."', '".$ipex[0].".*.".$ipex[2].".".$ipex[3]."', '".$ipex[0].".".$ipex[1].".*.".$ipex[3]."', '".$ipex[0].".".$ipex[1].".".$ipex[2].".*"."')";
                break;
            case is_numeric($IPorID):
                $query = "SELECT * FROM `".$bans."` 
                            WHERE `ban_userid` = '".$IPorID."'";
                break;
            default:
                return;
                break;
        }
        
        switch($onlyactive) {
            case true:
                $query .= " AND `ban_isactive` = 'yes'";
                break;
            case false:
                break;
        }
        
        $result = bcsql_query($query);
        
        if ( bcsql_num_rows($result) ) {
            while( $row = bcsql_fetch_array($result) ) { // watch the difference between = and == ; watch your parenthses --dionyziz
                $stillactive = true;
                if (Ban_EndTime_Check($row['ban_endtime'])) {
                    $rban = new Ban($row);
                    $rban->RemoveBan($row['ban_id']);
                    $stillactive = false;
                }
                if ($stillactive || !$onlyactive) {
                    $bansArray[] = new Ban($row);
                }
            }
        }
    }
    
    function AddBan($EndTime, $Reason, $BannedUserID = -1, $UserIP = "") {
        //need function to validate $EndTime is valid datetime
        //need to figure out a way to have $EndTime be infinite, so that a ban can never be
        //  ended unless manually by an admin
        global $user;
        global $bans;
        
        if ($BannedUserID == -1 && $UserIP == "") {
            //error: you must supply either a user id or an ip address/range
            return false;
        }
        
        if ($user->IsAdmin()) {
            $Reason = bcsql_escape($Reason);
            
            if ($UserIP != "") {
                if (!(ValidIP($UserIP)) || !(ValidIPRange($UserIP))) {
                    return false;
                }
            }
            $result = bcsql_query("INSERT INTO `".$bans."` 
                                    (`ban_userid`, `ban_ip`, `ban_adminid`, `ban_starttime`, `ban_endtime`, `ban_isactive`, `ban_reason`) 
                                    VALUES ('".$BannedUserID."', '".$UserIP."', '".$user->Id()."', '".NowDate()."', '".$EndTime."', 'yes', '".$Reason."'");
            return true;
        } 
        else {
            return false;
        }
    }
    
    function Ban_EndTime_Check($Ban_EndTime) {
        $endtimecheck = bcsql_query("SELECT TIMEDIFF('".$Ban_EndTime."', '".NowDate()."')");
        if ($endtimecheck > 0) {
            return false;
        } 
        else {
            return true;
        }
    }
?>