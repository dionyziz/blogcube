<?php
    if ( !Element( "element_logged_in" ) ) {
        return false;
    }

    $blog = GetBlog();
    
    $olddescription = $blog->Description();
    $blog->SetDescription( $_POST[ "description" ] );
    $bfc->start();
    ?>
    de2('blog_description_<?php 
    echo $blog->Id(); 
    ?>','blogging/blog/manage/description/view', {blogid:'<?php 
    echo $blog->Id(); 
    ?>'},'','Updating Blog...');
    <?php
    $bfc->end();
?>