<?php
    if ( !Element( "element_logged_in" ) ) {
        return false;
    }

    $blog = GetBlog(0);
    
    if ( $blog->Description() == "" ) {
        ?><i>(no description specified)</i><br /><br /><?php
        IconLL( Array(
            Array( "Create Description" , "javascript:de2('blog_description_" . $blog->Id() . "','blogging/blog/manage/description/edit',{blogid:'" . $blog->Id() . "'});" , "edit" )
        ) );
    }
    else {
        ?>"<b><?php 
        echo nl2br( htmlspecialchars( $blog->Description() ) ); 
        ?></b>"<br /><br />
        <?php
        IconLL( Array( 
            Array( "Edit Description" , "javascript:de2('blog_description_" . $blog->Id() . "','blogging/blog/manage/description/edit',{blogid:'" . $blog->Id() . "'});" , "edit" )
        ));
    }
?>