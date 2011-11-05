<?php
    /*
    this is a script that cleans up all the invalid database media entries and
    deletes the media files that don't exist in the database
    Developer: Izual
    */
    function media_cleanup() {    
        global $media;
        global $avatars;
        global $photoalbumsmedia;
        
        $query1 = "SELECT `media_id` , `media_userid` FROM `$media`s;";
        $sqlr1 = bcsql_query( $query1 );
        $mediaids = bcsql_fetch_array( $sqlr1 );
        $num_rows1 = bcsql_num_rows( $mediaids );
        for( $i = 0; $i<$num_rows1; $i++ ) {
            $thismedia = $mediaids[ $i ];
            $thismediaid = $thismedia[ "media_id" ];
            $mediaclass = New media( $thismediaid );
            $userid = $mediaclass->userid();
            $userfile = $uploaddir . $userid ."/". $thismediaid;
            if( !file_exists( $userfile ) ) {
                $sql = "DELETE FROM `$media` WHERE `media_id` = '$thismediaid';"; 
                bcsql_query( $sql );
                $sql = "DELETE FROM `$avatars` WHERE `avatar_mediaid` = '$thismediaid';";
                bcsql_query( $sql );
                $sql = "DELETE FROM `$photoalbumsmedia` WHERE `pamedia_mediaid` = '$thismediaid';";
                bcsql_query( $sql );
            }
        }
        //the oposite thing remains to be done
    }