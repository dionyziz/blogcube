<?php
    /*
    Developer: Izual
    there are 2 functions for adding a photo to an album, not yet sure what to use--testing
    */
function album_create( $album_name ,  $publ_stat ) {
    global $photoalbums;
    global $user; 
    
    $album_name = bcsql_escape( $album_name );
    $this_date = NowDate();
    $this_ip = UserIp();
    $publ_stat = bcsql_escape( $publ_stat );
    $user_id = $user->Id();
    //$publ_stat can either be public, friends ,ffriends or private
    $query = "INSERT INTO `$photoalbums` ( `album_id` , `album_name` , `album_userid` , `album_userip`, `album_createdate`, `album_modifydate` , `album_mainpic` , `album_publicity` )
                VALUES ( '' , '$album_name' , '$user_id' , '$this_ip' , '$this_date' , '$this_date' , '0' , '$publ_stat' );";
    bcsql_query( $query );
    $last_album_id = bcsql_insert_id();
    return $last_album_id;
}
function album_set_inactive( $album_id ) {
    global $photoalbums;
    global $deletedphotoalbums;
    
    if ( ValidId( $album_id ) ) {
        $query = "SELECT `album_name` , `album_userid` , `album_userip` , `album_createdate` , `album_modifydate` , `album_mainpic` , `album_publicity` FROM `$photoalbums` WHERE `album_id`='$album_id' LIMIT 1;";
        $sqlr = bcsql_query( $query );
        $num_rows = bcsql_num_rows( $sqlr );
        if ( $num_rows == 1 ) {
            $this_date = NowDate();
            $this_ip = UserIp();        
            $albums = bcsql_fetch_array( $sqlr );
            $album_name = $albums[ "album_name" ];
            $album_userid = $albums[ "album_userid" ];
            $album_userip = $albums[ "album_userip " ];
            $album_createdate = $albums[ "album_createdate" ];
            $album_modifydate = $albums[ "album_mainpic" ];
            $album_picid = $albums[ "albums_mainpic" ];
            $album_publ = $albums[ "albums_publicity" ];
            $query = "DELETE  FROM `$photoalbums` WHERE `album_id`='$album_id';";
            bcsql_query( $query );
            $album_name = bcsql_escape( $album_name );
            $publ_stat = bcsql_escape( $publ_stat );
            $query = "INSERT INTO `$deletedphotoalbums` ( `delalbum_id` , `delalbum_albumid` , `delalbum_name` , `delalbum_userid` , `delalbum_userip` , `delalbum_deluserip` , `delalbum_createdate` , `delalbum_lastmodifydate` , `delalbum_deletedate` , `delalbum_mainpic` , `delalbum_publicity` )
                        VALUES ( '' , '$album_id' , '$album_name' , '$album_userid' , '$album_userip' , '$this_ip' , '$album_createdate' , '$album_modifydate' , '$this_date' , '$album_picid' , '$album_publ' );";
            bcsql_query( $query );
            //deleting all photos of the album
            $photo_instances = album_retrievephotos( $album_id );
            $num_rows = count( $photo_instances );
            for ( $i=0; $i<$num_rows; $i++ ) {
                $this_photo = $photo_instances[ $i ];
                media_set_inactive( $this_photo->Id() );
            }
        }
        else {
            bc_die( "Error during query using albumid. Album_id: ".$album_id );
        }
    }
}
class album {
    var $aId; // use $mYadda here, according to conventions, specify public/private/protected
    var $aName;
    var $aUserid;
    var $aUserip;
    var $aCreatedate;
    var $aModifydate; // *private* $mModifyDate... 
    var $aMainpicid;
    var $aPublicity;
    var $aPhotonum;
    
    // **public** function foo() { ...
    function id() {
        return $this->aId;
    }
    function name() {
        return $this->aName;
    }
    function userid() {
        return $this->aUserid;
    }
    function userip() {
        return $this->aUserip;
    }
    function createdate() {
        return $this->aCreatedate;
    }
    function modifydate() {
        return $this->aModifydate;
    }
    function main_picid() {
        return $this->aMainpicid;
    }
    function publicity() {
        return $this->aPublicity;
    }

    function photo_num() {
        return $this->aPhotonum;
    }
    
