<?php
    /* 
    Module: Friends
    File: /modules/friends/friends.php
    Developer: Dionyziz
    */
    include "modules/module.php";
    
    function GetFans( $userid ) {
        // gets an array of userids containing only the ids of the users who have added you as a friend
        // (no matter if YOU have added them) where your user id is $userid
        global $friendfolders;
        
        $sql = "SELECT `ffolder_userid` FROM `$friendfolders`,`$friends` WHERE
                `$friends`.`friend_parentfolderid`=`$friendfolders`.`ffolder_id` AND
                `$friends`.`friend_userid`='$userid';";
        $sqlr = bcsql_query( $sql );
        if( !bcsql_num_rows( $sqlr ) ) {
            return false;
        }
        else {
            $fans = Array();
            while( $sqlfan = bcsql_fetch_array( $sqlr ) ) {
                $fans[] = $sqlfan[ "ffolder_userid" ];
            }
        }
        return $fans;
    }
    
    function UserRegistration_Friends( $invitedbyid ) {
        global $friendfolders;
        global $friends;
        global $users;
        global $user;
        
        $userid = $user->Id();

        // needs to be called when the user registers
        // we need to create the user's friends root folder
        $sql = "INSERT INTO `$friendfolders` 
                    ( `ffolder_id` , `ffolder_parentfolderid` , `ffolder_name` , `ffolder_userid` )
                    VALUES( '' , 0 , 'root' , '$userid' );";
        bcsql_query( $sql );

        // get the newly created folder
        $rootffolder_id = bcsql_insert_id();
        
        // update the user's record to contain this user's friends root folder
        $sql = "UPDATE `$users` 
                SET `user_friendsrootfolderid`='$rootffolder_id'
                WHERE `user_id`='$userid' LIMIT 1;";
        bcsql_query( $sql );
            
        // add the person who invited him/her
        $sql = "INSERT INTO `$friends` 
                    ( `friend_id` , `friend_userid` , `friend_parentfolderid` )
                    VALUES( '' , '$invitedbyid' , '$rootffolder_id' );";
        bcsql_query( $sql );
        
        // add us to the person who invited us
        $invitedby = New User( $invitedbyid );
        $invfid = $invitedby->FriendsRootFolderId();
        $sql = "INSERT INTO `$friends`
                    ( `friend_id` , `friend_userid` , `friend_parentfolderid` )
                    VALUES( '' , '$userid' , '$invfid' );";
        bcsql_query( $sql );
        
        return true; // success
    }
    
    function UserURegistration_Friends( $userid ) {
        // TODO: 
        // remove all friends in all folders of $userid recursively
        // remove friend folders of $userid recursively
        // remove $userid from all users that have added him as a friend
        return true;
    }
    
    class Friend {
        private $mId;
        private $mUserId;
        private $mParentId;
        private $mGotUser;
        private $mUser;
        
        public function Id() {
            return $this->mId;
        }
        public function UserId() {
            return $this->mUserId;
        }
        public function User() {
            if( !$this->mGotUser ) {
                $this->mUser = New User( $this->mUserId );
                $this->mGotUser = true;
            }
            return $this->mUser;
        }
        public function ParentId() {
            return $this->mParentId;
        }
        public function Friend( $construct ) {
            global $friends;
            
            if( is_array( $construct) ) {
                $sqlfriend = $construct;
            }
            else {
                $sql = "SELECT * FROM `$friends` WHERE `friend_id`='$id' LIMIT 1;";
                $sqlr = bcsql_query( $sql );
                if( bcsql_num_rows( $sqlr ) ) {
                    $sqlfriend = bcsql_fetch_array( $sqlr );
                }
                else {
                    bc_die( "Invalid friend id passed as a class constructor to class Friend" );
                }
            }
            $this->mId = $id;
            $this->mUserId = $sqlfriend[ "friend_userid" ];
            $this->mParentId = $sqlfriend[ "friend_parentfolderid" ];
            if( isset( $sqlfriend[ "user_id" ] ) ) {
                $this->mUser = New User( $sqlfriend ); // passing the fetched array
                $this->mGotUser = true;
            }
            else {
                $this->mGotUser = false;
            }
        }
        public function MoveToFolder( $newfolderid ) {
            global $friends;
            
            $sql = "UPDATE `$friends` SET `friend_parentfolderid`='$newfolderid' WHERE `friend_id`='" . $this->mId . "' LIMIT 1;";
            bcsql_query( $sql );
            $this->mParentId = $newfolderid;
            return true;
        }
        public function Remove() {
            global $friends;
            
            $sql = "DELETE FROM `$friends` WHERE `friend_id`='" . $this->mId . "' LIMIT 1;";
            bcsql_query( $sql );
            $this->mId = 0;
            $this->mUserId = 0;
            $this->mParentId = 0;
            return true;
        }
    }
    
    class FriendsTree {
        // SQL-optimized friends tree (2 as opposed to N + K, with N being the number of FriendFolders and K the number of Friends); 
        // to be used instead of FriendFolder/Friend classes when generating friend tree for a given user id
        private $mAllFolders; // array_of( FriendsTreeFolder )
        private $mAllFriends; // array_of( Friend )
        
        public function FriendsTree( $construct ) {
            global $friendfolders;
            global $friends;
            global $users;
            
            if( !ValidId( $construct ) ) {
                bc_die( "Invalid UserId Passed to FriendsTree Constructor" );
            }
            $sql = "
                SELECT
                    *
                FROM
                    `$friendfolders`
                WHERE
                    `ffolder_userid`='$construct';";
            $sqlr = bcsql_query( $sql );
            $i = 0;
            while( $thisfolder = bcsql_fetch_array( $sqlr ) ) {
                $thisftf = New FriendsTreeFolder( $thisfolder );
                $this->mAllFolders[ $thisftf->Id() ] = $thisftf;
                $i++;
            }
            $folderscount = $i;
            $AllFolderIds = array_keys( $this->mAllFolders );
            for( $i = 0 ; $i < $folderscount ; $i++ ) {
                $thischild = $this->mAllFolders[ $AllFolderIds[ $i ] ];
                $thisparentid = $thischild->ParentId();
                if( $thisparentid ) {
                    $thisparent = $this->mAllFolders[ $thischild->ParentId() ];
                    $thisparent->AttachSubfolder( $thischild->Id() );
                }
            }
            
            $sql = "
                SELECT 
                    `$friends`.*,
                    `$users`.*
                FROM
                    `$users`, `$friends`, `$friendfolders`
                WHERE
                    `ffolder_id`=`friend_parentfolderid` AND 
                    `ffolder_userid`='$construct' AND
                    `friend_userid`=`user_id`
                ORDER BY
                    `user_username`;";
            $sqlr = bcsql_query( $sql );
            while( $thisfriend = bcsql_fetch_array( $sqlr ) ) {
                $thisfriend = &New Friend( $thisfriend );
                $this->mAllFriends[ $thisfriend->User()->Id() ] = $thisfriend;
                $thisparent = $this->mAllFolders[ $thisfriend->ParentId() ];
                $thisparent->AttachFriend( $thisfriend->User()->Id() );
            }
        }
        public function FolderById( $folderid ) {
            return $this->mAllFolders[ $folderid ];
        }
        public function FriendByUserId( $userid ) {
            if( !isset( $this->mAllFriends[ $userid ] ) ) {
                bc_die( "Error acquiring friend from FriendsTree using passed userid( $userid )" );
            }
            return $this->mAllFriends[ $userid ];
        }
    }
    
    final class FriendsTreeFolder {
        private $mId;
        private $mName;
        private $mParentId; // @FriendFolder
        private $mUserId;
        private $mSubfoldersTree;
        private $mFriendsTree;
        
        public function Id() {
            return $this->mId;
        }
        public function ParentId() {
            return $this->mParentId;
        }
        public function Name() {
            return $this->mName;
        }
        public function SubfoldersCount() {
            return $this->mSubfoldersTree->ChildrenCount();
        }
        public function FriendsCount() {
            return $this->mFriendsTree->ChildrenCount();
        }
        public function ContainsSubfolders() {
            return $this->mSubfoldersTree->HasChildren();
        }
        public function ContainsFriends() {
            return $this->mFriendsTree->HasChildren();
        }
        public function AttachSubfolder( $folderid ) { 
            // doesn't modify the database, only memory
            $this->mSubfoldersTree->AttachChild( $folderid );
        }
        public function AttachFriend( $userid ) {
            // doesn't modify DB
            $this->mFriendsTree->AttachChild( $userid );
        }
        public function Subfolder() {
            return $this->mSubfoldersTree->Child();
        }
        public function Subfriend() {
            $child = $this->mFriendsTree->Child();
            return $child;
        }
        public function FriendsTreeFolder( $fetched_array ) {
            if ( !is_array( $fetched_array ) ) {
                bc_die( 'FriendsTreeFolder needs an array' );
            }
            $this->mId = $fetched_array[ "ffolder_id" ];
            $this->mName = $fetched_array[ "ffolder_name" ];
            $this->mUserId = $fetched_array[ "ffolder_userid" ];
            $this->mParentId = $fetched_array[ "ffolder_parentfolderid" ];
            if( $this->mParentId == 0 ) {
                $this->mName = "All friends";
            }
            $this->mFriendsTree = New Tree();
            $this->mSubfoldersTree = New Tree();
        }
        
        // derived from the old FriendFolder class
        /*
        
        public function CreateSubfolder( $name ) {
            global $friendfolders;
            
            $name = bcsql_escape( $name );
            $sql = "SELECT COUNT(*) AS subcnt FROM `$friendfolders` WHERE `ffolder_parentfolder`='" . $this->mParentId . "' AND `ffolder_name`='$name' LIMIT 1;";
            $sqlr = bcsql_query( $sql );
            $sqlff = bcsql_fetch_array( $sqlr );
            if( $sqlff[ "subcnt" ] == 1 ) {
                // folder with this name already exists
                return false;
            }
            $sql = "INSERT INTO `$friendfolders` ( `ffolder_id` , `ffolder_parentfolder` , `ffolder_name` ) VALUES( '' , '" . $this->mId . "' , '$name' );";
            bcsql_query( $sql );
            return bcsql_insert_id();
        }

        public function MoveToFolder( $targetparentfolderid ) {
            global $friendfolders;
            
            $sql = "SELECT COUNT(*) AS mvcnt FROM `$friendfolders` WHERE `ffolder_parentfolderid`='$targetparentfolderid' AND `ffolder_name`='" . $this->mName . "' LIMIT 1;";
            $sqlr = bcsql_query( $sql );
            $sqlff = bcsql_fetch_array( $sqlr );
            if( $sqlff[ "mvcnt" ] == 1 ) {
                // folder with the same name exists under new parent
                return false;
            }
            $sql = "UPDATE `$friendfolders` SET `ffolder_parentfolderid`='$targetparentfolderid' WHERE `ffolder_id`='" . $this->mId . "' LIMIT 1;";
            bcsql_query( $sql );
            $this->mParentId = $targetparentfolderid;
            return true;
        }

        public function Rename( $newname ) {
            global $friends;
            
            // we can't have a folder with the same name under the same parent, check
            $newname = bcsql_escape( $newname );
            $sql = "SELECT COUNT( * ) AS rnmcnt FROM `$friends` WHERE `ffolder_parentfolderid`='" . $this->mParentId . "' AND `ffolder_name`='$newname' LIMIT 1;";
            $sqlr = bcsql_query( $sql );
            $sqlff = bcsql_fetch_array( $sqlr );
            if( $sqlff[ "rnmcnt" ] == 1 ) {
                // folder with the same name exists in the same parent directory
                return false;
            }
            // $newname has already been escaped at this point
            $sql = "UPDATE `$friends` SET `ffolder_name`='$newname' WHERE `ffolder_id`='" . $this->mId . "' LIMIT 1;";
            bcsql_query( $sql );
            return true;
        }
        */
    }

    function AddFriend(     $who /* the userid of the friend-to-be-added */ , 
                 $where /* ffolderid of the folder where the friend will be placed */
                /* $whom = 0 the userid of the user who owns that folder; leave empty if unknown */ ) {
        global $friends;
        global $friendfolders;
        
        if ( !ValidId( $who ) ) {
            bc_die( 'AddFriend needs a valid userid as its first parameter' );
        }
        if ( !ValidId( $where ) ) {
            bc_die( 'AddFriend needs a valid ffolderid as its second parameter' );
        }
        
        $whom = 0; // can be passed as a parameter for speeding up the whole thing
        // WHOM makes WHO a friend of his
        // WHO will become a friend of WHOM
        if ( $whom == 0 ) {
            $sql = "SELECT 
                    `ffolder_userid`
                FROM
                    `$friendfolders`
                WHERE
                    `ffolder_id`='$where'
                LIMIT 1;";
            $sqlr = bcsql_query( $sql );
            if ( !bcsql_num_rows( $sqlr ) ) {
                bc_die( 'AddFriend: No such folder' );
            }
            $sqlf = bcsql_fetch_array( $sqlr );
            $whom = $sqlf[ 'ffolder_userid' ];
        }
        elseif( !ValidId( $whom ) ) {
            bc_die( 'AddFriend needs a valid userid or 0 as its third parameter' );
        }

        if ( $who == $whom ) {
            return -2; // you can't be a friend of yourself
        }
        // check if friend already in friends of user
        $sql = "SELECT 
                COUNT(*) AS addcnt 
            FROM 
                `$friends`,`$friendfolders` 
            WHERE 
                `$friends`.`friend_parentfolderid`=`$friendfolders`.`ffolder_id` AND
                `$friendfolders`.`ffolder_userid`='$whom' AND
                `$friends`.`friend_userid`='$who' 
            LIMIT 1;";
        $sqlr = bcsql_query( $sql );
        $sqlff = bcsql_fetch_array( $sqlr );
        if( $sqlff[ "addcnt" ] == 1 ) {
            return -1; //friend already exists
        }
        $sql = "INSERT INTO `$friends` 
                ( `friend_id` , `friend_userid` , `friend_parentfolderid` ) 
                VALUES( '' , '$who' , '$where' );";
        bcsql_query( $sql );
        return bcsql_insert_id();
    }
    function RemoveFriend( $who , $whom ) {
        global $friends;
        global $friendfolders;

        if ( !ValidId( $who ) ) {
            bc_die( 'RemoveFriend needs a valid userid as its first parameter' );
        }
        if ( !ValidId( $whom ) ) {
            bc_die( 'RemoveFriend needs a valid userid as its second parameter' );
        }

        // who will be deleted from whom's friends
        $sql = "DELETE `$friends`.* FROM 
                `$friends`,`$friendfolders`
            WHERE
                `$friends`.`friend_userid`='$who' AND
                `$friends`.`friend_parentfolderid`=`$friendfolders`.`ffolder_id` AND
                `$friendfolders`.`ffolder_userid`='$whom';";
        bcsql_query( $sql );
        return bcsql_affected_rows();
    }
?>