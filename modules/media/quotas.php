<?php
    /*
    Module: Quotas control
    File: /modules/media/quotas.php
    Developer: feedWARd
    */
    define("MAXSPACE", "100"); //megabyte
    
    function MediaQuotas( $userid , $additionalfilesize = 0 ) {
        global $media;
        $fetched = bcsql_query("SELECT
                                    `media_id`
                                FROM
                                    `$media`
                                WHERE
                                    `media_userid` = '$userid'");
        while ($curfname = bcsql_fetch_array($fetched)) {
            $filesize += filesize('uploads/' . $userid . '/' . $curfname['media_id']);
        }
        return $filesize/(1024*1024*MAXSPACE);
    }
?>