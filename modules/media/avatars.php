<?php
    /* 
    Module: Avatars
    File: /modules/media/avatars.php
    Developer: Izual
    Basic Include File -- this file is included by BlogCube core elements
    */
    function create_avatar( $media_filename , $temp_location , $binary ) {
        global $user;
        global $media;
        global $uploaddir;
        global $useravatars;
        
        $ext = getextension( $media_filename );
        if ( $ext == "jpg" || $ext == "png" || $ext == "jpeg" || $ext == "gif" ) {        
            if ( $ext == "jpg" ) {
                $ext = "jpeg";
            }
            $img_src = imagecreatefromstring( $binary );
            if ( !$img_src ) {
                return -2;
            }
            $src_x = ImageSX( $img_src );
            $src_y = ImageSY( $img_src );
            $img_dst = imagecreatetruecolor( 64 , 64 );
            imagecopyresampled( $img_dst , $img_src , 0 , 0 , 0 , 0 , 64 , 64 , $src_x , $src_y );
            bc_ob_section();
            // output avatar to jpg, regardless of the image type uploaded
            imagejpeg( $img_dst , "" , 100 );
            $result = bc_ob_fallback();
            imagedestroy( $img_src );
            imagedestroy( $img_dst );
            $userid = $user->Id();
            $userip = UserIp();
            $timestamp = NowDate();    
            // convert avatars to jpg, regardless of the filetype uploaded by the user
            $media_filename = RemoveExtension( $media_filename ) . ".jpg";
            $mime = mime_by_filename( $media_filename );
            $media_filename = bcsql_escape( $media_filename );
            $query = "INSERT INTO `$media` (`media_id` , `media_ip`, `media_timestamp`, `media_userid` , `media_filename` , `media_mimetype` , `media_active` )
                    VALUES ('' , '$userip' , '$timestamp' , '$userid' , '$media_filename' , '$mime' , 'yes');";
            bcsql_query( $query );
            $last_insmedia_id = bcsql_insert_id();
            $userfolder = $uploaddir . '/' . $userid;
            if ( !file_exists( $userfolder ) ) {
                mkdir( $userfolder );
            }
            $destination = $userfolder."/".$last_insmedia_id;
            $query = "INSERT INTO `$useravatars` ( `avatar_id` , `avatar_userid` , `avatar_mediaid` )
                    VALUES ( '' , '$userid' , '$last_insmedia_id' );";
            $expectedresult = bcsql_query( $query );
            $last_avatar_id = bcsql_insert_id();            
            $fp = fopen( $destination , "w" );
            fwrite( $fp , $result );
            fclose( $fp );
            if( $user->Avatar() == 0 ) {
                $thisavatar = New Avatar( Array( "avatar_id" => $last_avatar_id , "avatar_userid" => $user->Id() ) , false );
                $thisavatar->avatar_make_default();
            }
            return $last_avatar_id;
        }
        else {
            return -1;
        }
    }
    function retrieve_user_avatars( $userid ) {
        //returning an array with the media id's of all avatars for a particular user
        global $useravatars;
        
        if( !ValidId( $userid ) ) {
            bc_die( "Invalid userid" );
        }
        $query = "SELECT * FROM `$useravatars` WHERE `avatar_userid` = '$userid' ORDER BY `avatar_id`;";
        $sqlr = bcsql_query( $query );
        $num_rows = bcsql_num_rows( $sqlr );
        for ( $i=0; $i<$num_rows; $i++ ) {
            $avatars = bcsql_fetch_array( $sqlr );
            $avatarid = $avatars[ "avatar_id" ];
            $avatarclass = New avatar( $avatarid );
            if( $avatarclass->active() ) {
                $res_avatar_instances[] = $avatarclass;
            }
        }
        return $res_avatar_instances;
    }
    function media_total_size( $userid ) {
        $folder = "uploads/".$userid;
        if ( !file_exists( $folder ) ) {
            return false;
        }
        else {
            $size = filesize( $folder );
            return $size;
        }
    }
    class avatar extends media {
        private $mavatar_id;
        private $mavatar_mediaid;
        private $mavatar_default;
        
        public function avatar_id() {
            return $this->mavatar_id;
        }
        //check if an avatar is the default
        public function avatar_is_default_useravatar() {
            global $users;
            global $userdefaultavatars;
            
            if ( !$userdefaultavatars[ $this->userid() ] ) {
                $query = "SELECT 
                            `user_defaultavatar` 
                          FROM 
                            `$users` 
                          WHERE 
                            `user_id` = ".$this->mavatar_userid." 
                          LIMIT 1;";
                $sqlr = bcsql_query( $query );
                $num_rows = bcsql_num_rows( $sqlr );
                if ( $num_rows == 1 ) {    
                    $res = bcsql_fetch_array( $sqlr );
                    $user_default_avatar = $res[ "user_defaultavatar" ];
                    $userdefaultavatars[ $this->userid() ] = $user_default_avatar;
                }
                else {
                    bc_die( "Error trying to find default avatars" );
                }
            }
            if ( $this->id() == $userdefaultavatars[ $this->userid() ]) {
                return true;
            }
            $userdefaultavatars[ $this->userid() ] = -1;
        }    
        public function avatar( $construct , $alsoconstructmedia = true ) {
            global $useravatars;
            global $media;
            
            if( is_array( $construct ) ) {
                $avatar = $construct;
                $avatar_id = $avatar[ "avatar_id" ];
            }
            else {
                $avatar_id = $construct;
                $this->mavatar_id = $avatar_id;
                if( !ValidId( $avatar_id ) ) {
                    bc_die( "Invalid avatar_id when constructing the Avatar class: $avatar_id" );
                }
                $query = "
                        SELECT 
                            * 
                        FROM 
                            `$useravatars`, `$media`
                        WHERE 
                            `avatar_id`='$avatar_id' AND
                            `media_id`=`avatar_mediaid`
                        LIMIT 1;";
                $sqlr = bcsql_query( $query );
                $num_rows = bcsql_num_rows( $sqlr );
                if ( $num_rows == 1 ) {        
                    $avatar = bcsql_fetch_array( $sqlr );
                }
                else {
                    bc_die( "Cannot construct avatar class. Avatar_id: ".$avatar_id );
                }
            }
            $this->m_userid = $this->mavatar_userid = $avatar[ "avatar_userid" ];
            if( $alsoconstructmedia ) {
                $this->media( $avatar );
            }
        }
        public function avatar_make_default() {
            global $users;
            
            $query = "UPDATE `$users` SET `user_defaultavatar` = '" . $this->m_id . "' WHERE `user_id`='" . $this->m_userid . "' LIMIT 1;";
            bcsql_query( $query );
            return true;
        }
    }
    //check if user has uploaded any avatar and return true if he has and false if he hasnt
    function any_avatar_uploaded( $userid ) {
        $avatars = retrieve_avatars( $userid );
        $avat_nums = count( $avatars );
        if ( $avat_nums == 0 ) {
            return false;
        }
        else {
            return true;
        }
    }
    function avatars_usersize( $userid ) {
        $avatars = retrieve_avatars( $userid );
        $avat_nums = count( $avatars );
        $sum = 0;
        if ( any_avatar_uploaded( $userid ) ) {
            for ( $i=0; $i<=$avat_nums; $i++ ) {
                $this_avatar = $avatars[ $i ];
                $this_avatar_id = $this_avatar[ "avatar_mediaid" ];
                $location = "uploads/".$userid;
                $avatarsize = filesize( $location."/".$this_avatar_id );
                $sum = $sum + $avatarsize;
            }
        }
        else {    
            return $sum;
        }
    }
    /*
    function avatar_set_inactive( $avatarid ) {    
        global $useravatars;
        
        $query = "DELETE FROM `$useravatars` WHERE `avatar_id`='$avatarid';";
        bcsql_query( $query );
        $avatarclass = New avatar( $avatarid );
        $avatarmediaid = $avatarclass->Id();
        media_set_inactive( $avatarmediaid );
    }
    */
    function avatar_set_inactive_by_mediaid( $mediaid ) { // blame dionyziz
        global $useravatars;
        
        $query = "DELETE FROM `$useravatars` WHERE `avatar_mediaid`='$mediaid';";
        bcsql_query( $query );
        return media_set_inactive( $mediaid );
    }
?>