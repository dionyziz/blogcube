<?php
    /* 
    Module: Media
    File: /modules/media/media.php
    Developer: Izual
    Basic Include File -- this file is included by BlogCube core elements
    */
    
    if ( $debug_version ) {
        $uploaddir = "/home/media/uploads/beta";
    }
    else {
        $uploaddir = "/home/media/uploads/stable";
    }

    include "modules/module.php";
    include "modules/media/avatars.php";
    include "modules/media/mime.php";
    include "modules/media/albums/albums.php";
    include "modules/media/earth/earth.php";
    include "modules/media/quotas.php";
    
    /*this function takes two parametres, the filename and the binary of the media
    we include the php script that makes the mime type recogntion
    you can check the mime.php where there's a function returning the mimetype after taking as parametr the filename of the media
    */

    function submit_media( $media_filename , $temp_location ) {
        global $user;
        global $media;
        global $uploaddir;
        
        $userid = $user->Id();
        $userip = UserIp();
        $timestamp = NowDate();    
        $media_filename = bcsql_escape( $media_filename );
        $mime = mime_by_filename( $media_filename );
        $query = "INSERT INTO `$media` (`media_id` , `media_ip`, `media_timestamp`, `media_userid` , `media_filename` , `media_mimetype` , `media_active` , `media_compressed` )
                VALUES ('' , '$userip' , '$timestamp' , '$userid' , '$media_filename' , '$mime' , 'yes' , 'no' );";
        bcsql_query( $query );
        $last_media_id = bcsql_insert_id();
        $userfolder = $uploaddir . '/' . $userid;
        if ( !file_exists( $userfolder ) ) {
            mkdir( $userfolder );
        }
        $destination = $userfolder."/".$last_media_id;
        $result = move_uploaded_file( $temp_location , $destination );
        // bc_die('submit_media: ' . $temp_location . " / " . $destination);
        media_compress( $destination , $last_media_id );
        return $last_media_id;
    }
    
    function media_compress( $filepath , $mediaid ) {
        $fh = fopen( $filepath , "rb" );
        $binary = fread( $fh , filesize( $filepath ) );
        fclose( $fh );
        // decrease second parameter for faster executing, increase it for more compression
        $compressedbinary = gzcompress( $binary , 6 );
        if( strlen( $binary ) * ( 3 / 4 ) > strlen( $compressedbinary ) ) {
            // keep compressed
            // TODO: we need to *save* the compressed file and update our database entry
            return true;
        }
        else {
            // don't compress; too little space saving for the performarce hit of the compression
            return false;
        }
    }
    
    class media {
        protected $m_id;
        private $m_ip;
        private $m_timestamp;
        protected $m_userid;
        private $m_filename;
        private $m_binary;
        private $m_mimetype;
        private $m_active;
        private $m_extension;
        private $m_simpletext;
        private $mBinaryCached;
        private $m_location;
        private $mCompressed;
        private $m_size;
        
        public function id() {
            return $this->m_id;
        }
        public function ip() {
            return $this->m_ip;
        }    
        public function timestamp() {
            return $this->m_timestamp;
        }
        public function userid() {
            return $this->m_userid;
        }
        public function filename() {
            return $this->m_filename;
        }
        public function compressed() {
            return $this->mCompressed;
        }
        public function binary() {
            global $uploaddir;

            if ( !$this->mBinaryCached ) {
                $usermedia = $uploaddir."/".$this->m_userid."/".$this->m_id;
                $fp = fopen ( $usermedia , "r" );
                $this->m_binary = fread( $fp , filesize( $usermedia ) );
                fclose ( $fp );
                $this->mBinaryCached = true;
            }    
            return $this->m_binary;
        }
        public function mimetype() {
            return $this->m_mimetype;
        }
        /*
        this function returns just the extension of the media  e.g. mp3, avi,rar etc
        */
        public function extension() {    
            if( !$this->m_extension ) {
                $ext = getextension( $this->m_filename );
                $this->m_extension = $ext;
            }
            return $this->m_extension;
        }
        /*
        this function checks if the "media" is text that means if the mimetype contains the string "text" e.g. text/css
        and returns true if it's is text
        */
        public function simpletext() {
            $strslash = strpos( $this->m_mimetype , "/" );
            $type= substr( $this->m_mimetype , 0 , $strslash );
            if ( $type == "text" ) {
                return true;
            }
        }
        public function active() {
            return $this->m_active;
        }
        public function location() {
            return $this->m_location;
        }
        public function size() {    
            return $this->m_size;
        }
        
        public function media( $construct ) {
            // construct:
            // * New Media( $mediaid )
            // * New Media( $fetched_array )
            global $media;
            global $uploaddir;

            if( is_array( $construct ) ) {
                $mediaarray = $construct;
            }
            else if( ValidId( $construct ) ) {
                $media_id = $construct;
                $query = "SELECT * FROM `$media` WHERE `media_id` = '$media_id' LIMIT 1;";
                $sqlr = bcsql_query( $query );
                $num_rows = bcsql_num_rows( $sqlr );
                if ( $num_rows == 1 ) {
                    $mediaarray = bcsql_fetch_array( $sqlr );
                }
                else {
                    bc_die( "Cannot construct media class. Media id: $media_id" );
                }
            }
            else {
                bc_die( "Invalid media_id passed to the media class as constructor: $construct" );
            }
            $this->m_id = $mediaarray[ "media_id" ];
            $this->m_ip = $mediaarray[ "media_ip" ];
            $this->m_timestamp = $mediaarray[ "media_timestamp" ];
            $this->m_userid = $mediaarray[ "media_userid" ];
            $this->m_filename = $mediaarray[ "media_filename" ];
            $this->m_mimetype = $mediaarray[ "media_mimetype" ];
            $this->m_active = $mediaarray[ "media_active" ] == "yes"; 
            $this->m_compressed = $mediaarray[ "media_compressed" ];
            $this->mBinaryCached = false;
            $this->m_location = $uploaddir."/".$this->m_userid."/".$this->m_id;
            $this->m_size = filesize( $this->m_location );
        }
    }
    //here is a function for setting a media to inactive, after taking as parametr the ID of the media
    function media_set_inactive( $id ) {
        global $media;
        global $deleted_media;
        global $user;
        global $uploaddir;
        
        $query = "SELECT `media_ip`,`media_timestamp`,`media_filename`,`media_compressed` FROM `$media` WHERE `media_id` = '$id' LIMIT 1;";
        $sqlr=bcsql_query ( $query );
        $num_rows = bcsql_num_rows( $sqlr );
        if ( $num_rows == 1 ) {
            $todays_date = NowDate();
            $user_id = $user->Id();
            $user_ip = UserIp();
            $media_info = bcsql_fetch_array( $sqlr );
            $m_ip = $media_info[ "media_ip" ];
            $m_timestamp = $media_info[ "media_timestamp" ];
            $m_filename = $media_info[ "media_filename" ];
            $m_compressed = $media_info[ "media_compressed" ];
            $m_mimetype = $media_info[ "media_mimetype" ];
            $query = "INSERT INTO `$deleted_media` ( `del_id`, `del_mediaid`, `del_ip_upload`, `del_timestamp_upload`, `del_userid`, `del_filename`, `del_mimetype`, `del_compressed`, `del_ip_del`, `del_timestamp_del`)
                        VALUES ( '' , '$id', '$m_ip', '$m_timestamp', '$user_id', '$m_filename', '$m_mimetype', '$m_compressed', '$user_ip', '$todays_date');";  
            bcsql_query( $query );
            $query = "DELETE FROM `$media` WHERE `media_id`='$id';";
            bcsql_query( $query );
            $source = $uploaddir . $user_id . "/" . $id;
            $destfolder = $uploaddir . "deleted/" . $user_id;
            $destfile = $uploaddir . "deleted/" . $user_id . "/" . $id;
            if ( !file_exists( $destfolder ) ) {
                mkdir( $destfolder );
            }
            rename( $source , $destfile );
            return true;
        }
        bc_die( "Cannot find media by id" );
        return false;
    }    
?>