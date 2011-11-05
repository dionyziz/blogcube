<?php
    /* 
    Module: Messaging
    File: /modules/messaging/messaging.php
    Developer: feedWARd
    */
    include "modules/module.php";
    /*  SUGGESTIONS:
        o allow the users to create groups of people, so instead of continuously inserting the users to
          which  they want to send a message, they can just select the group. There can be also predefined
          groups (e.g. friends group will correspond to friend list). --feedWARd
          that's what friends are for. See /modules/friends. They are grouped as well. We'll take that into
          account when writting server-side code. --dionyziz
        o spam filter (report as spam) --feedWARd
          not a bad idea. If you want, take some time to work on it --dionyziz
        o send sms on new message (?) --feedWARd
          already suggested by d3nnn1z (see Talk:Roadmap). It means that when you receive a message
          from a friend, an SMS message is sent to your mobile phone saying that you have a new message
          from this friend of yours. We'll need to work on an SMS system first, which will probably be
          done on or after Cairo. --dionyziz
    */
    
    function UserURegistration_Messaging( $userid ) {
        // invoked when user with id $userid is being deleted
        $UserRoot = New MessageFolder(GetUserRoot($userid));
        $UserRoot->RemoveFolder();
    }
    
    
    function UserRegistration_Messaging() {
        /* When a user is registered, 6 folders must be created:
            Root
            |-Incoming
            |-Sent
            |-Trash
            |-Drafts
            |-Spam
        */
        global $user; // globally holds an instance of the user class for the newly created user
        global $messagefolders;
        global $messages;
        
        $userid = $user->Id();
        $username = $user->Username();
        bcsql_query("INSERT INTO `$messagefolders` (`mfolder_name`,`mfolder_userid`,`mfolder_parid`,`mfolder_special`,`mfolder_expanded`) VALUES ('Root','$userid','0','none','yes')");
        $RootID = bcsql_insert_id();
        bcsql_query("INSERT INTO `$messagefolders` (`mfolder_name`,`mfolder_userid`,`mfolder_parid`,`mfolder_special`) VALUES ('Incoming','$userid','$RootID','incoming')");
        bcsql_query("INSERT INTO `$messagefolders` (`mfolder_name`,`mfolder_userid`,`mfolder_parid`,`mfolder_special`) VALUES ('Sent','$userid','$RootID','sent')");
        bcsql_query("INSERT INTO `$messagefolders` (`mfolder_name`,`mfolder_userid`,`mfolder_parid`,`mfolder_special`) VALUES ('Trash','$userid','$RootID','trash')");
        bcsql_query("INSERT INTO `$messagefolders` (`mfolder_name`,`mfolder_userid`,`mfolder_parid`,`mfolder_special`) VALUES ('Drafts','$userid','$RootID','drafts')");
        bcsql_query("INSERT INTO `$messagefolders` (`mfolder_name`,`mfolder_userid`,`mfolder_parid`,`mfolder_special`) VALUES ('Spam','$userid','$RootID','spam')");
        // send a welcome message to the user
        $temptstamp = NowDate();
        $subject = "Welcome to BlogCube";
        $text = "
           Hello " . $username . ", and welcome to BlogCube. We'd like to thank you for agreeing to help us 
           test BlogCube. 
           
           But, first off, a few words about what BlogCube is... 
           BlogCube is a blogging service that allows you to create your own personal journal or
           so-called \"blog\" and share it with your friends. BlogCube focuses on ease of use,
           while it includes many advanced features for experienced users.
           
           To start, please take some time to explore some of the features that BlogCube offers. 
           If you get stuck, don't hesitate to contact an administrator. 
           
           BlogCube is currently in its first development stages, so you may experience an unstable
           behaviour now and then. If you see anything weird, or find a problem, we would appreciate it
           if you contacted an administrator to report the problem. 
           
           In case you come up with a cool idea or something you'd like to see implemented in BlogCube,
           we'd be happy to hear from you!
           
           Have fun,
           
           The BlogCube Team
           
           p.s. You can login and access your account at any time by visiting $systemurl
        ";
        $subject = bcsql_escape( $subject );
        $text = bcsql_escape( $text );
        bcsql_query("INSERT INTO `$messages` (`message_senderid`,`message_subject`,`message_text`,`message_date`) VALUES ('0','$subject','$text','$temptstamp')");
        $curmsgid = bcsql_insert_id();
        $curmsg = New Message($curmsgid);
        $curmsg->AddRecipient($userid);
        $curmsg->SendMessage();
    }
    
    final class MessageFoldersTree extends Tree {
        private $mParent;
        private $mFetchArray;
        
        public function TreeParent() {
            return $this->mParent;
        }
        public function SetParent( $parent ) {
            $this->mParent = $parent;
        }
        public function CommentsTree() {
            $this->Tree();
        }
        
        public function SetFolder($farray) {
            $this->mFetchArray = New MessageFolder($farray);
        }
        
        public function GetFolder() {
            return $this->mFetchArray;
        }
    }
    
    function Messaging_FetchUserTree( $userid ) {
        global $messagefolders;
        global $mfolder_user;
        
        if ( !ValidId( $userid ) ) {
            bc_die( "Invalid userid passed to Messaging_FetchUserTree ($userid)" );
        }
        $sql = "SELECT
                mf.*, COUNT(mfu.`mfuser_foldid`) AS mcount, COUNT(children.`mfolder_parid`) AS fcount
            FROM
                ( `$messagefolders` AS mf LEFT JOIN `$mfolder_user` AS mfu
                    ON mfu.`mfuser_foldid`=mf.`mfolder_id` ) 
                LEFT JOIN `$messagefolders` AS children ON
                    children.`mfolder_parid`=mf.`mfolder_id`
            WHERE
                mf.`mfolder_userid`='$userid'
            GROUP BY
                mf.`mfolder_id`
            ORDER BY
                mf.`mfolder_id` ASC;";
        $res = bcsql_query( $sql );
        $allfolders = array();
        $allfolders[0] = New MessageFoldersTree();
        while ( $folder = $res->FetchArray() ) {
            $folderid = $folder[ 'mfolder_id' ];
            $allfolders[ $folderid ] = New MessageFoldersTree(); // construct using fetched array
            $allfolders[ $folderid ]->SetFolder($folder);
            $FoldersSQLArrays[ $folderid ] = $folder;
        }
        $folderids = array_keys( $FoldersSQLArrays );
        for( $i = 0 ; $i < count( $folderids ) ; $i++ ) {
            $folderid = $folderids[ $i ];
            $sqlarray = $FoldersSQLArrays[ $folderid ];
            $parentid = $sqlarray[ "mfolder_parid" ];
            $allfolders[ $parentid ]->AttachChild( $allfolders[ $folderid ] );
            $allfolders[ $folderid ]->SetParent( $allfolders[ $parentid ] );
        }
        return $allfolders;
    }
    
    function GetUserRoot( $userid ) {
        global $messagefolders;
        
        $result = bcsql_query("SELECT `mfolder_id` FROM `$messagefolders` WHERE `mfolder_userid` = '$userid' AND `mfolder_parid` = '0' LIMIT 1");
        $RootAttr = bcsql_fetch_array($result);
        if ( empty($RootAttr["mfolder_id"]) ) { return false; }
        return $RootAttr["mfolder_id"];
    }
    
    function GetUserSpecialFolder($userid,$special) {
        global $messagefolders;
        
        /* $special can be:
            'incoming'
            'sent'
            'trash'
            'drafts'
            'spam'
        */
        $result = bcsql_query("SELECT `mfolder_id` FROM `$messagefolders` WHERE `mfolder_userid` = '$userid' AND `mfolder_special` = '$special'");
        $SpecialAttr = bcsql_fetch_array($result);
        return $SpecialAttr["mfolder_id"];
    }
    
    function GetNewMessages($userid) {
        global $mfolder_user;
        global $messagefolders;
        global $user;
        
        $userid = $user->Id();
        $allnmcount = 0;
        $result = bcsql_query("SELECT COUNT(*) AS nmcount, mfolder_id FROM `$messagefolders`, `$mfolder_user` WHERE `mfuser_read`='no' AND `mfuser_foldid`=`mfolder_id` AND `mfolder_userid`='$userid' GROUP BY `mfolder_id`");
        $FetchedNewMsgs = bcsql_fetch_array($result);
        return $FetchedNewMsgs["nmcount"];
        /*$allfolders = bcsql_query("SELECT * FROM `$messagefolders` WHERE `mfolder_userid` = '$userid'");
        while ($curfold = bcsql_fetch_array($allfolders)) {
            $curfoldid = $curfold["mfolder_id"];
            $newmsgs = bcsql_query("SELECT COUNT(*) AS nmcount FROM `$mfolder_user` WHERE `mfuser_read` = 'no' AND `mfuser_foldid` = '$curfoldid'");
            $FetchedNewMsgs = bcsql_fetch_array($newmsgs);
            $allnmcount += $FetchedNewMsgs["nmcount"];
        }*/
    }
    
    function CreateMessage($subject,$text,$type) {
        global $user;
        global $messages;
        global $mfolder_user;
        
        if (( $type > 2 ) || ( $type < 1 )) { return false; }
        $subject = bcsql_escape($subject);
        $text = bcsql_escape($text);
        $temptstamp = NowDate();
        $userid = $user->Id();
        bcsql_query("INSERT INTO `$messages` (`message_senderid`,`message_subject`,`message_text`,`message_date`) VALUES ('$userid','$subject','$text','$temptstamp')");
        if ( $type == 1 ) { $s = GetUserSpecialFolder($user->Id(), 'drafts'); }
        else { $s = GetUserSpecialFolder($user->Id(), 'sent'); }
        $MsgID = bcsql_insert_id();
        bcsql_query("INSERT INTO `$mfolder_user` (`mfuser_msgid`,`mfuser_foldid`,`mfuser_read`) VALUES ('$MsgID','$s','yes')");
        return $MsgID;
    }
    
    function GetNewMsgIDs($userid) {
        global $messagefolders;
        global $mfolder_user;
        
        $newmsgs = Array();
        $allfolders = bcsql_query("SELECT * FROM `$messagefolders` WHERE `mfolder_userid` = '$userid'");
        while ($curfold = bcsql_fetch_array($allfolders)) {
            $curfoldid = $curfold["mfolder_id"];
            $result = bcsql_query("SELECT * FROM `$mfolder_user` WHERE `mfuser_read` = 'no' AND `mfuser_foldid` = '$curfoldid'");
            while ($FetchedNewMsgs = bcsql_fetch_array($result)) {
                $newmsgs[] = $FetchedNewMsgs["mfuser_msgid"];
            }
        }
        return $newmsgs;
    }
    
    class MessageFolder {
        var $mf_id;
        var $mf_name;
        var $mf_userid;
        var $mf_parid;
        var $mf_special;
        var $mf_expanded;
        private $mMessagesCount;
        private $mMessagesUnreadCount;
        private $mFoldersCount;
        var $errtext;
        var $sqlf_result;
        var $sqlf_num_rows;
        var $sqlf_pointer;
        var $sqlm_result;
        var $sqlm_num_rows;
        var $sqlm_pointer;
        
        public function MessageFolder( $construct ) {
            global $user;
            global $messagefolders;
            
            if ( is_array( $construct ) ) {
                $MsgFolderAttr = $construct;
            }
            else if ( ValidId( $construct ) ) {
                $result = bcsql_query(
                        "
                        SELECT 
                            * 
                        FROM 
                            `$messagefolders` 
                        WHERE 
                            `mfolder_id` = '$construct' 
                        LIMIT 1;"
                    );
                $MsgFolderAttr = bcsql_fetch_array($result);
            }
            $this->mf_userid = $MsgFolderAttr["mfolder_userid"];
            $this->mf_parid = $MsgFolderAttr["mfolder_parid"];
            $this->mf_name = $MsgFolderAttr["mfolder_name"];
            $this->mf_special = $MsgFolderAttr["mfolder_special"];
            $this->mf_expanded = $MsgFolderAttr["mfolder_expanded"];
            if ( isset( $MsgFolderAttr[ 'mcount' ] ) ) {
                $this->mMessagesCount = $MsgFolderAttr[ 'mcount' ];
            }
            if ( isset( $MsgFolderAttr[ 'fcount' ] ) ) {
                $this->mFoldersCount = $MsgFolderAttr[ 'fcount' ];
            }
            if ( isset( $MsgFolderAttr[ 'mucount' ] ) ) {
                $this->mMessagesUnreadCount = $MsgFolderAttr[ 'mucount' ];
            }
            $this->mf_id = $MsgFolderAttr["mfolder_id"];
        }
        
        function FolderName() {
            return $this->mf_name;
        }
        
        function FolderOwnerID() {
            return $this->mf_userid;
        }
        
        function FolderParentID() {
            return $this->mf_parid;
        }
        
        function FolderID() {
            return $this->mf_id;
        }
        
        function FolderSpecial() {
            return $this->mf_special;
        }
        
        function AddFolder($name) {
            global $messagefolders;
            
            $name = bcsql_escape($name);
            $result = bcsql_query("INSERT INTO `$messagefolders` (`mfolder_name`,`mfolder_userid`,`mfolder_parid`,`mfolder_special`) VALUES ('$name','$this->mf_userid','$this->mf_id','none')");
        }
        
        function RemoveFolder() {
            global $mfolder_user;
            global $messagefolders;
            $queue = array($this->mf_id);
            while ( count($queue) ) {
                $tid = $queue[count($queue)-1];
                $a = &New MessageFolder($tid);
                bcsql_query("DELETE FROM `$mfolder_user` WHERE `mfuser_foldid` = '$this->mf_id'");
                while ( $b = $a->GetFolder() ) {
                    array_push($queue,$b);
                }
                if ( $a->CountFolders() == 0 ) {
                    bcsql_query("DELETE FROM `$messagefolders` WHERE `mfolder_id` = '$tid' LIMIT 1");        
                    array_pop($queue);
                }
            }
        }
        
        function CountFolders() {
            global $messagefolders;
            
            if ( !isset( $this->mFoldersCount ) ) {
                $result = bcsql_query("SELECT COUNT(*) AS fcount FROM `$messagefolders` WHERE `mfolder_parid` = '$this->mf_id'");
                $FolderCount = bcsql_fetch_array($result);
                $this->mFoldersCount = $FolderCount["fcount"];
            }
            return $this->mFoldersCount;
        }
        
        public function CountMessages() {
            global $mfolder_user;
            
            if ( !isset( $this->mMessagesCount ) ) {
                $result = bcsql_query("SELECT COUNT(*) AS mcount FROM `$mfolder_user` WHERE `mfuser_foldid` = '" . $this->mf_id . "'");
                $MessageCount = bcsql_fetch_array($result);
                $this->mMessagesCount = $MessageCount["mcount"];
            }
            return $this->mMessagesCount;
        }
        
        function RenameFolder($newname) {
            global $messagefolders;
            
            $newname = bcsql_escape($newname);
            $result = bcsql_query("UPDATE `$messagefolders` SET `mfolder_name` = '$newname' WHERE `mfolder_id` = '$this->mf_id'");
            $this->mf_name = $newname;
        }
        
        function MoveToFolder($id) {
            global $messagefolders;
            
            $result = bcsql_query("SELECT `mfolder_id` FROM `$messagefolders` WHERE `mfolder_id` = '$id' LIMIT 1");
            $CheckIfExists = bcsql_num_rows($result);
            if ( !$CheckIfExists ) {
                $this->errtext = "The specified folder does not exist";
                return false;
            }
            else {
                bcsql_query("UPDATE `$messagefolders` SET `mfolder_parid` = '$id' WHERE `mfolder_id` = '$this->mf_id' LIMIT 1");
                $this->parid = $id;
            }
        }
        
        function GetFolder() {
            global $messagefolders;
            
            if ( empty($this->sqlf_result) ) {
                $this->sqlf_result = bcsql_query("SELECT * FROM `$messagefolders` WHERE `mfolder_parid` = '$this->mf_id' ORDER BY `mfolder_name` ASC");
                $this->sqlf_num_rows = bcsql_num_rows($this->sqlf_result);
            }
            if ($this->sqlf_pointer < $this->sqlf_num_rows) {
                $CurFoldAttr = bcsql_fetch_array($this->sqlf_result);
                $this->sqlf_pointer++;
                return $CurFoldAttr["mfolder_id"];
            }
            else {
                $this->errtext = "All folders have been returned";
                return false;
            }
        }
        
        function GetErrorText($clear = true) {
            $a = $this->errtext;
            if ( $clear == true ) { $this->errtext = ""; }
            return $a;
        }
        
        function CountUnread() {
            global $mfolder_user;
            
            if ( !isset( $this->mMessagesUnreadCount ) ) {
                $result = bcsql_query("SELECT COUNT(*) AS mucount FROM `$mfolder_user` WHERE `mfuser_foldid` = '$this->mf_id' AND `mfuser_read` = 'no'");
                $MsgUnreadCount = bcsql_fetch_array($result);
                $this->mMessagesUnreadCount = $MsgUnreadCount["mucount"];
            }
            return $this->mMessagesUnreadCount;
        }
        
        function GetMessage() {
            global $mfolder_user;
            
            if ( empty($this->sqlm_result) ) {
                $this->sqlm_result = bcsql_query("SELECT * FROM `$mfolder_user` WHERE `mfuser_foldid` = '$this->mf_id'");
                $this->sqlm_num_rows = bcsql_num_rows($this->sqlm_result);
            }
            if ($this->sqlm_pointer < $this->sqlm_num_rows) {
                $CurMsgAttr = bcsql_fetch_array($this->sqlm_result);
                $this->sqlm_pointer++;
                return $CurMsgAttr["mfuser_msgid"];
            }
            else {
                $this->errtext = "All messages have been returned";
                return false;
            }
        }
        
        function IsExpanded() {
            return $this->mf_expanded;
        }
        
        function SetExpanded($expvalue = 'yes') {
            global $messagefolders;
            
            if (( $expvalue != 'yes' ) && ( $expvalue != 'no' )) {
                return false;
            }
            bcsql_query("UPDATE `$messagefolders` SET `mfolder_expanded` = '" . $expvalue . "' WHERE `mfolder_id` = '" . $this->mf_id . "'");
            $this->mf_expanded = $expvalue;
            return true;
        }
    }
    
    class Message {
        var $m_id;
        var $m_senderid;
        var $m_subject;
        var $m_text;
        var $m_date;
        var $sql_recip_result;
        var $sql_recip_num_rows;
        var $sql_recip_pointer;
        var $m_foldid;
        
        function MessageFolderID() {
            return $this->m_foldid;
        }
        
        function MessageID() {
            return $this->m_id;
        }
        
        function MessageSenderID() {
            return $this->m_senderid;
        }
        
        function MessageText() {
            return $this->m_text;
        }
        
        function MessageSubject() {
            return $this->m_subject;
        }
        
        function MessageDate() {
            return $this->m_date;
        }
        
        function Message($id) {
            global $messages;
            global $user;
            
            $userid = $user->Id();
            $sql_result = bcsql_query("SELECT * FROM `$messages` WHERE `message_id` = '$id' LIMIT 1");
            $CheckIfExists = bcsql_num_rows($sql_result);
            if ( $CheckIfExists ) {
                $MessageAttr = bcsql_fetch_array($sql_result);
                $this->m_id = $MessageAttr["message_id"];
                $this->m_senderid = $MessageAttr["message_senderid"];
                $this->m_subject = $MessageAttr["message_subject"];
                $this->m_text = $MessageAttr["message_text"];
                $this->m_date = $MessageAttr["message_date"];
                $this->SetFolderID($userid);
            }
            else {
                bc_die("No message with the specified id found");
                return false;
            }
        }
        
        
        function NextRecipient() {
            global $mrecipients;
            
            if ( empty($this->sql_recip_result) ) {
                $this->sql_recip_result = bcsql_query("SELECT * FROM `$mrecipients` WHERE `mrecipient_msgid` = '$this->m_id'");
                $this->sql_recip_num_rows = bcsql_num_rows($this->sql_recip_result);
                $this->sql_recip_pointer = 1;
            }
            if ( $this->sql_recip_pointer <= $this->sql_recip_num_rows  ) {
                $RecipAttr = bcsql_fetch_array($this->sql_recip_result);                
                $this->sql_recip_pointer++;
                return $RecipAttr["mrecipient_userid"];
            }
        }
        
        function AddRecipient($userid) {
            global $mrecipients;
            
            bcsql_query("INSERT INTO `$mrecipients` (`mrecipient_userid`,`mrecipient_msgid`) VALUES ('$userid','$this->m_id')");
        }
        
        function RemoveRecipient($userid) {
            global $mrecipients;
            
            bcsql_query("DELETE FROM `$mrecipients` WHERE `mrecipient_userid` = '$userid' LIMIT 1");
        }
        
        function MoveToFolder($newfoldid,$by = 0) {
            global $messagefolders;
            global $mfolder_user;
            global $user;
            
            $result = bcsql_query("SELECT `mfolder_userid` FROM `$messagefolders` WHERE `mfolder_id` = '$newfoldid' LIMIT 1");
            $CheckIfExists = bcsql_num_rows($result);
            $temp = bcsql_fetch_array($result);
            if ( $by == 0 ) { 
                $userid = $user->Id();
            }
            else {
                $userid = $by;
            }
            $IsOwnedByUser = $temp["mfolder_userid"];
            if ( !$CheckIfExists ) {
                $this->errtext = "The specified folder does not exist";
                return false;
            }
            else if ( $IsOwnedByUser != $userid ) {
                $this->errtext = "The specified folder is not owned by you";
                return false;
            }
            else {
                bcsql_query("UPDATE `$mfolder_user` SET `mfuser_foldid` = '" . $newfoldid . "' WHERE `mfuser_msgid` = '" . $this->m_id . "' AND `mfuser_foldid` = '" . $this->m_foldid . "' LIMIT 1");
                return true;
            }
        }
        
        function DeleteMessage() {
            global $user;
            global $mfolder_user;
            
            $userid = $user->Id();
            $result = bcsql_query("SELECT * FROM `$mfolder_user` WHERE `mfuser_foldid` = '" . $this->m_foldid . "' AND `mfuser_msgid` = '" . $this->m_id . "' LIMIT 1");
            $CheckIfExists = bcsql_num_rows($result);
            if ( !$CheckIfExists ) {
                $this->errtext = "The specified folder/message does not exist";
                return false;
            }
            else {
                bcsql_query("DELETE FROM `$mfolder_user` WHERE `mfuser_foldid` = '" . $this->m_foldid . "' AND `mfuser_msgid` = '" . $this->m_id . "' LIMIT 1");
                return true;
            }
        }
        
        function SendMessage() {
            global $user;
            global $mrecipients;
            global $messages;
            global $mfolder_user;
            
            $result = bcsql_query("SELECT * FROM `$mrecipients` WHERE `mrecipient_msgid` = '$this->m_id'");
            while ($RecipAttr = bcsql_fetch_array($result)) {
                $e = GetUserSpecialFolder($RecipAttr["mrecipient_userid"],"incoming");
                bcsql_query("INSERT INTO `$mfolder_user` (`mfuser_msgid`,`mfuser_foldid`,`mfuser_read`) VALUES ('$this->m_id','$e','no')");
            }
            $temptstamp = NowDate();
            bcsql_query("UPDATE `$messages` SET `message_date` = '$temptstamp' WHERE `message_id` = '$this->m_id'");
            $this->m_date = $temptstamp;
        }
        
        function IsUnread() {
            global $mfolder_user;
            
            $result = bcsql_query("SELECT `mfuser_read` FROM `$mfolder_user` WHERE `mfuser_foldid` = '" . $this->m_foldid . "' AND `mfuser_msgid` = '" . $this->m_id . "' LIMIT 1");
            $fetched = bcsql_fetch_array($result);
            if ( $fetched["mfuser_read"] == 'yes' ) {
                return false;
            }
            else {
                return true;
            }
        }
        
        function MarkAsRead($read = 'yes') {
            global $mfolder_user;
            bcsql_query("UPDATE `$mfolder_user` SET `mfuser_read` = '$read' WHERE `mfuser_foldid` = '" . $this->m_foldid . "' AND `mfuser_msgid` = '" . $this->m_id . "' LIMIT 1");
            $result = bcsql_query("SELECT * FROM `$mfolder_user` WHERE `mfuser_foldid` = '" . $this->m_foldid . "' AND `mfuser_msgid` = '" . $this->m_id . "' LIMIT 1");
            $fetched = bcsql_fetch_array($result);
            return $fetched["mfuser_read"];
        }
        
        function ReportAsSpam() {
            global $mspams;
            
            $temptstamp = NowDate();
            bcsql_query("INSERT INTO `$mspams` (`mspam_msgid`,`mspam_date`) VALUES ('$this->m_id','$temptstamp')");
        }
        
        function SetFolderID ($userid) {
            global $mfolder_user;
            
            $result = bcsql_query("SELECT * FROM `$mfolder_user` WHERE `mfuser_msgid` = '" . $this->m_id . "'");
            while( $mfuAttr = bcsql_fetch_array($result)) {
                $curmf = $mfuAttr["mfuser_foldid"];
                $curf = New MessageFolder($curmf);
                if ( $curf->FolderOwnerID() == $userid ) {
                    $this->m_foldid = $curmf;
                    return true;
                }
            }
            return false;
        }
        
        function CheckForSpams($userid = "none") {
            global $mfilters;
            global $mfolder_user;
            global $user;
            
            if ( $userid == "none" ) {
                $userid = $user->Id();
            }
            $result = bcsql_query("SELECT * FROM `$mfilters` WHERE `mfilter_targetid` = '-1'");
            while ($curfilter = bcsql_fetch_array($result)) {
                if ( $curfilter->FilterMessage($this->m_id) ) {
                    $this->MoveToFolder(GetUserSpecialFolder($userid,'spam'),$userid);
                    return true;
                }
            }
        }
        
        function CheckForFilters($userid = "none") {
            global $user;
            if ( $userid == "none" ) {
                $userid = $user->Id();
            }
            $allfilters = New Filters();
            while ($curfilterid = $allfilters->NextFilter($userid)) {
                $curfilter = New Filter($curfilterid);
                if ( $curfilter->FilterMessage($this->m_id) ) {
                    $targetid = $curfilter->FilterTargetID();
                    //echo "Message should be moved to: " . $curfilter->FilterTargetID(); 
                    $a = $this->MoveToFolder($curfilter->FilterTargetID(),$userid);
                    return true;
                }
            }
            return false;
        }
        
        function GetErrorText($clear = true) {
            $a = $this->errtext;
            if ( $clear == true ) { $this->errtext = ""; }
            return $a;
        }
    }
    
    class Filters {
        var $sql_result;
        var $sql_num_rows;
        var $sql_pointer;
        var $errtext;
        
        function GetErrorText($clear = true) {
            $a = $this->errtext;
            if ( $clear == true ) { $this->errtext = ""; }
            return $a;
        }
        
        function AddFilter($name,$targetid) {
            global $user;
            global $mfilters;
            
            if ( $targetid == -1 ) {
                $isadmin = $user->IsAdmin();
                if ( $isadmin == false ) {
                    $this->errtext = "only the administrators can add spam filters";
                    return false;
                }
            }
            $CheckIfExists = New MessageFolder($targetid);
            $fname = $CheckIfExists->FolderName();
            if ( empty($fname) ) {
                $this->errtext = "folder does not exist";
                return false;
            }
            $name = bcsql_escape($name);
            $userid = $user->Id();
            bcsql_query("INSERT INTO `$mfilters` (`mfilter_name`,`mfilter_userid`,`mfilter_targetid`) VALUES ('$name','$userid','$targetid')");
            return bcsql_insert_id();
        }
        
        function RemoveFilter($fid) {
            global $user;
            global $mfilters;
            global $mfiltertypes;
            
            $userid = $user->Id();
            $result = bcsql_query("SELECT * FROM `$mfilters` WHERE `mfilter_id` = '" . $fid . "' LIMIT 1");
            $CheckIfExists = bcsql_num_rows($result);
            if ( !$CheckIfExists ) {
                $this->errtext = "filter id does not exist";
                return false;
            }
            else {
                $CheckOwner = bcsql_fetch_array($result);
                if ( $userid != $CheckOwner["mfilter_userid"] ) {
                    $this->errtext = "filter owner does not match";
                    return false;
                }
                else {
                    bcsql_query("DELETE FROM `$mfilters` WHERE `mfilter_id` = '" . $fid . "' LIMIT 1");
                    bcsql_query("DELETE FROM `$mfiltertypes` WHERE `mftype_filterid` = '" . $fid . "'");
                    return true;
                }
            }
        }
        
        function NextFilter($userid = "none") {
            global $mfilters;
            global $user;
            
            if ( $userid == "none" ) { $userid = $user->Id(); }
            if ( empty($this->sql_result) ) {
                $this->sql_result = bcsql_query("SELECT * FROM `$mfilters` WHERE `mfilter_userid` = '$userid'");
                $this->sql_num_rows = bcsql_num_rows($this->sql_result);
            }
            if ($this->sql_pointer < $this->sql_num_rows) {
                $CurFilterAttr = bcsql_fetch_array($this->sql_result);
                $this->sql_pointer++;
                return $CurFilterAttr["mfilter_id"];
            }
            else {
                $this->errtext = "All filters have been returned";
                return false;
            }
        }
        
        function CountFilters($userid = "none") {
            global $mfilters;
            global $user;
            
            if ( $userid == "none" ) { $userid = $user->Id(); }
            $result = bcsql_query("SELECT COUNT(*) AS fcount FROM `$mfilters` WHERE `mfilter_userid` = '$userid'");
            $FilterCount = bcsql_fetch_array($result);
            return $FilterCount["fcount"];
        }
    }
    
    class Filter {
        var $f_id;
        var $f_userid;
        var $f_name;
        var $f_targetid;
        var $sql_result;
        var $sql_num_rows;
        var $sql_pointer;
        var $type_type;
        var $type_info;
        var $type_not;
        var $errtext;
        
        function GetTypeType() {
            return $this->type_type;
        }
        
        function GetTypeInfo() {
            return $this->type_info;
        }
        
        function GetTypeNot() {
            return $this->type_not;
        }
        
        function GetErrorText($clear = true) {
            $a = $this->errtext;
            if ( $clear == true ) { $this->errtext = ""; }
            return $a;
        }
        
        function FilterIsSpam() {
            if ( $this->f_targetid == -1 ) { return true; }
            else { return false; }
        }
        
        function FilterID() {
            return $this->f_id;
        }
        
        function FilterTargetID() {
            return $this->f_targetid;
        }
        
        function FilterName() {
            return $this->f_name;
        }
        
        function FilterUserID() {
            return $this->f_userid;
        }
        
        function CountTypes() {
            global $mfiltertypes;
            
            $result = bcsql_query("SELECT COUNT(*) AS mftypecount FROM `$mfiltertypes` WHERE `mftype_filterid` = '" . $this->f_id . "'");
            $TypeCount = bcsql_fetch_array($result);
            return $TypeCount["mftypecount"];
        }
        
        function AddType($type,$typeinfo,$not = "false") {
            global $mfiltertypes;
            
            $typeinfo = bcsql_escape($typeinfo);
            if (( $not != "false" ) && ( $not != "true" )) {
                $this->errtext = "the \$not value can only be set to \"true\" or \"false\"";
                return false;
            }
            $result = bcsql_query("INSERT INTO `$mfiltertypes` (`mftype_filterid`,`mftype_type`,`mftype_info`,`mftype_not`) VALUES ('" . $this->f_id . "','" . $type . "','" . $typeinfo . "','" . $not . "')");
            return true;
        }
        
        function NextType() {
            global $mfiltertypes;
            
            if ( empty($this->sql_result) ) {
                $this->sql_result = bcsql_query("SELECT * FROM `$mfiltertypes` WHERE `mftype_filterid` = '" . $this->f_id . "'");
                $this->sql_num_rows = bcsql_num_rows($this->sql_result); 
            }
            if ($this->sql_pointer < $this->sql_num_rows) {
                $CurFilterTypeAttr = bcsql_fetch_array($this->sql_result);
                $this->sql_pointer++;
                $this->type_type = $CurFilterTypeAttr["mftype_type"];
                $this->type_info = $CurFilterTypeAttr["mftype_info"];
                $this->type_not = $CurFilterTypeAttr["mftype_not"];
                return $CurFilterTypeAttr["mftype_id"];
            }
            else {
                $this->errtext = "All messages have been returned";
                return false;
            }
        }        
        function RenameFilter($newname) {
            global $mfilters;
            
            $newname = bcsql_escape($newname);
            bcsql_query("UPDATE `$mfilters` SET `mfilter_name` = '$newname' WHERE `mfilter_id` = '" . $this->f_id . "' LIMIT 1");
        }
        
        function Filter($id) {
            global $mfilters;
            
            $result = bcsql_query("SELECT * FROM `$mfilters` WHERE `mfilter_id` = '$id' LIMIT 1");
            $FilterAttr = bcsql_fetch_array($result);
            $this->f_id = $FilterAttr["mfilter_id"];
            $this->f_userid = $FilterAttr["mfilter_userid"];
            $this->f_name = $FilterAttr["mfilter_name"];
            $this->f_targetid = $FilterAttr["mfilter_targetid"];
        }
        
        function FilterMessage($msgid) {
            while ($curtypeid = $this->NextType()) {
                if ( $this->CheckMessage($msgid) == "true" ) {
                    return true;
                }
            }
            return false;
        }
        
        function CheckMessage($msgid) {
            global $messages;
            
            $result = bcsql_query("SELECT * FROM `$messages` WHERE `message_id` = '$msgid' LIMIT 1");
            $MsgAttr = bcsql_fetch_array($result);
            if ( $this->type_not == "false" ) { $toreturn = "false"; $tonotreturn = "true"; }
            else { $toreturn = "true"; $tonotreturn = "false"; }
            if ( $this->type_type == 1 ) { //is sent by
                if ( GetUserByUsername($this->type_info) == false ) {
                    return $tonotreturn;
                }
                $f_sender = GetUserByUsername($this->type_info);
                $f_senderid = $f_sender->Id();
                if ( $MsgAttr["message_senderid"] == $f_senderid ) {
                    return $toreturn;
                }
                return $tonotreturn;
            }
            if ( $this->type_type == 2 ) { //subject contains
                $f_result = stripos($MsgAttr["message_subject"],$this->type_info);
                if ( $f_result !== false ) {
                    return $toreturn;
                }
                return $tonotreturn;
            }
            if ( $this->type_type == 3 ) { //body contains
                $f_result = stripos($MsgAttr["message_text"],$this->type_info);
                if ( $f_result !== false ) {
                    return $toreturn;
                }
                return $tonotreturn;
            }
        }
    }
    
    class Spams {
        var $sql_result;
        var $sql_num_rows;
        var $sql_pointer;
        var $errtext;
        
        function NextSpam() {
            global $mspams;
            
            if ( empty($this->sql_result) ) {
                $this->sql_result = bcsql_query("SELECT * FROM `$mspams`");
                $this->sql_num_rows = bcsql_num_rows($this->sql_result);
            }
            if ($this->sql_pointer < $this->sql_num_rows) {
                $CurSpamAttr = bcsql_fetch_array($this->sql_result);
                $this->sql_pointer++;
                return $CurSpamAttr["mspam_id"];
            }
            else {
                $this->errtext = "All messages have been returned";
                return false;
            }
        }
        
        function GetErrorText($clear = true) {
            $a = $this->errtext;
            if ( $clear == true ) { $this->errtext = ""; }
            return $a;
        }
    }
    
    class Spam {
        var $s_msgid;
        var $s_date;
        var $s_id;
        
        function Spam($s_id) {
            global $mspams;
            
            $result = bcsql_query("SELECT * FROM `$mspams` WHERE `mspam_id` = '$s_id' LIMIT 1");
            $CheckIfExists = bcsql_num_rows($result);
            if ( $CheckIfExists ) {
                $SpamAttr = bcsql_fetch_array($result);
                $this->s_msgid = $SpamAttr["mspam_msgid"];
                $this->s_date = $SpamAttr["mspam_date"];
            }
            else {
                bc_die("No spam with the specified id found");
                return false;
            }
        }
        
        function GetMsgID() {
            return $this->s_msgid;
        }
        
        function GetDate() {
            return $this->s_date;
        }
    }
?>