<?php
    if ( !Element( "element_logged_in" ) ) {
        return false;
    }

    h3( "My Blogs" , "sig64" );

    $myblogs = $user->Blogs();
    if ( $myblogs !== false ) {
        $showingblogs = true;
        for ( $i = 0 ; $i < $myblogs->Length() ; $i++ ) {
            $blog = $myblogs->Blog( $i );
            include "elements/blogging/blog/blog.php";
        }
        $showingblogs = false;
    }
    ?><div class="blogpanel_new" id="viewblogpanel_new"><table style="width:100%"><tr><td style="width:100%">
    <div style="float:left;margin-right:4px;margin-top:4px;margin-bottom:4px;"><?php
        img( "images/nuvola/new48.png" , "New Blog" , "New Blog" , 48 , 48 ); 
    ?></div>
    <div style="padding-top:4px"><h4 title="Create a New Blog">New Blog</h4><br />
    <?php
    if( $myblogs === false ) {
        ?><br /><span style="font-size:85%">You haven't created a blog yet...</span><?php
    }
    ?>
    <br /><?php
        echo $arrow;
    ?> <a href="javascript:dm('blog/create/new','One moment...')">Create a New Blog</a><br />
    </div></td></tr></table></div><?php
    bfc_start(); ?>
    cachable = true;
    pl( "blogging/blog/create/new" );
    et( "My Blogs" );
    <?php
    bfc_end();
?>
