<?php
    /* 
    Module: Logs
    File: /modules/logs/logs.php
    Developer: Izual
    */
    include "modules/module.php";

    function logging() {
        global $user;
        global $logs;
        
        $userid = $user->Id();
        $userip = UserIp();
        $timestamp = NowDate();
        $action = $_SERVER[ "REQUEST_URI" ];
        $action = bcsql_escape( $action );
        $query = "INSERT DELAYED INTO `$logs` (`log_id` , `log_userid` , `log_ip` , `log_timestamp` , `log_action`)
                VALUES ('' , '$userid' , '$userip' , '$timestamp' , '$action' );";
        bcsql_query( $query );
        return true;
    }
    logging();
?>