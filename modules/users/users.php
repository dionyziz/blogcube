<?php
    /* 
    Module: Users
    File: /modules/users/users.php
    Developer: Dionyziz
    */
    include "modules/module.php";

    // authentication script
    include "modules/users/auth.php";
    
    // perform authentication: check if user is logged in and initialize $user
    auth();
    
    function PasswordStrength( $password ) {
        global $user;
        
        $sequences = Array(
            "`1234567890-=" ,
            "qwertyuiop[]\\" ,
            "asdfghjkl;'" ,
            "zxcvbnm,./" , 
            "~!@#$%^&*()_+" ,
            "QWERTYUIOP{}|" ,
            "ASDFGHJKL:\"" ,
            "ZXCVBNM<>?" ,
            "abcdefghijklmnopqrstuvwxyz" ,
            "ABCDEFGHIJKLMNOPQRSTUVWXYZ" );
        
        $pwdlength = strlen( $password );
        $strengthmodifier = 0;
        
        if( $pwdlength < 4 ) {
            // password too short
            return 0;
        }
        if( strpos( strtolower( $user->Username() ) , strtolower( $password ) ) !== false ) {
            return 1;
        }
        if( preg_match( "#^[0-9]+$#" , $password ) == 1 ) {
            // the whole password consists of numbers
            $strengthmodifier = 1;
        }
        else {
            for( $i = 0 ; $i < count( $sequences ) ; $i++ ) {
                if( strpos( $sequences , $password ) !== false ) {
                    // the whole password is a part of a sequence
                    $strengthmodifier = 2;
                    break;
                }
            }
            if( !$strength_modifier ) {
                if( bc_spell_start( Array( $password ) ) == 0 ) {
                    // the whole password is a dictionary word
                    $strengthmodifier = 3;
                }
                else if( preg_match( "#^[a-z]+$#" , $password ) == 1 || preg_match( "#^[A-Z]+$#" , $password ) == 1 ) {
                    // the whole password consists of lower-case-only or upper-case-only latin characters
                    $strengthmodifier = 4;
                }
                else if( preg_match( "#^[a-z0-9]+$#" , $password ) == 1 || preg_match( "#^[A-Z0-9]+$#" , $password ) == 1 ) {
                    // the whole password consists of either only lower-case characters and numbers or only upper-case characters and numbers
                    $strengthmodifier = 5;
                }
                else if( preg_match( "#^[A-Za-z]+$#" , $password ) == 1 ) {
                    // the whole password consists of mixed lower and upprt case latin characters
                    $strengthmodifier = 6;
                }
                else if( preg_match( "#^[A-Za-z0-9]+$#" , $password ) == 1 ) {
                    // the whole password consists of mixed lower-case letters, upper-case letters and numbers
                    $strengthmodifier = 7;
                }
            }
        }
        $result = $strengthmodifier * ( $pwdlength - 3 ) * 5.55;
        if( $result > 100 ) {
            $result = 100;
        }
        $result = round( $result );
        return $result;
    }
    
    function Register( $invitationid , $invitationcode , $username , $passwordplaintext , $email , $firstname , $lastname , $invitedbyuserid ) {
        global $users;
        global $s_username;
        global $s_password;
        
        // mark the invitation as "userd"
        if( !ValidId( $invitationid ) ) {
            bc_die( "Invalid invitation id provided" );
        }
        $invitation = New Invitation( $invitationid );
        if( !$invitation->CheckValidy( $invitationcode ) ) {
            bc_die( "The invitation code you provided does not match your invitation" );
        }
        if( $invitation->RegisteredUserId() ) {
            bc_die( "The invitation you are trying to use has already been used" );
        }
        
        $theuser = GetUserByUsername( $username );
        if( $theuser ) {
            // user with this username already exists
            return -1; 
        }
        if( !ValidEmail( $email ) ) {
            // invalid email address
            return -2;
        }
        $validuname = ValidUname( $username );
        if( $validuname === -1 ) {
            // invalid username
            return -3;
        }
        elseif( $validuname === -2 ) {
            // reserved username
            return -4;
        }
        // will we allow two different accounts with the same e-mail address?
        $pwd = md5( $passwordplaintext );
        $nowdate = NowDate();
        $sql = "INSERT INTO `$users` ( `user_id` , `user_username` , `user_password` , `user_email` , `user_registerdate` , `user_invitedbyuserid` , `user_firstname` , `user_lastname` , `user_bcadmin` ) VALUES( '' , '$username' , '$pwd' , '$email' , '$nowdate' , '$invitedbyuserid' , '$firstname' , '$lastname' , '100' );";
        bcsql_query( $sql );
        $newuserid = bcsql_insert_id();
        
        $s_username = $username;
        $s_password = $pwd;
        
        $_SESSION[ "s_username" ] = $s_username;
        $_SESSION[ "s_password" ] = $s_password;
        
        auth();
        
        if( !$invitation->MarkAsUsed( $newuserid ) ) {
            bc_die( "Error marking invitation as used" );
        }
        
        UserRegistration_Friends( $invitedbyuserid );
        UserRegistration_Messaging();
        UserRegistration_Bookmarks();
        
        return $newuserid;
    }
    
    function URegister( $userid ) {
        global $users;
        
        UserURegistration_Friends( $userid );
        UserURegistration_Messaging( $userid );
        
        $sql = "DELETE FROM `$users` WHERE `user_id`='$userid' LIMIT 1;";
    }
    
    function GetUserByUsername( $username ) {
        global $users;
        
        $username = bcsql_escape( $username );
        $sql = "SELECT * FROM `$users` WHERE `user_username`='$username' LIMIT 1;";
        $sqlr = bcsql_query( $sql );
        if ( bcsql_num_rows( $sqlr ) ) {
            $sqluser = bcsql_fetch_array( $sqlr );
            return New User( $sqluser );
        }
        // no such user found
        return false;
    }
    
    function GetUserByEmail( $email ) {
        global $users;
        
        $email = bcsql_escape( $email );
        $sql = "SELECT * FROM `$users` WHERE `user_email`='$email';";
        $sqlr = bcsql_query( $sql );
        if ( bcsql_num_rows( $sqlr ) == 1 ) {
            $sqluser = bcsql_fetch_array( $sqlr );
            return New User( $sqluser );
        }
        // no such user found, or more than one user with that email found
        return false;
    }
    
    function ValidUname( $username ) {
        // warning: use the === operator when you check for ValidUname values
        global $reserved;
        
        $validusernames = "#^[a-z][-a-z0-9]{2,}$#i";
        if( preg_match( $validusernames , $username ) == 0 ) {
            return -1; // invalid
        }
        if( in_array( $username , $reserved ) ) {
            return -2; // reserved
        }
        return true;
    }
    
    function GetUnameSuggestion( $username ) {
        $date = getdate();
        $suggestions = array( 
                $username . "isthebest", 
                $username . "isablogger", 
                $username . "lovesblogging",
                $username . $date['year'], 
                $username . "-" . $date['year'] , 
                $username . "iscool" ,
                $username . "-blogger" , 
                $username . "-is-cool" , 
                $username . "-is-the-best" , 
                $username . "-loves-blogging" );
                            
        $validsuggestions = array();
        while(list($index, $suggestion) = each($suggestions)) {
            $theuser = GetUserByUserName( $suggestion );
            if($theuser === false) {
                array_push($validsuggestions, $suggestion);
            }
        }
        if ( count( $validsuggestions ) ) {
            return $validsuggestions[ rand(0, count($validsuggestions) - 1) ];
        }
        return false;
    }
    
    class User {
        private $mId;
        private $mUsername;
        private $mPassword;
        private $mFailedLogins;
        private $mLastActive;
        private $mInvitedByUser;
        private $mInvitationsLeft;
        private $mBookmarksRootId; // @bookmarks
        private $mFriendsRootId; // @friends
        private $mGotFriendsRoot;
        private $mFriendsRoot;
        private $mEmail;
        private $mAvatar; // @media
        private $mBcAdmin;
        private $mBcDeveloper;
        private $mRegisterDate;
        private $mFirstName;
        private $mLastName;
        private $mCountryId; // @countries
        private $mGender;
        private $mDob;
        private $mWebsite;
        private $mMsn;
        private $mYahoo;
        private $mIcq;
        private $mJabber;
        private $mGtalk;
        private $mAim;
        private $mMobile;
        private $mPublicEmail;
        private $mPublicMsn;
        private $mPublicYahoo;
        private $mPublicIcq;
        private $mPublicJabber;
        private $mPublicGtalk;
        private $mPublicAim;
        private $mPublicBirthday;
        private $mPublicAge;
        private $mPublicMobile;
        private $mFavoriteMovie;
        private $mFavoriteBand;
        private $mFavoriteMusicGenre;
        private $mFavoriteGame;
        private $mFavoriteQuote;
        private $mHair;
        private $mEyes;
        private $mAboutMe;
        private $mBlogs;
        private $mBlogsLoaded;
        private $mHasBlogs;
        private $mPin;
        private $mAllowEmailBlogging;
        private $mAllowSMSBlogging;
        
        public function Su() {
            global $anonymous;
            
            $_SESSION[ "s_username" ] = $this->mUsername;
            $_SESSION[ "s_password" ] = $this->mPassword;
            auth();
            return !$anonymous;
        }
        public function HasPin() {
            return $this->mPin != "";
        }
        public function ClearPin() {
            global $users;
            
            $sql = "UPDATE `$users` SET `user_pin`='' WHERE `user_id`='" . $this->mId. "' LIMIT 1;";
            bcsql_query( $sql );
        }
        public function CheckPin( $pin ) {
            return md5( $pin ) == $this->mPin;
        }
        public function SetPin( $newpin ) {
            global $users;
            
            $newpin = md5( $newpin );
            $sql = "UPDATE `$users` SET `user_pin`='$newpin' WHERE `user_id`='" . $this->mId. "' LIMIT 1;";
            bcsql_query( $sql );
        }
        public function Id() {
            return $this->mId;
        }
        public function Username() {
            return $this->mUsername;
        }
        public function PasswordHash() {
            // for checking use CheckPassword instead
            return $this->mPassword;
        }
        public function CheckPassword( $password ) {
            return md5( $password ) == $this->mPassword;
        }
        public function SetPassword( $newpassword ) {
            global $users;
            
            if( strlen( $newpassword ) <= 3 ) {
                bc_die( "User::SetPassword(): Password must be at least four characters long!" );
            }
            $newpassword = md5( $newpassword );
            $sql = "UPDATE `$users` SET `user_password`='$newpassword' WHERE `user_id`='" . $this->mId . "' LIMIT 1;";
            bcsql_query( $sql );
            $this->mPassword = $newpassword;
        }
        public function FailedLogins() {
            return $this->mFailedLogins;
        }
        public function AddFailedLogin() {
            global $users;
            
            $sql = "UPDATE `$users` SET `user_failedlogins`= `user_failedlogins` + 1 WHERE `user_id`='" . $this->mId. "' LIMIT 1;";
            bcsql_query( $sql );
            $this->mFailedLogins++;
        }
        public function RemoveFailedLogins() {
            global $users;
            
            $sql = "UPDATE `$users` SET `user_failedlogins`= 0 WHERE `user_id`='" . $this->mId. "' LIMIT 1;";
            bcsql_query( $sql );
            $this->mFailedLogins = 0;
        }
        public function LastActive() {
            return $this->mLastActive;
        }
        public function InvitedByUser() {
            return $this->mInvitedByUser;
        }
        public function InvitationsLeft() {
            return $this->mInvitationsLeft;
        }
        public function BookmarksRootFolderId() {
            return $this->mBookmarksRootId;
        }
        public function FriendsRootFolderId() {
            return $this->mFriendsRootId;
        }
        /* public function Friends() { // @/modules/friends/friends.php::FriendFolder
            if ( !$this->mGotFriendsRoot ) {
                $this->mFriendsRoot = &New FriendFolder( $this->mFriendsRootId );
                $this->mGotFriendsRoot = true;
            }
            return $this->mFriendsRoot;
        } */
        public function Email() {
            return $this->mEmail;
        }
        public function SetEmail( $value ) {
            if( !ValidEmail( $value ) ) {
                return false;
            }
            return $this->mSet( "email" , $value );
        }
        public function Avatar() {
            return $this->mAvatar;
        }
        public function IsAdmin() {
            return $this->mBcAdmin;
        }
        public function IsDeveloper() {
            return $this->mBcDeveloper;
        }
        public function RegisterDate() {
            return $this->mRegisterDate;
        }
        public function FirstName() {
            return $this->mFirstName;
        }
        public function SetFirstName( $value ) {
            return $this->mSet( "firstname" , $value );
        }
        public function LastName() {
            return $this->mLastName;
        }
        public function SetLastName( $value ) {
            return $this->mSet( "lastname" , $value );
        }
        public function CountryId() {
            return $this->mCountryId;
        }
        public function Gender() {
            return $this->mGender;
        }
        public function SetGender( $value ) {
            switch( $value ) {
                case 'male':
                case 'female':
                    return $this->mSet( "gender" , $value );
            }
            return false;
        }
        public function Dob() {
            return $this->mDob;
        }
        public function SetDob( $value ) {
            return $this->mSet( "dob" , $value );
        }
        public function Website() {
            return $this->mWebsite;
        }
        public function SetWebsite( $value ) {
            return $this->mSet( "website" , $value );
        }
        public function Msn() {
            return $this->mMsn;
        }
        public function SetMsn( $value ) {
            return $this->mSet( "msn" , $value );
        }
        public function Yahoo() {
            return $this->mYahoo;
        }
        public function SetYahoo( $value ) {
            return $this->mSet( "yahoo" , $value );
        }
        public function Icq() {
            return $this->mIcq;
        }
        public function SetIcq( $value ) {
            return $this->mSet( "icq" , $value );
        }
        public function Jabber() {
            return $this->mJabber;
        }
        public function SetJabber( $value ) {
            return $this->mSet( "jabber" , $value );
        }
        public function Gtalk() {
            return $this->mGtalk;
        }
        public function SetGTalk( $value ) {
            return $this->mSet( "gtalk" , $value );
        }
        public function Aim() {
            return $this->mAim;
        }
        public function SetAim( $value ) {
            return $this->mSet( "aim" , $value );
        }
        private function mSetPub( $pubfield , $value ) {
            switch( $value ) {
                case "private":
                case "public":
                case "friends":
                case "ffriends":
                    return $this->mSet( "public" . $pubfield , $value );
                default:
                    return false;
            }
        }
        public function PublicEmail() {
            return $this->mPublicEmail;
        }
        public function SetPublicEmail( $value ) {
            return $this->mSetPub( "email" , $value );
        }
        public function PublicMsn() {
            return $this->mPublicMsn;
        }
        public function SetPublicMsn( $value ) {
            return $this->mSetPub( "msn" , $value );
        }
        public function PublicYahoo() {
            return $this->mPublicYahoo;
        }
        public function SetPublicYahoo( $value ) {
            return $this->mSetPub( "yahoo" , $value );
        }
        public function PublicIcq() {
            return $this->mPublicIcq;
        }
        public function SetPublicIcq( $value ) {
            return $this->mSetPub( "icq" , $value );
        }
        public function PublicJabber() {
            return $this->mPublicJabber;
        }
        public function SetPublicJabber( $value ) {
            return $this->mSetPub( "jabber" , $value );
        }
        public function PublicGtalk() {
            return $this->mPublicGtalk;
        }
        public function SetPublicGtalk( $value ) {
            return $this->mSetPub( "gtalk" , $value );
        }
        public function PublicAim() {
            return $this->mPublicAim;
        }
        public function SetPublicAim( $value ) {
            return $this->mSetPub( "aim" , $value );
        }
        public function PublicBirthday() {
            return $this->mPublicBirthday;
        }
        public function SetPublicBirthday( $value ) {
            return $this->mSetPub( "birthday" , $value );
        }
        public function PublicAge() {
            return $this->mPublicAge;
        }
        public function SetPublicAge( $value ) {
            return $this->mSetPub( "age" , $value );
        }
        public function PublicMobile() {
            return $this->mPublicMobile;
        }
        public function SetPublicMobile( $value ) {
            return $this->mSetPub( "mobile" , $value );
        }
        public function FavoriteMovie() {
            return $this->mFavoriteMovie;
        }
        public function SetFavoriteMovie( $value ) {
            return $this->mSet( "favoritemovie" , $value );
        }
        public function FavoriteBand() {
            return $this->mFavoriteBand;
        }
        public function SetFavoriteBand( $value ) {
            return $this->mSet( "favoriteband" , $value );
        }
        public function FavoriteMusicGenre() {
            return $this->mFavoriteMusicGenre;
        }
        public function SetFavoriteMusicGenre( $value ) {
            return $this->mSet( "favoritemusicgenre" , $value );
        }
        public function FavoriteGame() {
            return $this->mFavoriteGame;
        }
        public function SetFavoriteGame( $value ) {
            return $this->mSet( "favoritegame" , $value );
        }
        public function FavoriteQuote() {
            return $this->mFavoriteQuote;
        }
        public function SetFavoriteQuote( $value ) {
            return $this->mSet( "favoritequote" , $value );
        }
        public function Hair() {
            return $this->mHair;
        }
        public function SetHair( $value ) {
            return $this->mSet( "hair" , $value );
        }
        public function Eyes() {
            return $this->mEyes;
        }
        public function SetEyes( $value ) {
            return $this->mSet( "eyes" , $value );
        }
        public function AboutMe() {
            return $this->mAboutMe;
        }
        public function SetAboutMe( $values ) {
            return $this->mSet( "aboutme" , $values );
        }
        public function AllowEmailBlogging() {
            return $this->mAllowEmailBlogging;
        }
        public function SetAllowEmailBlogging( $value ) {
            if( $value === true || $value === false ) {
                return $this->mSet( "allowemailblogging" , $value ? "yes" : "no" );
            }
            bc_die( "SetAllowEmailBlogging should take one boolean parameter" );
        }
        public function AllowSMSBlogging() {
            return $this->mAllowSMSBlogging;
        }
        public function SetAllowSMSBlogging( $value ) {
            if( $value === true || $value === false ) {
                return $this->mSet( "allowsmsblogging" , $value ? "yes" : "no" );
            }
            bc_die( "SetAllowSMSBlogging should take one boolean parameter" );
        }
        public function AssignInvitations( $number_of_new_invitations ) {
            // assign the given number of invitations to the user
            global $users;
            
            if( !is_numeric( $number_of_new_invitations ) ) {
                bc_die( "AssignedInvitations should be a number" );
            }
            if( $number_of_new_invitations > 0 ) {
                $sql = "UPDATE `$users` 
                        SET `$users`.`user_invitationsleft`=(`$users`.`user_invitationsleft`+$number_of_new_invitations)
                        WHERE `$users`.`user_id`='" . $this->mId . "' LIMIT 1;";
                bcsql_query( $sql );
                $this->mInvitationsLeft += $number_of_new_invitations;
                return true;
            }
            return false;
        }
        public function ReduceInvitations() {
            global $users;
            
            if( $this->mInvitationsLeft ) {
                $sql = "UPDATE `$users` 
                        SET `$users`.`user_invitationsleft`=(`$users`.`user_invitationsleft`-1)
                        WHERE `$users`.`user_id`='" . $this->mId . "' LIMIT 1;";
                $this->mInvitationsLeft--;
                bcsql_query( $sql );
                return true;
            }
            else {
                return false;
            }
        }
        public function &Blogs() {
            global $blogs;
            global $permissions;
            
            if ( $this->mBlogsLoaded ) {
                if ( $this->mHasBlogs ) {
                    return $this->mBlogs;
                }
                else {
                    return false;
                }
            }
            else {
                $sql = "SELECT
                            `$blogs`.*
                        FROM
                            `$blogs`,`$permissions`
                        WHERE
                            `$permissions`.`permission_userid`='" . $this->mId . "' AND
                            `$blogs`.`blog_id`=`$permissions`.`permission_blogid` AND
                            `$blogs`.`blog_active`='yes'
                        ORDER BY 
                            `$blogs`.`blog_title` ASC;";
                $sqlr = bcsql_query( $sql );
                if ( bcsql_num_rows( $sqlr ) ) {
                    $this->mHasBlogs = true;
                    $this->mBlogs = New Blogs( $sqlr );
                    return $this->mBlogs;
                }
                else {
                    $this->mHasBlogs = false;
                    return false;
                }
            }
        }
        // construct function; you can either pass a userid
        // or a mysql fetched array to it (in case you want to
        // create a user class for a specific user (e.g. specific username,
        // etc.)
        public function User( $construct ) {
            global $users;
            
            if ( is_array( $construct ) ) {
                $fetched_array = $construct;
            }
            else {
                if( !is_numeric( $construct ) ) {
                    bc_die( "User class constructor parameter should either be a fetched array or an integer representing the user id" );
                }
                $construct = bcsql_escape( $construct );
                $sql = "SELECT
                            `$users`.*
                        FROM
                            `$users`
                        WHERE
                            `$users`.`user_id`='" . $construct . "'
                        LIMIT 1;";
                $sqlr = bcsql_query( $sql );
                if ( bcsql_num_rows( $sqlr ) ) {
                    $fetched_array = bcsql_fetch_array( $sqlr );
                }
                else {
                    $fetched_array = Array();
                }
            }
            if( count( $fetched_array ) ) {
                $this->mId = $fetched_array[ "user_id" ];
                $this->mUsername = $fetched_array[ "user_username" ];
                $this->mPassword = $fetched_array[ "user_password" ];
                $this->mFailedLogins = $fetched_array[ "user_failedlogins" ];
                $this->mPin = $fetched_array[ "user_pin" ];
                $this->mLastActive = $fetched_array[ "user_lastactive" ];
                $this->mInvitedByUser = $fetched_array[ "user_invitedbyuserid" ];
                $this->mInvitationsLeft = $fetched_array[ "user_invitationsleft" ];
                $this->mFriendsRootId = $fetched_array[ "user_friendsrootfolderid" ];
                if( !ValidId( $this->mFriendsRootId ) ) {
                    // invalid rootid
                    $this->mGotFriendsRoot = true;
                    $this->mFriendsRoot = false; // store false instead of an object
                }
                else {
                    $this->mGotFriendsRoot = false;
                }
                $this->mBookmarksRootFolderId = $fetched_array[ "user_bookmarksrootfolderid" ];
                $this->mEmail = $fetched_array[ "user_email" ];
                $this->mAvatar = $fetched_array[ "user_defaultavatar" ];
                $this->mBcAdmin = $fetched_array[ "user_bcadmin" ] >= 1000;
                $this->mBcDeveloper = $fetched_array[ "user_bcadmin" ] >= 500;
                $this->mRegisterDate = $fetched_array[ "user_registerdate" ];
                $this->mFirstName = $fetched_array[ "user_firstname" ];
                $this->mLastName = $fetched_array[ "user_lastname" ];
                $this->mCountryId = $fetched_array[ "user_countryid" ];
                $this->mGender = $fetched_array[ "user_gender" ];
                $this->mDob = $fetched_array[ "user_dob" ];
                $this->mWebsite = $fetched_array[ "user_website" ];
                $this->mMsn = $fetched_array[ "user_msn" ];
                $this->mYahoo = $fetched_array[ "user_yahoo" ];
                $this->mIcq = $fetched_array[ "user_icq" ];
                $this->mJabber = $fetched_array[ "user_jabber" ];
                $this->mGtalk = $fetched_array[ "user_gtalk" ];
                $this->mAim = $fetched_array[ "user_aim" ];
                $this->mPublicEmail = $fetched_array[ "user_publicemail" ];
                $this->mPublicMsn = $fetched_array[ "user_publicmsn" ];
                $this->mPublicIcq = $fetched_array[ "user_publicicq" ];
                $this->mPublicJabber = $fetched_array[ "user_publicjabber" ];
                $this->mPublicGtalk = $fetched_array[ "user_publicgtalk" ];
                $this->mPublicAim = $fetched_array[ "user_publicaim" ];
                $this->mPublicBirthday = $fetched_array[ "user_publicbirthday" ];
                $this->mPublicAge = $fetched_array[ "user_publicage" ];
                $this->mPublicMobile = $fetched_array[ "user_publicmobile" ];
                $this->mFavoriteMovie = $fetched_array[ "user_favoritemovie" ];
                $this->mFavoriteBand = $fetched_array[ "user_favoriteband" ];
                $this->mFavoriteMusicGenre = $fetched_array[ "user_favoritemusicgenre" ];
                $this->mFavoriteGame = $fetched_array[ "user_favoritegame" ];
                $this->mFavoriteQuote = $fetched_array[ "user_favoritequote" ];
                $this->mHair = $fetched_array[ "user_hair" ];
                $this->mEyes = $fetched_array[ "user_eyes" ];
                $this->mAboutMe = $fetched_array[ "user_aboutme" ];
                $this->mAllowEmailBlogging = $fetched_array[ "user_allowemailblogging" ];
                $this->mAllowSMSBlogging = $fetched_array[ "user_allowsmsblogging" ];
            }
        }
        private function mSet( $fieldname , $fieldvalue ) {
            // NEVER pass $fieldname dynamic, only static values
            global $users;
            
            // bc_die( $fieldname . " = " . $fieldvalue );
            //bug-fix: users can use html tags in some contacts, quotes etc
            $fieldvalue = strip_tags( $fieldvalue );
            $fieldvalue = bcsql_escape( $fieldvalue );
            $sql = "UPDATE `$users` SET `user_$fieldname`='$fieldvalue' WHERE `user_id`='" . $this->mId . "' LIMIT 1;";
            bcsql_query( $sql );
            return true;
        }
        public function IsFriend( $userid ) {
            // returns true if user with $userid is a friend of $this
            global $friends;
            global $friendfolders;
            
            if( $userid == $this->mId ) {
                return true;
            }
            
            $userid = bcsql_escape( $userid );
            $sql = "SELECT 
                        `$friends`.`friend_userid`
                    FROM 
                        `$friends`,`$friendfolders` 
                    WHERE
                        `$friendfolders`.`ffolder_userid`='" . $this->mId . "' AND
                        `$friends`.`friend_parentfolderid`=`$friendfolders`.`ffolder_id` AND
                        `$friends`.`friend_userid`='" . $userid . "'
                    LIMIT 1;";
            
            $sqlr = bcsql_query( $sql );
            return bcsql_num_rows( $sqlr ) == 1;
        }
        public function IsFFriend( $userid ) {
            // returns true if user with $userid is a friend of a friend of $this or a friend of $this
            global $friends;
            global $friendfolders;
            
            if( $userid == $this->mId ) {
                return true;
            }

            // there MUST be a faster way to do that (one query)
            $userid = bcsql_escape( $userid );
            $sql = "SELECT 
                        `$friends`.`friend_userid`
                    FROM 
                        `$friends`,`$friendfolders` 
                    WHERE
                        `$friendfolders`.`ffolder_userid`='" . $this->mId . "' AND
                        `$friends`.`friend_parentfolderid`=`$friendfolders`.`ffolder_id`;";
            
            $sqlr = bcsql_query( $sql );
            while( $thisfriend = bcsql_fetch_array( $sqlr ) ) {
                if( $thisfriend[ "friend_userid" ] == $userid ) {
                    return true;
                }
                else {
                    $thisfriendi = New User( $thisfriend[ "friend_userid" ] );
                    if( $thisfriendi->IsFriend( $userid ) ) {
                        return true;
                    }
                }
            }
            return false;
        }
    }
    
    function GetUsers( $start = 0, $limit = 21 ) {
        global $users;
        
        $sql = "SELECT
                    `$users`.*
                FROM
                    `$users`
                ORDER BY
                    `$users`.`user_id` ASC
                LIMIT $start, $limit;";
        return New Users( bcsql_query( $sql ) );
    }
    
    class Users {
        var $mUser;
        var $mLength;
        
        public function User( $index ) {
            if ( $index < $this->mLength )
                return $this->mUser[ $index ];
            else
                return false;
        }
        public function Length() {
            return $this->mLength;
        }
        public function Users( $sql_resource ) {
            $this->mLength = 0;
            while ( $thisuser = bcsql_fetch_array( $sql_resource ) ) {
                $this->mUser[ $this->mLength ] = New User( $thisuser );
                ++$this->mLength;
            }
        }
    }
?>