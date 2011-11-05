<?php
    if ( !Element( "element_logged_in" ) ) {
        return false;
    }
    
    $blog = GetBlog();
    
    $blog->Delete();
?><h3>Blog Deleted</h3><br />
Your blog was successfully deleted.<br />
<?php
/*    bfc_start();
?>
    de( "myblogs_fblog_<?php echo $blogid; ?>" , "nothing" );
<?php
    bfc_end();
*/
?>