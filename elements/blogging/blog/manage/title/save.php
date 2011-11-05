<?php
    include "elements/element_logged_in.php";
    $blog = GetBlog();
    
    $oldtitle = $blog->Title();
    $blog->SetTitle( $_POST[ "title" ] );
    $bfc->start();
    if ( $blog->Title() != $oldtitle ) {
        // update "My Blogs"
        //echo $display_title;
        if ( $blog->Title() ) {
            $display_title = htmlspecialchars( escapedoublequotes( $blog->Title() ) );
        }
        else {
            $display_title = "<i>" . htmlspecialchars( $blog->Name() ) . "</i>";
        }
        ?>
            newtitle = "<?php echo $display_title; ?>";
            g( "blog_header_title_<?php
                echo $blog->Id(); 
            ?>" ).innerHTML = newtitle; 
        <?php
    }
    ?>
    de2('blog_title_<?php
    echo $blog->Id();
    ?>','blogging/blog/manage/title/view',{blogid:'<?php 
    echo $blogid; 
    ?>'},'','Updating Blog...');
    <?php
    $bfc->end();
?>