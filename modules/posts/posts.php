<?php
    /*
        Developer: Dionyziz
    */
    include "modules/module.php";
    
    function GetPost( $postid = 0 ) {
        if ( $postid == 0 ) {
            $postid = $_POST[ "postid" ];
        }
        $postid = bcsql_escape( $postid );
        $post = New Post( $postid );
        if ( !$post->Id() ) {
            $post = false;
        }

        return $post;
    }
    
    function GetArchives($blogid) {
        global $posts;
        global $user;
        
        $userid = $user->Id();
        $sql = "SELECT
                    `post_date`
                FROM
                    `$posts`
                WHERE
                    `post_blogid` = '$blogid' AND
                    `post_active` = 'yes'";
        $query = bcsql_query($sql);
        $archivearr = array();
        while ($cpost = bcsql_fetch_array($query)) {
            $cpostdate_key = date("Ym",strtotime($cpost['post_date']));
            $cpostdate_val = date("F Y",strtotime($cpost['post_date']));
            $archivearr[$cpostdate_key] = $cpostdate_val;
        }
        krsort($archivearr);
        return $archivearr;
    }
    
    final class Post {
        private $mId;
        private $mTitle;
        private $mText;
        private $mUserId;
        private $mBlogId;
        private $mIP;
        private $mDate;
        private $mActive;
        private $mGotComments;
        private $mCommentsCnt;
        private $mComments;
        private $mCommentIdx;
        private $mNumComments;
        
        public function Id() {
            return $this->mId;
        }
        public function BlogId() {
            return $this->mBlogId;
        }
        public function UserId() {
            return $this->mUserId;
        }
        public function IP() {
            return $this->mIP;
        }
        public function Active() {
            return $this->mActive;
        }
        public function Date() {
            return $this->mDate;
        }
        public function Title() {
            return $this->mTitle;
        }
        public function Text() {
            return $this->mText;
        }
        public function Edit( $new_title , $new_text , $new_date = "" ) {
            global $posts;
            global $user;
            
            if( $new_date == "" ) {
                $new_date = NowDate();
            }
            $this->mText = $new_text;
            $this->mTitle = $new_title;
            $this->mDate = $new_date;
            
            $new_text = bcsql_escape( $new_text );
            $new_title = bcsql_escape( $new_title );
            $nowdate = NowDate();
            $ip = UserIp();
            $sql = "UPDATE
                        `$posts`
                    SET 
                        `post_userid`='" . $user->Id() . "', 
                        `post_title`='$new_title',
                        `post_text`='$new_text',
                        `post_date`='$new_date',
                        `post_ip`='$ip'
                    WHERE
                        `post_id`='" . $this->mId . "'
                    LIMIT 1;";
            bcsql_query( $sql );
            return true;
        }
        public function Delete() {
            global $posts;
            
            $sql = "UPDATE
                        `$posts`
                    SET
                        `post_active`='no'
                    WHERE
                        `post_id`='" . $this->mId . "'
                    LIMIT 1;";
            bcsql_query( $sql );
            return true;
        }
        public function Post( $construct ) {
            global $posts;
            
            if( is_array( $construct ) ) {
                $fetched_array = $construct;
            }
            else if( ValidId( $construct ) ) {
                $construct = bcsql_escape( $construct );
                $sql = "SELECT * FROM `$posts` WHERE `post_id`='$construct' LIMIT 1;";
                $sqlr = bcsql_query( $sql );
                if( bcsql_num_rows( $sqlr ) ) {
                    $fetched_array = bcsql_fetch_array( $sqlr );
                }
                else {
                    bc_die( "Invalid post id" );
                }
            }
            else {
                bc_die( "Post constructor requires a fetched array or postid" );
            }
            $this->mId = $fetched_array[ "post_id" ];
            $this->mBlogId = $fetched_array[ "post_blogid" ];
            $this->mUserId = $fetched_array[ "post_userid" ];
            $this->mTitle = $fetched_array[ "post_title" ];
            $this->mText = $fetched_array[ "post_text" ];
            $this->mDate = $fetched_array[ "post_date" ];
            $this->mIP = $fetched_array[ "post_ip" ];
            $this->mActive = $fetched_array[ "post_active" ];
            $this->mGotComments = false;
            $this->mNumComments = -1;
        }
        function NumComments() {
            if( $this->mNumComments == -1 ) {
                $this->mNumComments = CommentsOnPost( $this->mId );
            }
            return $this->mNumComments;
        }
        /* deprecated function Comment(); -- Use /modules/comments instead */
    }
    
    class Posts {
        var $mPost;
        var $mLength;
        
        function Post( $index ) {
            if ( $index < $this->mLength )
                return $this->mPost[ $index ];
            else
                return false;
        }
        function Length() {
            return $this->mLength;
        }
        function Posts( $sql_resource ) {
            $this->mLength = 0;
            while ( $thispost = bcsql_fetch_array( $sql_resource ) ) {
                $this->mPost[ $this->mLength ] = New Post( $thispost );
                ++$this->mLength;
            }
        }
    }
?>