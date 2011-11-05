<?php
    if ( !Element( "element_logged_in" ) ) {
        return false;
    }
    
    // TODO: convert to simple HTML rather than window
    
    $blog = GetBlog();
    
    $content = "After deleting a blog, all the posts and comments on that blog will be lost!
You cannot recover a deleted blog. 
Are you sure that you want to delete the blog named <b>" . $blog->name() . "</b>?<br /><br />
<a href=\"javascript:de('". $target ."','blogging/blog/delete/now&blogid=". $blogid ."');\">Delete Blog</a>
<a href=\"javascript:de('". $target ."','blogging/blog/manage/blog&blogid=". $blogid ."')\">Cancel</a>";
    $z = gui_window2( "Delete Blog?" , $content , $target );
    bfc_start();
    echo $z;
    bfc_end();
?>