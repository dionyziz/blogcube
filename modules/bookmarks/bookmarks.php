<?php

    /*
    Module: Bookmarks
    File: /modules/bookmarks/bookmarks.php
    Developer: Indy
    */
    
    include "modules/module.php";
    
    function UserRegistration_Bookmarks() {
        global $bookmarkfolders;
        global $user;
        global $users;
        
        $userid = $user->Id();
        $userip = UserIp();
        $fdate = NowDate();
        
        $sql = "INSERT INTO `$bookmarkfolders` 
                    ( `bfolder_id` , `bfolder_name` , `bfolder_userid` , `bfolder_parid`, `bfolder_ip` , `bfolder_date` )
                    VALUES( '' , 'root' , '$userid', '0' , '$userip', '$fdate' );";
        bcsql_query( $sql );

        $rootfolderid = bcsql_insert_id();
        
        $sql = "UPDATE `$users` 
                SET `user_bookmarksrootfolderid`='$rootfolderid'
                WHERE `user_id`='$userid' LIMIT 1;";
        bcsql_query( $sql );
    }
    
    function CreateBookmark( $PostId, $Label ) { //$FolderId should be added on tree structure implementation
    
        global $bookmarks;
        global $user;
    
        $FolderId = $user->BookmarksRootFolderId();
        $nowdate = NowDate();
        $userip = UserIp();
        $userid = $user->Id();
        $Label = bcsql_escape( $Label );
        $query = "INSERT INTO `$bookmarks` ( `bookmark_id` , `bookmark_postid` , `bookmark_folderid` , `bookmark_label` , `bookmark_date` , `bookmark_ip` , `bookmark_userid` ) VALUES ( '' , '$PostId' , '$folderid' , '$Label' , '$nowdate' , '$userip' , '$userid' );";
        bcsql_query( $query );
        return bcsql_insert_id();
    }
    
    function IsBookmarked( $PostId ) {
        global $bookmarks;
        global $user;
        
        $userid = $user->Id();
        
        $query = bcsql_query("SELECT
                    * 
                FROM 
                    `$bookmarks`
                WHERE
                    `bookmark_postid` = '$PostId' AND 
                    `bookmark_userid` = '$userid'
                LIMIT
                    1");
        if ( bcsql_num_rows($query) != 1 ) {
            return false;
        }
        return true;
    }
    
    class Bookmark {
        
        private $mId;
        private $mUserId;
        private $mLabel;
        private $mPostId;
        private $mFolderId;
        private $mDate;
        private $mIp;
        
        public function Bookmark( $Construct ) {

            global $bookmarks;
            
            if ( !is_array( $Construct ) ) {
                $BookmarkId = $Construct;
                $query = "SELECT * FROM `$bookmarks` WHERE `bookmark_id` = '$BookmarkId' LIMIT 1;";
                $sqlr = bcsql_query( $query );
                $bookmark_details = bcsql_fetch_array( $sqlr );
            }
            else
                $bookmark_details = $Construct;
            $this->mUserId = $bookmark_details[ "bookmark_userid" ];
            $this->mLabel = $bookmark_details[ "bookmark_label" ];
            $this->mPostId = $bookmark_details[ "bookmark_postid" ];
            $this->mFolderId = $bookmark_details[ "bookmark_folderid" ];
            $this->mId = $bookmark_details[ "bookmark_id" ];
            $this->mIp = $bookmark_details[ "bookmark_ip" ];
            $this->mDate = $bookmark_details[ "bookmark_date" ];
            
        }
        
        public function DeleteBookmark() {
        
            global $bookmarks;
        
            $Id = $this->mId;
            $query = "DELETE FROM `$bookmarks` WHERE `bookmark_id` = '$Id' LIMIT 1;";
            bcsql_query( $query );
            
        }
        
        public function MoveBookmark( $NewFolderId ) {
        
            global $bookmarks;
        
            $Id = $this->mId;
            $query = "UPDATE `$bookmarks` SET `bookmark_folderid` = '$NewFolderId' WHERE `bookmark_id` = '$Id' LIMIT 1;";
            bcsql_query( $query );
            $this->mFolderId = $NewFolderId;
        }
        
        public function ChangeLabel( $NewLabel ) {
            
            global $bookmarks;
            
            $this->mLabel = $NewLabel;
            $NewLabel = bcsql_escape( $NewLabel );
            $Id = $this->mId;
            $query = "UPDATE `$bookmarks` SET `bookmark_label` = '$NewLabel' WHERE `bookmark_id` = '$Id' LIMIT 1;";
            bcsql_query( $query );
        }
        
        public function Id() {
            return $this->mId;
        }
        
        public function UserId() {
            return $this->mUserId;
        }
        
        public function Label() {
            return $this->mLabel;
        }
        
        public function PostId() {
            return $this->mPostId;
        }
        
        public function Date() {
            return $this->mDate;
        }
        
        public function FolderId() {
            return $this->mFolderId;
        }
        
        public function Ip() {
            return $this->mIp;
        }
        
    }
    
    function CreateBookmarkFolder( $ParId = 0 , $Name ) {
        
        global $bookmarkfolders;
        global $user;
        
        $UserId = $user->Id();
        $UserIp = UserIp();
        $NowDate = NowDate();
        $Name = bcsql_escape( $Name );
        $query = "INSERT INTO `$bookmarkfolders` ( `bfolder_id` , `bfolder_name` , `bfolder_userid` , `bfolder_parid` , `bfolder_ip` , `bfolder_date` ) VALUES ( '' , '$Name' , '$UserId' , '$ParId' , '$UseIp' , '$NowDate' );";
        bcsql_query( $query );
        return bcsql_insert_id();
    }
    
    class BookmarkFolder {
    
        private $mUserid;
        private $mParid;
        private $mId;
        private $mName;
        private $mIp;
        private $mDate;
        
        public function UserId() {
            return $this->mUserId;
        }
        
        public function Parid() {
            return $this->mParid;
        }
        
        public function Id() {
            return $this->mId;
        }
        
        public function Name() {
            return $this->mName;
        }
        
        public function Ip() {
            return $this->mIp;
        }
        
        public function Date() {
            return $this->mDate;
        }
        
        public function BookmarkFolder( $construct ) {
            
            global $bookmarkfolders;
            
            if ( !is_array( $construct ) ) {
                $BookmarkFolderId = $construct;
                $query = "SELECT * FROM `$bookmarkfolders` WHERE `bfolder_id` = `$BookmarkFolderId` LIMIT 1;";
                $sqlr = bcsql_query( $query );
                $bfolder_details = bcsql_fetch_array( $sqlr );
            }
            else {
                $bfolder_details = $construct;
            }
            $this->mUserId = $bfolder_details[ "bfolder_userid" ];
            $this->mParId = $bfolder_details[ "bfolder_parid" ];
            $this->mId = $bfolder_details[ "bfolder_id" ];
            $this->mName = $bfolder_details[ "bfolder_name" ];
            $this->mIp = $bfolder_details[ "bfolder_ip" ];
            $this->mDate = $bfolder_details[ "bfolder_date" ];
            
        }
        
        public function DeleteFolder() {
        
            global $bookmarks;
            global $bookmarkfolders;
            
            $Id = $this->mId;
            $query = "DELETE FROM `$bookmarkfolders` WHERE `bfolder_id` = '$Id' LIMIT 1;";
            bcsql_query( $query );
            $query = "SELECT * FROM `$bookmarkfolders` WHERE `bfolder_parid` = '$Id';";
            $sqlr = bcsql_query( $query );
            if ( bcsql_num_rows( $sqlr ) ) {
                while( $sqlbf = bcsql_fetch_array( $sqlr ) ) {
                    $subbf = New BookmarkFolder( $sqlbf );
                    $subbf->DeleteFolder();
                }
            }
            $query = "DELETE FROM `$bookmarks` WHERE `bookmarks_folderid` = '$Id';";
            bcsql_query( $query );
            $this->mId = 0;
            $this->mName = "";
            $this->mParentId = 0;
            
        }
        
        public function MoveFolder( $NewParId ) {
        
            global $bookmarkfolders;
            
            $Id = $this->Id;
            $query = "UPDATE `$bookmarkfolders` SET `bfolder_parid` = '$NewParId' WHERE `bfolder_id` = '$Id' LIMIT 1;";
            bcsql_query( $query );
            $this->ParId = $NewParId;
            
        }
        
        public function ChangeName( $NewName ) {
            
            global $bookmarkfolders;
            
            $this->mName = $NewName;
            $NewName = bcsql_escape( $NewName );
            $Id = $this->Id;
            $query = "UPDATE `$bookmarkfolders` SET `bfolder_name` = '$NewName' WHERE `bfolder_id` = '$Id' LIMIT 1";
            bcsql_query( $query );
        }
    
    }
    
    function GetAllBookmarks( $CurrentUserId = 0 ) {
    
        global $bookmarks;
        global $user;
        
        if ( $CurrentUserId === 0 )
            $CurrentUserId = $user->Id();
        if ( !ValidId( $CurrentUserId ) ) {
            return false;
        }
        $BookmarkList = Array();
        $Query = "
                SELECT 
                    * 
                FROM 
                    `$bookmarks` 
                WHERE 
                    `bookmark_userid` = $CurrentUserId;";
        $Sqlr = bcsql_query( $Query );
        while ($BookmarkDetails = bcsql_fetch_array($Sqlr)) {
            $BookmarkList[] = New Bookmark( $BookmarkDetails );
        }
        return $BookmarkList;
    
    }
    
    function GetBookmarkByPostId( $PostId ) {
        global $bookmarks;
        global $user;
        
        $userid = $user->Id();
        
        $Query = "SELECT
                    *
                FROM
                    `$bookmarks`
                WHERE
                    `bookmark_userid` = '$userid' AND
                    `bookmark_postid` = '$PostId'
                LIMIT 1
                ";
        $result = bcsql_query($Query);
        $curbookmark = New Bookmark(bcsql_fetch_array($result));
        return $curbookmark;
    }
?>
