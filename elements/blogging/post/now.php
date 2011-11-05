<?php
    if ( !Element( "element_logged_in" ) ) {
        return false;
    }

    if ( isset( $_POST[ 'n' ] ) ) {
        $n = $_POST[ 'n' ];
    }
    else {
        $n = '';
    }
        
    if ( !blogpostvalid( $n ) ) {
        ?>
        <b>You have specified an invalid name for your post.</b><br /><br />
        Please check the name of your post and try again.
        <?php
        exit();
    }
    
    $c = $_POST[ 'c' ];

    if ( !blogpostcontentvalid( $c ) ) {
        ?>
        <b>You have an error in your post content.</b><br /><br />
        Please make sure that your post is valid and try again.
        <?php
        exit();
    }

    $postid = $_POST[ 'edit' ];
    $blog = GetBlog();

    if ( ValidId( $postid ) ) {
        if ( ( $post = GetPost( $postid ) ) === false ) {
            bc_die( "Invalid post id passed while editing: " . $postid );
        }
        $post->Edit( $n, $c );
        ?><b>Post has been edited</b><br /><?php

    }
    else {
        if( isset( $_POST[ 'quickpost' ] ) ) {
            $c = nl2br( $c );
        }
        $blog->CreatePost( $n , $c );
    }
    
    $bfc->start();
    if( isset( $_POST[ 'quickpost' ] ) ) {
        ?>
        de( "createnewpost" , "" );
        de( "allposts" , "blog/allposts&blogid=<?php
        echo $blog->Id();
        ?>" );
        <?php
    }
    else {
        ?>goblog('<?php
        echo $blog->Id();
        ?>');<?php
    }
    $bfc->end();
?>