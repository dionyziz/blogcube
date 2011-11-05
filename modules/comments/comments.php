<?php
    /* 
    Module: Comments
    File: /modules/comments/comments.php
    Developers: Izual, feedWARd, Dionyziz
    */
    
    include "modules/module.php";
    
    /* i believe we should also add a comment_key field in the parameter because it will be easier for the client-side coders to call the functions
       (see my documentation.php module) --feedWARd
       no, we don't need to refer to a key, we can just use the comment's id to refer to it --dionyziz
    */
    
    /*
    this module is a cooperation of izual and feedWARd and i suggest that we should make the corrections and suggestions to the source code through comments like this one
    the things that must be done are: submiting a comment to the database, setting it to inactive and retrieving from the database a comment and it's children comments
    and making therefore a class
    
    --Izual
    */
    
    /*after some discussion with dionyziz we finally conlcuded that we are going to use a field for active or inactive comments
    --Izual
    */
    
    /*
    old comments class has been totally rewritten so that we can have post comments, photo comments, album comments, etc.
    --dionyziz
    */
    
    /* deprectated */ abstract class DEPRECATED__comment {
        /* (Edits the comment's text. Furthermore, it adds the text '-- edited on <date> --' at the end
        of the comment (shall we keep this feature?) 
        -- feedWARd
        no, store the last edit date in a special seperate field, which you should create in your dbase structure
        (we can use this field on client-side code to display this message if we want)
        -- dionyziz
        */
        function EditComment( $newcomment ) {
            /* i altered the last edit and created a new field in the db with the name `comment_lastedit`
            so if someone wants to check if a comment has been edited he can check if the the `comment_timestamp`is the same with `comment_lastedid`
            --Izual
            */
            global $comments;
            
            $newcomment = bcsql_escape($newcomment);
            $timestamp = NowDate();
            bcsql_query("UPDATE `$comments` SET `comment_comment` = '$newcomment' , `comment_lastedit` = '$timestamp' WHERE `comment_id` = '$this->c_id' LIMIT 1 ;");
            $this->c_comment = $newcomment;
        }
    }
    
    // blame dionyziz below this line
    function CommentPostSubmit( $postid , $commentid ) {
        global $postcomments;
        global $posts;

        if ( !ValidId( $postid ) || !ValidId( $commentid ) ) {
            bc_die( 'Invalid postid or commentid' );
        }
        $sql = "SELECT
                `post_id`
            FROM
                `$posts`
            WHERE
                `post_id`='$postid'
            LIMIT 1;";
        $res = bcsql_query( $sql );
        if ( !$res->NumRows() ) {
            bc_die( "No such post!" );
        }
        $sql = "INSERT INTO
                `$postcomments`
            (`pcomment_id`, `pcomment_commentid`, `pcomment_postid`)
            VALUES('', '$commentid', '$postid');";
        bcsql_query( $sql );
    }

    function CommentSubmit( $comment , $parentid /* , $avatar = 0 */ ) {
        global $user;
        global $globalcomments;

        if ( $parentid != 0 && !ValidId( $parentid ) ) {
            bc_die( "Invalid parent id ($parentid)!" );
        }
        if ( $parentid != 0 ) {
            $sql = "SELECT 
                    `gcomment_id`, `gcomment_active`
                FROM
                    `$globalcomments`
                WHERE
                    `gcomment_id`='$parentid'
                LIMIT 1;";
            $res = bcsql_query( $sql );
            if ( !$res->NumRows() ) {
                bc_die( "No such parent comment ($parentid)!" );
            }
            $row = $res->FetchArray();
            $parentcomment = New GlobalComment( $row );
            if ( !$parentcomment->Active() ) {
                bc_die( "You can't reply to a deleted comment ($parentid)!" );
            }
        }
        $userip = UserIp();
        $nowdate = NowDate();
        $userid = $user->Id();
        $comment = bcsql_escape( $comment );
        $sql = "INSERT INTO `$globalcomments`
            (`gcomment_id`, `gcomment_ip`, `gcomment_created`, `gcomment_userid`, `gcomment_parid`, `gcomment_comment`, `gcomment_active`, `gcomment_lastedited`, `gcomment_avatarid`)
            VALUES( '' , '$userip' , '$nowdate' , '$userid' , '$parentid' , '$comment' , 'yes' , '$nowdate' , '0' );";
        bcsql_query( $sql );
        return bcsql_insert_id();
    }
    
    class GlobalComment {
        private $mId;
        protected $mSecondaryId;
        private $mIp;
        private $mComment;
        private $mParId;
        private $mCreateDate;
        private $mUserId;
        private $mUsername;
        private $mAvatar;
        private $mActive;
        private $mLastEditDate;
        protected $mSecondaryTable;
        
        public function Id() {
            return $this->mId;
        }
        private function SecondaryId() {
            return $this->mSecondaryId;
        }
        public function Ip() {
            return $this->mIp;
        }
        public function Comment() {
            return $this->mComment;
        }
        public function CreateDate() {
            return $this->mCreateDate;
        }
        public function CreateDateH() {
            return BCDate( $this->mCreateDate );
        }
        public function UserId() {
            return $this->mUserId;
        }
        public function Username() {
            return $this->mUsername;
        }
        public function Active() { // :boolean
            return $this->mActive;
        }
        public function LastEditDate() {
            return $this->mLastEditDate;
        }
        public function Avatar() {
            return $this->mAvatar;
        }
        public function GlobalComment( $construct ) {
            global $globalcomments;

            if ( ValidId( $construct ) ) {
                $sql = "SELECT
                        *
                    FROM
                        `$globalcomments`
                    WHERE
                        `gcomment_id`=" . $construct . "
                    LIMIT 1;";
                $res = bcsql_query( $sql );
                if ( !$res->NumRows() ) {
                    bc_die( "Non-existing comment id ($construct)" );
                }
                $construct = $res->FetchArray();
            }
            if ( !isset( $construct[ "gcomment_id" ] ) ) { // constructor array
                bc_die( "GlobalComment should be called with a fetched array passed as a parameter, which should at least contain the comment id (gcomment_id)" );
            }
            $this->mId = $construct[ "gcomment_id" ];
            $this->mIp = $construct[ "gcomment_ip" ];
            $this->mCreateDate = $construct[ "gcomment_created" ];
            $this->mUserId = $construct[ "gcomment_userid" ];
            $this->mParId = $construct[ "gcomment_parid" ];
            $this->mComment = $construct[ "gcomment_comment" ];
            $this->mActive = $construct[ "gcomment_active" ] == "yes";
            $this->mLastEditDate = $construct[ "gcomment_lastedited" ];
            $this->mAvatar = $construct[ "gcomment_avatarid" ]; // AvatarId or mediaid!??!
            if ($this->mAvatar == 0) {
                $this->mAvatar = $construct[ "user_defaultavatar" ]; // notice: mediaid!
            }
            $this->mUsername = $construct[ "user_username" ];
        }
        protected function Query( $where ) {
            global $globalcomments;
            
            $sql = "SELECT
                    *
                FROM
                    `$globalcomments` ";
            if ($this->mSecondaryTable) {
                $sql .= " , `" . $this->mSecondaryTable . "`";
            }
            $sql .= "
                WHERE
                    $where;";
            $sqlr = bcsql_query( $sql );
            if( bcsql_num_rows( $sqlr ) ) {
                $sqlarray = bcsql_fetch_array( $sqlr );
                $this->GlobalComment( $sqlarray );
                return $sqlarray;
            }
            return false;
        }
    }
    
    function DeleteComment( $comment_id ) {
        global $globalcomments;
        global $user;

        if ( !ValidId( $comment_id ) ) {
            bc_die( "DeleteComment() expects a comment id" );
        }

        $sql = "UPDATE
                `$globalcomments`
            SET
                `gcomment_active`='no'
            WHERE
                `gcomment_id`='$comment_id' AND
                `gcomment_active`='yes'
            LIMIT 1;";
        bcsql_query( $sql );
    }
    
    // do not call this directly; use $Post->NumComments() instead
    function CommentsOnPost( $postid ) {
        global $globalcomments , $postcomments;
        
        if( ValidId( $postid ) ) {
            $sql = "SELECT
                    COUNT(*) AS commentscount
                FROM
                    `$postcomments`, `$globalcomments`
                WHERE
                    `$postcomments`.`pcomment_commentid`=`$globalcomments`.`gcomment_id` AND 
                    `$postcomments`.`pcomment_postid`='$postid' AND
                    `$globalcomments`.`gcomment_active`='yes';";
            $sqlr = bcsql_query( $sql );
            $sqlarray = bcsql_fetch_array( $sqlr );
            return $sqlarray[ "commentscount" ];
        }
        bc_die( "CommentsOnPost() needs a valid postid" );
    }
    
    final class PostComment extends GlobalComment {
        private $mPostId;
        
        public function PostId() {
            return $this->mPostId;
        }
        public function PostComment( $construct ) {
            global $postcomments;
            
            $this->mSecondaryTable = $postcomments;
            if ( is_array( $construct ) ) {
                // a fetched array
                $this->GlobalComment( $construct );
            }
            else if ( ValidId( $construct ) ) {
                // a comment id
                $sqlarray = $this->Query( "`gcomment_id`='$construct'" );
            }
            else {
                bc_die( "Invalid parameter passed when constructing BlogPostComment: " . $construct );
            }
            $this->mSecondaryId = $sqlarray[ "pcomment_id" ];
            $this->mPostId = $sqlarray[ "pcomment_postid" ];
        }
    }
    
    final class CommentsTree extends Tree { 
        /* 
        represents ONE comment with or without children; 
        children are CommentsTree objects themselves, added to the tree as tree-children items
        and can therefore contain their own children
        */
        private $mComment;
        private $mParent; // backreference to the comment's parent CommentsTree structure, if applicable (i.e. unless it's the root comment)
        
        public function SetComment( $fetchedarray ) {
            $this->mComment = New PostComment( $fetchedarray );
        }
        public function Comment() {
            if( !isset( $this->mComment ) )
                return false;
            return $this->mComment;
        }
        public function TreeParent() {
            return $this->mParent;
        }
        public function SetParent( $parent ) {
            $this->mParent = $parent;
        }
        public function CommentsTree() {
            $this->Tree();
        }
    }
    
    final class PostCommentsTree {
        // holds the tree objects for all comments in an array [ commentid (:int) => tree (:CommentsTree) ]
        private $mCommentsTrees;

        // returns the root CommentsTree object whose children are the root comments on the post
        // each root comment can contain children while can be read using a trivial-tree structure
        public function Root() {
            return $this->mCommentsTrees[ 0 ];
        }
        public function PostCommentsTree( $postid ) {
            // construct the post comments tree; we just need to pass the postid
            /*
                (Simple) Usage:
                
                function ShowComments( $commentstree ) {
                    echo $commentstree->Comment()->Comment();
                    ?> by <?php
                    echo $commentstree->Comment()->UserId();
                    ?><br /><?php
                    echo $commentstree->ChildrenCount();
                    ?> replies on this comment:<?php
                    while( $childcomment = $commentstree->Child() ) {
                        ShowComments( $childcomment );
                    }
                    ?> (end of replies to the current comment) <?php
                }
                ShowComments( (New PostCommentsTree( 5 ))->Root() ); // assume that we want to read all comments on post #5
            */
            
            global $globalcomments;
            global $postcomments;
            global $users;
            
            if ( !ValidId( $postid ) ) {
                bc_die( "PostCommentsTree() constructor expects a postid" );
            }
            // read all comments on this post, no matter their depth, ordered by date (newest first)
            $sql = "SELECT
                    `$globalcomments`.*,
                    `$postcomments`.*,
                    `$users`.`user_username`,
                    `$users`.`user_defaultavatar`
                FROM
                    `$globalcomments`, `$postcomments`
                    LEFT JOIN `$users` ON
                        `$globalcomments`.`gcomment_userid` = `$users`.`user_id`
                WHERE
                    `$globalcomments`.`gcomment_id`=`$postcomments`.`pcomment_commentid` AND
                    `$postcomments`.`pcomment_postid`='$postid'
                ORDER BY
                    `$globalcomments`.`gcomment_created` DESC;";
            $sqlr = bcsql_query( $sql );
            // create the root tree; this is going to have all root comments of the post as children
            $this->mCommentsTrees[ 0 ] = New CommentsTree(); // root tree
            while ( $sqlarray = bcsql_fetch_array( $sqlr ) ) {
                // create one comment tree for each comment, store it in the mCommentsTrees array using the commentid as the key
                $commentid = $sqlarray[ "gcomment_id" ];
                $this->mCommentsTrees[ $commentid ] = New CommentsTree();
                $this->mCommentsTrees[ $commentid ]->SetComment( $sqlarray );
                // create one entry in the CommentsSQLArrays array where we'll keep the fetched array for the specific comment; use the commentid again as the key
                $CommentsSQLArrays[ $commentid ] = $sqlarray;
            }
            // commentids is going to be an array containing all comment ids (children, parents, and the whole family) done on this post 
            // in date order (not in tree structure)
            $commentids = array_keys( $CommentsSQLArrays );
            // loop through all commentids on this blogpost
            for( $i = count( $commentids ) - 1 ; $i >= 0 ; $i-- ) { // reversed order
                $commentid = $commentids[ $i ];
                $sqlarray = $CommentsSQLArrays[ $commentid ];
                // two possibilities: $parentid is either 0 (so our comment is a root comment) or contains the id of the comment that is the parent of the current comment
                $parentid = $sqlarray[ "gcomment_parid" ];
                // if it's not a root comment, this will be added to its parent; if it's a root comment it will be added to the 0-index comments tree
                $this->mCommentsTrees[ $parentid ]->AttachChild( $this->mCommentsTrees[ $commentid ] );
                $this->mCommentsTrees[ $commentid ]->SetParent( $this->mCommentsTrees[ $parentid ] );
            }
            CommentsClearDeleted( $this->mCommentsTrees[ 0 ] );
        }
    }
    function CommentsClearDeleted( $tree ) {
        if ( $tree->ChildrenCount() ) {
            while ( $child = $tree->Child() ) {
                CommentsClearDeleted( $child );
            }
        }
        else {
            CommentsClearDeletedChild( $tree );
        }
    }
    function CommentsClearDeletedChild( $childtree ) {
        $comment = $childtree->Comment();
        $parent = $childtree->TreeParent();
        if ( $comment === false ) {
            // root comment ; unset
            return;
        }
        if (( !$comment->Active() ) && ( !$childtree->ChildrenCount() )) {
            $parent->UnattachLastChild();
            CommentsClearDeletedChild( $parent );
        }
    }
?>
