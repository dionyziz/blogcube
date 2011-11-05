<?php
    /*
    Module: Earth
    File: /modules/media/earth/earth.php
    Developer: Dionyziz
    */
    
    include "modules/module.php";
    
    class Earth {
        private $mId;
        private $mUserId;
        private $mUploadedBytes;
        private $mOldUploadedBytes;
        private $mTotalBytes;
        
        public function Earth( $earthid = 0 ) {
            global $earth;
            global $user;
            
            $this->mUploadedBytes = 0;
            $this->mTotalBytes = 1;
            if( $earthid == 0 ) {
                /* 
                    class instanciated without passing any parameters, or by passing 0 as a parameter
                    this means that we're starting a new upload here (it must have been called from inside an upload form
                */
                $this->mUserId = $userid = $user->Id();
                $nowdate = NowDate();
                // generate new earthid and store it in our database
                $sql = "INSERT INTO 
                            `$earth` 
                        ( `earth_id` , `earth_userid` , `earth_uploadedbytes` , `earth_lastactive` ) 
                        VALUES( '' , '$userid' , '0' , '$nowdate' );";
                bcsql_query( $sql );
                
                // we use the build-in MySQL autoincrement id as earthid
                $this->mId = bcsql_insert_id();
                // create bc_earth.# so that the perl file know this earth is valid (it will check if that file exists)
                $earthexistsfile = "/tmp/bc_earth." . $this->mId;
                if( file_exists( $earthexistsfile ) ) {
                    // wait, the earth file arleady exists, someone must be trying to malform a request, exit
                    bc_die( "Earth already exists, while it was not previously in database for $earthid" );
                }
                /* 
                    write the IP of the user for whom this earthid is valid, so that no other user tries to upload this file
                    the perl file will check against this information for additional identity validation, since it can't access the actual userid or $user object
                */
                $fh = fopen( $earthexistsfile , "w" );
                fwrite( $fh , UserIp() );
                fclose( $fh );
            }
            else if( ValidId( $earthid ) ) {
                // okay, we're looking at an existing id (it must be a ValidId in order to do that)
                $this->mId = $earthid;
                // look it up in the dbase
                $sql = "SELECT 
                            * 
                        FROM 
                            `$earth`
                        WHERE
                            `earth_id`='$earthid'
                        LIMIT 1;";
                $sqlr = bcsql_query( $sql );
                if( bcsql_num_rows( $sqlr ) == 0 ) {
                    // hang on it doesn't exist
                    // bc_die( "No such earth" );
                    // let the programmer know that something has gone wrong (it usually means that the earth entry was deleted because the upload has finished)
                    $this->mId = -1;
                    return;
                }
                // fetch the info from the database
                $earthdata = bcsql_fetch_array( $sqlr );
                $this->mUserId = $earthdata[ "earth_userid" ];
                $this->mOldUploadedBytes = $earthdata[ "earth_uploadedbytes" ];
                // query the information provided to us by the perl file
                $postdatafile = "/tmp/bc_earthdata." . $earthid;
                $totalsizefile = "/tmp/bc_earthlength." . $earthid;
                // check if such information exists
                if( file_exists( $postdatafile ) ) {
                    // yep, we get the size of the temporary datafile so that we know how many bytes have been uploaded so far
                    $this->mUploadedBytes = filesize( $postdatafile );
                    if( $this->mUploadedBytes == $this->mOldUploadedBytes ) {
                        // no upload done since last time, cancel upload
                        $this->Cancel();
                        $this->mId = -2;
                        return;
                    }
                }
                else {
                    // temporary data file doesn't exist, this usually means that the upload has been completed, but let's check it
                    // perhaps the perl script hasn't run yet
                    // if a bc_earth.# file exists...
                    if( file_exists( "/tmp/bc_earth." . $this->mId ) ) {
                        // upload not started yet, since the perl script would have deleted it
                        $this->mUploadedBytes = 0;
                    }
                    else {
                        // upload completed, since the perl script has deleted it
                        $this->mUploadedBytes = 1;
                    }
                }
                $nowdate = NowDate();
                $sql = "UPDATE 
                            `$earth` 
                        SET
                            `earth_uploadedbytes`='" . $this->mOldUploadedBytes . "',
                            `earth_lastactive`='$nowdate'
                        WHERE
                            `earth_id`='" . $this->mId . "'
                        LIMIT 1;";
                bcsql_query( $sql );
                // check if totalsize information exists
                if( file_exists( $totalsizefile ) ) {
                    // yep, see how much it is, read from that file
                    $fh = fopen( $totalsizefile , "r" );
                    $this->mTotalBytes = intval( fread( $fh , filesize( $totalsizefile ) ) );
                    fclose( $fh );
                }
                else {
                    // no such file exists, let's assume that the total number of bytes is 1.
                    // (that's because if no totalsize file exists, usually no postdata file will exist either, so uploaded bytes will be either 0 or 1, causing a 0% or 100% percentage to be displayed (that's what we want))
                    $this->mTotalBytes = 1;
                }
            }
            else {
                bc_die( "Invalid earth $earthid" );
            }
        }
        public function Id() {
            return $this->mId;
        }
        public function UserId() {
            return $this->mUserId;
        }
        public function UploadedBytes() {
            return $this->mUploadedBytes;
        }
        public function TotalBytes() {
            return $this->mTotalBytes;
        }
        public function Progress() {
            if( $this->TotalBytes() == 0 ) {
                return 100;
            }
            if( $this->UploadedBytes() >= $this->TotalBytes() )
                return 100;
            $progress = intval( ( $this->UploadedBytes() / $this->TotalBytes() ) * 100 );
            if( $progress == 100 )
                $progress = 99;
            return $progress;
        }
        public function EarthToMedia( $media_filename , $temp_location ) {
            // that's supposed to mark an upload as completed
            // we're going to copy from the temporary location to the new location
            global $earth;
            global $anonymous;
            
            // first off, clear all earth information so that we know there's no pending upload
            $sql = "DELETE FROM 
                        `$earth` 
                    WHERE
                        `earth_id`='" . $this->mId . "'
                    LIMIT 1;";
            bcsql_query( $sql );
            
            // if no login has been done (that's usually the case, because this script gets called internally by the perl script)
            if( $anonymous ) {
                $theuser = New User( $this->mUserId );
                $theuser->Su();
            }
            if( $anonymous ) {
                bc_die( "Failed to login on earth for " . $this->mUserId );
            }
            
            $postdatafile = "/tmp/bc_earthdata." . $earthid;
            $totalsizefile = "/tmp/bc_earthlength." . $earthid;
            if( file_exists( $postdatafile ) ) {
                ulink( $postdatafile );
            }
            if( file_exists( $totalsizefile ) ) {
                ulink( $totalsizefile );
            }
            $this->mUploadedBytes = $this->mTotalBytes;
            
            return submit_media( $media_filename , $temp_location );
        }
        private function Cancel() {
            global $earth;
            
            $sql = "DELETE FROM
                        `$earth`
                    WHERE
                        `earth_id`='" . $this->mId . "'
                    LIMIT 1;";
            bcsql_query( $sql );
        }
    }
?>