    function album( $albumsth , $point ) {
        global $photoalbums;
        global $photoalbumsmedia;
        //$point is 1 or 2, to check whether the passed value is an albumid or a  album name
        if ( $point == 1 ) {
            if ( ValidId( $albumsth ) ) {
                $query = "
                    SELECT 
                        * 
                    FROM 
                        `$photoalbums` 
                    WHERE 
                        `album_id`='$albumsth' 
                    LIMIT 1;";
            }
            else {
                bc_die( "Invalid id of album in the constructor. Album id: ".$album_id );
            }
        }
        else {
            $albumsth = bcsql_escape( $albumsth );
            $query = " 
                SELECT 
                    *
                FROM
                    `$photoalbums`
                WHERE
                    `album_name`='$albumsth'
                LIMIT 1;";
        }
        $sqlr = bcsql_query( $query );
        $num_rows = bcsql_num_rows( $sqlr );
        if ( $num_rows == 1 ) {
            $albums = bcsql_fetch_array( $sqlr );
            $this->aId = $albums[ "album_id" ];
            $this->aName = $albums[ "album_name" ];
            $this->aUserid = $albums[ "album_userid" ];
            $this->aUserip = $albums[ "album_userip " ];
            $this->aCreatedate = $albums[ "album_createdate" ];
            $this->aModifydate = $albums[ "album_modifydate" ];
            $this->aMainpicid = $albums[ "album_mainpic" ];
            $this->aPublicity = $albums[ "album_publicity" ];
            $query = "SELECT COUNT( pamedia_id ) as photo_nums FROM `$photoalbumsmedia` WHERE `pamedia_albumid`='$album_id';";
            $sqlr = bcsql_query( $query );
            $nums = bcsql_fetch_array( $sqlr );
            $this->aPhotonum = $nums[ "photo_nums" ];        
        }
        else {
            bc_die( "Query of album constructor returns not one id ".$num_rows );
        }
    }
}    
function add_photo_toalbum( $filename , $location , $album_id ) {
    global $photoalbumsmedia;
    
    if ( ValidId( $album_id )) {
        $media_id = submit_media( $filename , $location );
        $query = "INSERT INTO `$photoalbumsmedia` ( `pamedia_id` , `pamedia_mediaid` , `pamedia_albumid` )
                    VALUES ( '' , '$media_id' , '$album_id' );";
        bcsql_query( $query );
        return $media_id;
    }
    else {
        bc_die( "Not valid album id to add photo. Album_id:".$album_id );
    }
}
function album_retrievephotos( $album_id ) {
    global $photoalbumsmedia;
    
    if ( ValidId( $album_id )) {
        $query = "
            SELECT 
                `pamedia_mediaid` 
            FROM 
                `$photoalbumsmedia` 
            WHERE 
                `pamedia_albumid`='$album_id';";
        $sqlr = bcsql_query( $query );
        $num_rows = bcsql_num_rows( $sqlr );
        for ( $i=0; $i<$num_rows; $i++ ) {
            $albums_photos = bcsql_fetch_array( $sqlr );
            $album_photoid = $albums_photos[ "pamedia_mediaid" ];
            $photoclass = New photo( $album_photoid );
            $res_palbums_instances[] = $photoclass;
        }
        return $res_palbums_instances;
    }
    else {
        bc_die( "Not valid album id to retrieve photos. Album_id:".$album_id );
    }
}
function albums_retrieve_albums( $userid ) {
    global $photoalbums;
    
    if ( ValidId( $userid ) ) {
        $query = "
            SELECT 
                `album_id` 
            FROM 
                `$photoalbums` 
            WHERE 
                `album_userid`='$userid'
            ORDER BY
                `album_id` DESC;";
        $sqlr = bcsql_query( $query );
        $num_rows = bcsql_num_rows( $sqlr );
        for ( $i=0; $i<$num_rows; $i++ ) {
            $albums_rsrc = bcsql_fetch_array( $sqlr );
            $albums_ids = $albums_rsrc[ "album_id" ];
            $albumclass = New album( $albums_ids , 1 );
            $albums_instances[] = $albumclass;
        }
        if ( $num_rows == 0 ) {
            return Array();
        }
        else {
            return $albums_instances;
        }
    }
    else {
        bc_die( "Not valid user id to search for albums. User id: ".$userid ) ;
    }
}
function album_photo_setinactive( $photoid ) {
    global $photoalbumsmedia;
    
    if ( ValidId( $photoid )) {
        $query = "
            DELETE FROM 
                `$photoalbumsmedia` 
            WHERE 
                `pamedia_mediaid`='$photoid';";
        bcsql_query( $query );
        media_set_inactive( $photoid );
    }
    else {
        bc_die( "Not valid photo id to delete. Photo id:".$photoid ) ;
    }
}
function set_album_mainpicid( $picid , $album_id ) {
    global $photoalbums;
    
    if ( ValidId( $picid ) ) {
        if ( ValidId( $album_id ) ) {
            $today = NowDate();
            $query = "
                UPDATE 
                    `$photoalbums` 
                SET 
                    `album_mainpic`='$picid',
                    `album_modifydate`='$today' 
                WHERE 
                    `album_id`='$album_id';";
            bcsql_query( $query );
        }
        else {
            bc_die( "Wrong album_id. Album id: ".$album_id );
        }
    }
    else {
        bc_die( "Wrong picture id. Picture id: ".$picid );
    }
}
function update_album_name( $albumid , $newalbumname ) {
    global $photoalbums;
    
    if ( ValidId( $albumid ) ) {
        $today = NowDate();
        $newalbumname = bcsql_escape( $newalbumname );
        $query = "UPDATE `$photoalbums` SET `album_name`='$newalbumname', `album_modifydate`='$today' WHERE `album_id`='$albumid' LIMIT 1;";
        bcsql_query( $query );
    }
    else {
        bc_die( "Wrong album_id to alter name. Album id: ".$albumid );
    }
}
function album_publ_set( $albid , $value ) {
    global $photoalbums;
    
    if ( ValidId( $albid )) {
        if ( $value=="public" || $value=="friends" || $value=="ffriends" || $value=="private" ) {
            $today = NowDate();
            $query = "UPDATE `$photoalbums` SET `album_publicity`='$value', `albumodifydate='$today' WHERE `album_id`='$albid';";
            bcsql_query( $query );
        }
        else {
            bc_die( "Not valid publicity value. Value: ".$value );
        }
    }
    else {
        bc_die( "Wrong album id. Album id: ".$albid );
    }
}
class photo extends media {
    var $width;
    var $height;
    
