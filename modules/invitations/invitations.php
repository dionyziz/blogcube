<?php
    /* 
    Module: Invitations
    File: /modules/invitations/invitations.php
    Developer: Christian
    */
    include "modules/module.php";

    require "modules/invitations/invitation_template.php";
    
    function AssignInvitations( $UserID, $NumOfInvitations ) {
        global $user;

        if ( $user->IsAdmin() ) {
            // All right. The user is admin
            $user_getting_invitations = New User( $UserID );
            $user_getting_invitations->AssignInvitations( $NumOfInvitations );
            return true;
        }
        else {
            return false;
        }
    }
    
    function Invite( $InvitedUser , $InvitedEmail ) {
        global $user;
        global $invitations;

        if( $user->InvitationsLeft() > 0 ) {
            $random = new Random();
            $code = $random->get( 45 );
            $sql = "INSERT INTO `$invitations` (`invitation_id`, `invitation_invitinguserid`,
                `invitation_invitedemail`, `invitation_invitedname`, `invitation_registereduserid`,
                `invitation_code`) VALUES ( '', '" . $user->Id() . "',
                '" . $InvitedEmail . "', '" . $InvitedUser . "', '', '" . $code . "' );";
            $sqlr = bcsql_query( $sql );
            $id = bcsql_insert_id(); // get the id of the newly created row        
            $text = CreateInvitation( $user->FirstName() , $user->LastName() , $id , $code ); // see invitation_template.php
            $subject = $user->FirstName() . " " . $user->LastName() . " has invited you to open a BlogCube account";
            $header = "From: \"" . $user->FirstName() . " " . $user->LastName() . "\" <" . $user->Email() . ">";
            
            mail( $InvitedEmail , $subject , $text , $header );
            $user->ReduceInvitations(); // the user who is using the invitation has now one less
        }
        else {
            bc_die( "No invitations left" );
        }
    }
    
    function GetInvitations( $UserID ) {
        global $invitations;
        
        $sql = "SELECT `invitation_id` FROM `$invitations` WHERE
            `invitation_invitinguserid`='$UserID';";
        $sqlr = bcsql_query( $sql );
        if( !bcsql_num_rows( $sqlr ) ) {
            return false;
        }
        else {
            $invitationhist = Array();
            while( $sqlinvitation = bcsql_fetch_array( $sqlr ) ) {
                $invitationhist[] = $sqlinvitation[ "invitation_id" ];
            }
        }
        return $invitationhist;
    }

    class Invitation {
        private $mID;
        private $mInvitingUserID;
        private $mInvitedEmail;
        private $mInvitedName;
        private $mRegisteredUserID;
        private $mCode;
        
        public function MarkAsUsed( $registereduserid ) { 
            // MarkAsUser() marks the current invitation as used (by dionyziz)
            // for creating the user account with id $registereduserid
            // if it doesn't already correspond to a different account
            
            global $invitations;
            
            // check if the invitation has already been used for creating another account
            if( $this->mRegisteredUserID == 0 ) {
                // okay, hasn't been used yet
                // we'll need to update our invitations table
                $sql = "UPDATE `$invitations` SET `invitation_registereduserid`='$registereduserid' 
                        WHERE `invitation_id`='" . $this->mID . "' LIMIT 1;";
                bcsql_query( $sql );
                $this->mRegisteredUserID = $registereduserid;
                return true;
            }
            else {
                // it has been used, don't do anything and return false (error)
                return false;
            }
        }
        
        public function Id() {
            return $this->mID;
        }
        
        public function InvitingUserId() {
            return $this->mInvitingUserID;
        }
        
        public function InvitedEmail() {
            return $this->mInvitedEmail;
        }
        
        public function InvitedName() {
            return $this->mInvitedName;
        }
        
        public function RegisteredUserId() {
            return $this->mRegisteredUserID;
        }
        
        public function Code() {
            return $this->mCode;
        }
        
        public function CheckValidy( $code ) {
            if( $this->mCode == $code && ValidId( $this->mID ) ) {
                return true;
            }
            else {
                return false;
            }
        }
        
        public function Invitation( $construct ) {
            global $invitations;
            
            $sql = "SELECT
                    `$invitations`.*
                FROM
                    `$invitations`
                WHERE
                    `$invitations`.`invitation_id`='" . $construct . "'
                LIMIT 1;";
            $sqlr = bcsql_query( $sql ) or mydie( mysql_error() );
            $fetched_array = bcsql_fetch_array( $sqlr );
            
            $this->mID = $fetched_array['invitation_id'];
            $this->mInvitingUserID = $fetched_array['invitation_invitinguserid'];
            $this->mInvitedEmail = $fetched_array['invitation_invitedemail'];
            $this->mInvitedName = $fetched_array['invitation_invitedname'];
            $this->mRegisteredUserID = $fetched_array['invitation_registereduserid'];
            $this->mCode = $fetched_array['invitation_code'];
        }
    }
?>
