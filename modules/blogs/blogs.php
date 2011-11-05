<?php
    /*
        Developer: Dionyziz
    */
    include "modules/module.php";

    function blognamevalid( $name ) {
        global $reserved;
        
        // blog names are used as a reference at the URL
        // make sure they only contain a-z, A-Z, 0-9 and -
        // and at least 3 characters and starts with a letter
        $name = strtolower( $name );
        $validblognames = "#^[a-z][a-z0-9\-]{2,}$#i";
        if( !preg_match( $validblognames , $name ) ) {
            return -1;
        }
        if( in_array( $name , $reserved ) ) {
            return -2; // reserved
        }
        return true;
    }

    function blogpostvalid( $name ) {
        // check for valid post name
        // in a few words, no HTML is allowed so just check
        // if it contains html tags
        return true;
    }
    
    function blogpostcontentvalid( $content ) {
        // this function should check if blog content is valid
        // we should check for invalid html such as weird tags
        // including <html>, <body>, <title>, <script>, <frame>, <iframe>,
        // <style>, <object>, <embed>, etc.
        return true;
    }

    function CreateBlog( $blogname ) {
        global $blogs;
        global $user;
        global $permissions;
        
        $bnvalid = blognamevalid( $blogname );
        if( $bnvalid !== true ) {
            switch( $bnvalid ) {
                case -1:
                    return -1; //invalid characters
                case -2;
                    return -3; //reserved
            }
        }
        
        $blogname = bcsql_escape( $blogname );
        $sql = "SELECT
                COUNT(*) AS blogexists
            FROM
                `$blogs`
            WHERE
                `blog_name`='$blogname'
            LIMIT 1;";
        $sqlr = bcsql_query( $sql );
        $blogexists = bcsql_fetch_array( $sqlr );
        if ( $blogexists[ "blogexists" ] ) {
            return -2; // blog already exists
        }
        $nowdate = NowDate();
        $sql = "INSERT INTO
                `$blogs`
            ( `blog_id` , `blog_name` , `blog_title` , `blog_description` , `blog_access` , `blog_createdate` , `blog_active` )
            VALUES
            ( '' , '$blogname' , '$blogname' , '' , 'public' , '$nowdate' , 'yes' );";
        bcsql_query( $sql );
        $blogid = bcsql_insert_id();
        // grand blog permissions to creator
        $sql = "INSERT INTO
                    `$permissions`
                ( `permission_id` , `permission_userid` , `permission_blogid` , `permission_permissions` )
                VALUES
                ( '' , '" . $user->Id() . "' , '$blogid' , '100' );";
        bcsql_query( $sql );
        
        return $blogid;
    }
    
    class Blogs {
        var $mBlog;
        var $mLength;
        
        function Blog( $index ) {
            if ( $index < $this->mLength ) 
                return $this->mBlog[ $index ];
            else
                return false;
        }
        function Length() {
            return $this->mLength;
        }
        function Blogs( $sql_resource ) {
            $this->mLength = 0;
            while ( $thisblog = bcsql_fetch_array( $sql_resource ) ) {
                $this->mBlog[ $this->mLength ] = New Blog( $thisblog );
                ++$this->mLength;
            }
        }
    }
    
    function GetBlogByName( $name ) {
        global $blogs;
        
        $name = bcsql_escape( $name );
        $sql = "SELECT * FROM `$blogs` WHERE `blog_name`='$name' LIMIT 1;";
        $sqlr = bcsql_query( $sql );
        if( bcsql_num_rows( $sqlr ) ) {
            return New Blog( bcsql_fetch_array( $sqlr ) );
        }
        else {
            return false;
        }
    }
    
    function GetBlog( $perm = 100 ) {
        global $blogs;
        global $permissions;
        global $user;
        
        $blogid = $_POST[ "blogid" ];
        bc_assert( ValidId( $blogid ) );
        $sql = "SELECT
                    `$blogs`.*,
                    `$permissions`.`permission_permissions`
                FROM
                    `$blogs` LEFT JOIN `$permissions`
                        ON ( `$blogs`.`blog_id`=`$permissions`.`permission_blogid` AND
                             `$permissions`.`permission_userid`='" . $user->Id() . "' )
                WHERE
                    `$blogs`.`blog_id`='$blogid'
                LIMIT 1;";
        $sqlr = bcsql_query( $sql );
        if ( bcsql_num_rows( $sqlr ) ) {
            $sqlblog = bcsql_fetch_array( $sqlr );
            $blog = New Blog( $sqlblog );
            $blogperm = $sqlblog[ "permission_permissions" ];
            if ( $blogperm == NULL ) {
                // has no permissions at all (not even negative, nothing)
                if ( $perm == 0 ) {
                    // no permissions needed anyway
                    // check for read access
                    if ( $blog->Access() == "public" ) {
                        // public blog, access granted
                        $access = true;
                    }
                    else {
                        // private blog, denied
                        $access = false;
                    }
                }
                else {
                    // needs some permissions, denied
                    $access = false;
                }
            }
            else {
                // has a permission entry, check if it's sulficient
                if ( $blogperm >= $perm ) {
                    // has necessary (or more) permissions on this blog
                    $access = true;
                }
                else {
                    // has some permissions but not enough for this functionality
                    $access = false;
                }
            }
        }
        else {
            // no such blog
            $access = false;
        }
        if ( !$access ) {
            $blog = New Blog( Array() );
        }
        return $blog;
    }
    
    class Blog {
        var $mId;
        var $mTitle;
        var $mName;
        var $mURL;
        var $mDescription;
        var $mAccess;
        var $mMyPermissions;
        var $mMyPermissionsLoaded;
        var $mTemplateId;
        var $mShowPostsCount;
        var $mBlogPosts;
        var $mBlogPostsCount;
        var $mBlogPostCurrent;
        var $mGotBlogPosts;
        var $mCreateDate;
        
        function CreatePost( $title , $content ) {
            global $posts;
            global $user;
            
            $n = bcsql_escape( $title );
            $c = bcsql_escape( $content );
            
            $nowdate = NowDate();
            $ip = UserIp();
            $sql = "INSERT INTO
                        `$posts`
                    ( `post_id` , `post_blogid` , `post_userid` , `post_title` , `post_text` , `post_date` , `post_ip` , `post_active` )
                    VALUES
                    ( '' , '" . $this->mId . "' , '" . $user->Id() . "' , '$n' , '$c' , '$nowdate' , '$ip' , 'yes' );";
            bcsql_query( $sql );
            return bcsql_insert_id();
        }
        function Id() {
            return $this->mId;
        }
        function Title() {
            return $this->mTitle;
        }
        function Name() {
            return $this->mName;
        }
        function Access() {
            return $this->mAccess;
        }
        function Description() {
            return $this->mDescription;
        }
        function TemplateId() {
            return $this->mTemplateId;
        }
        function PostsLimit() {
            return $this->mShowPostsCount != 0;
        }
        function ShowPostsCount() {
            return $this->mShowPostsCount;
        }
        function URL() {
            return $this->mURL;
        }
        function CreateDate() {
            return $this->mCreateDate;
        }
        // deprecated - use now GetPosts / GetPostsMonth
        function Post() {
            global $posts;
            
            if( !$this->mGotBlogPosts ) {
                $sql = "SELECT
                            *
                        FROM
                            `$posts`
                        WHERE
                            `$posts`.`post_blogid`='" . $this->mId . "' AND
                            `$posts`.`post_active`='yes'
                        ORDER BY
                            `$posts`.`post_date` DESC";
                /* if( $this->PostsLimit() ) {
                    $sql .= " LIMIT " . $blog->ShowPostsCount();
                } */
                $this->mBlogPosts = bcsql_query( $sql );
                $this->mBlogPostsCount = bcsql_num_rows( $this->mBlogPosts );
                $this->mGotBlogPosts = true;
                $this->mBlogPostCurrent = 0;
            }
            if( $this->mBlogPostCurrent < $this->mBlogPostsCount ) {
                $this->mBlogPostCurrent++;
                return New Post( bcsql_fetch_array( $this->mBlogPosts ) );
            }
            else {
                return false;
            }
        }
        function GetPosts( $start = 0, $limit = 6 ) {
            global $posts;
            
            $sql = "SELECT
                        *
                    FROM
                        `$posts`
                    WHERE
                        `$posts`.`post_blogid`='" . $this->mId . "' AND
                        `$posts`.`post_active`='yes'
                    ORDER BY
                        `$posts`.`post_date` DESC
                    LIMIT
                        $start , $limit";
            return New Posts( bcsql_query( $sql ) );
        }
        function GetPostsMonth( $month, $start = 0, $limit = 6 ) {
            global $posts;
            
            $year = substr( $month, 0, 4 );
            $month = substr( $month, 4, 2);
            
            $sql = "SELECT
                        *
                    FROM
                        `$posts`
                    WHERE
                        `$posts`.`post_blogid`='" . $this->mId . "' AND
                        `$posts`.`post_active`='yes' AND
                        '" . $year . "-" . $month . "-01 00:00:00' <= `$posts`.`post_date` AND
                        `$posts`.`post_date` <= '" . $year . "-" . $month . "-31 23:59:59'
                    ORDER BY
                        `$posts`.`post_date` DESC
                    LIMIT
                        $start , $limit";
            return New Posts( bcsql_query( $sql ) );
        }
        function SetTitle( $newtitle ) {
            // should check access permissions before calling (done in /elements/blogging/manage/title/save.php)
            global $blogs;
            
            if ( $newtitle != $this->mTitle ) {
                $this->mTitle = $newtitle;
                $newtitle = bcsql_escape( $newtitle );
                $sql = "UPDATE
                            `$blogs`
                        SET
                            `$blogs`.`blog_title`='$newtitle'
                        WHERE
                            `$blogs`.`blog_id`='" . $this->mId . "'
                        LIMIT 1;";
                bcsql_query( $sql );
            }
        }
        function SetDescription( $newdescription ) {
            // should check access permissions before calling (done in /elements/blogging/manage/description/save.php)
            global $blogs;
            
            if ( $newdescription != $this->mDescription ) {
                $this->mDescription = $newdescription;
                $newtitle = bcsql_escape( $newdescription );
                $sql = "UPDATE
                            `$blogs`
                        SET
                            `$blogs`.`blog_description`='$newdescription'
                        WHERE
                            `$blogs`.`blog_id`='" . $this->mId . "'
                        LIMIT 1;";
                bcsql_query( $sql );
            }
        }
        function IsMine( $permissionlevel = 100 ) {
            global $blogs;
            global $permissions;
            global $user;
            
            if ( !$this->mMyPermissionsLoaded ) {
                $sql = "SELECT
                            `permission_permissions`
                        FROM
                            `$permissions`,`$blogs`
                        WHERE
                            `$blogs`.`blog_id`='" . $this->mId . "' AND
                            `$permissions`.`permission_userid`='" . $user->Id() . "' AND
                            `$blogs`.`blog_id`=`$permissions`.`permission_blogid`
                        LIMIT 1;";
                $sqlr = bcsql_query( $sql );
                if ( bcsql_num_rows( $sqlr ) == 0 )
                    $this->mMyPermissions = 0;
                else {
                    $sqlpermissions = bcsql_fetch_array( $sqlr );
                    $this->mMyPermissions = $sqlpermissions[ "permission_permissions" ];
                }
            }
            return $this->mMyPermissions >= $permissionlevel;
        }
        function Delete() {
            global $blogs;
            
            $sql = "UPDATE
                        `$blogs`
                    SET
                        `blog_active`='no'
                    WHERE
                        `blog_id`='" . $this->mId . "'
                    LIMIT 1;";
            bcsql_query( $sql );
        }
        function Blog( $construct ) {
            global $blogs;
            global $domainname;
            
            // constructor:
            // 1) New Blog( bcsql_fetch_array( sql_resource ) );
            // 2) New Blog( blogid )
            if ( !is_array( $construct ) ) {
                bc_assert( ValidId( $construct ) );
                $sql = "SELECT 
                            *
                        FROM
                            `$blogs`
                        WHERE
                            `$blogs`.`blog_id`='$construct'
                        LIMIT 1;";
                $sqlr = bcsql_query( $sql );
                if ( bcsql_num_rows( $sqlr ) == 0 )
                    $fetched_array = Array();
                else
                    $fetched_array = bcsql_fetch_array( $sqlr );
            }
            else {
                $fetched_array = $construct;
            }
            $this->mId = $fetched_array[ "blog_id" ];
            $this->mTitle = $fetched_array[ "blog_title" ];
            $this->mName = $fetched_array[ "blog_name" ];
            $this->mDescription = $fetched_array[ "blog_description" ];
            $this->mAccess = $fetched_array[ "blog_access" ];
            $this->mTemplateId = $fetched_array[ "blog_templateid" ];
            $this->mShowPostsCount = $fetched_array[ "blog_showpostscount" ];
            $this->mCreateDate = $fetched_array[ "blog_createdate" ];
            $this->mURL = "http://" . $this->mName . "." . $domainname . "/";
            $this->mGotBlogPosts = false;
        }
    }
?>