    function width() {
        return $this->width;
    }
    function height() {
        return $this->height;
    }
    
    function photo( $construct ) {
        global $user;
        
        if (!is_array($construct)) {
            $mediaid = $construct;
        }
        else {
            // ... TO DO 
        }
        $this->Media( $mediaid );
        $img_src = @imagecreatefromstring( $this->binary() );
        bc_assert( $img_src !== false );
        $this->width = ImageSX( $img_src );
        $this->height = ImageSY( $img_src );
    }
    function proportional_size( $maxw , $maxh ) {
        if ( $this->width > $this->height && $this->width > $maxw ) {
            $prop = $this->width / $this->height;
            $nwidth = $maxw;
            $nheight = $maxw / $prop;
        }
        elseif ( $this->height > $this->width && $this->height > $maxh ) {    
            $prop = $this->height / $this->width;
            $nwidth = $maxh / $prop;
            $nheight = $maxh;
        }
        elseif ( $this->height == $this->width && $this->width>$maxw ) {
            $nwidth = $maxw;
            $nheight = $maxh;
        }
        else { 
            $nwidth = $this->width;
            $nheight  = $this->height;
        }
        $size[ 0 ] = round( $nwidth , 0 );
        $size[ 1 ] = round( $nheight , 0 );
        return $size;
    }
    function create_photo( $pwidth , $pheight ) {
        global $user;
        
        $img_src = imagecreatefromstring( $this->binary() );
        $src_x = ImageSX( $img_src );
        $src_y = ImageSY( $img_src );
        $this->width = ImageSX( $img_src );
        $this->height = ImageSY( $img_src );
        $img_dst = imagecreatetruecolor( $pwidth , $pheight );
        imagecopyresampled( $img_dst , $img_src , 0 , 0 , 0 , 0 , $pwidth , $pheight , $src_x , $src_y );
        bc_ob_section();
        imagejpeg( $img_dst , "" , 100 );
        $result = bc_ob_fallback();
        imagedestroy( $img_src );
        imagedestroy( $img_dst );
        return $result;
    }
}
function add_photo( $album_id , $filename , $temp_location ) {
    global $photoalbumsmedia;
    
    if( ValidId( $album_id ) ) {
        $mediaid = submit_media( $filename , $temp_location );
        $query = "INSERT INTO `$photoalbumsmedia` ( `pamedia_id` , `pamedia_mediaid` , `pamedia_albumid` )
                    VALUES ( '' , '$mediaid' , '$album_id' );";
        bcsql_query( $query );
        $last_photo_id = bcsql_insert_id();
    }
    else {
        bc_die( "Non valid album id. Albumid: " . $album_id );
    }
    return $mediaid;
}
