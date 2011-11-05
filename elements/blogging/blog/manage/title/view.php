<?php
    include "elements/element_logged_in.php";
    $blog = GetBlog(0);

    if ( $blog->Title() == "" ) {
          ?><i>(no title specified)</i><?php
          IconLL( Array( 
            Array( "Create Title" , "javascript:de2('blog_title_" . $blog->Id() . "','blogging/blog/manage/title/edit',{blogid:'" . $blog->Id() . "'});" , "edit" )
          ) );
    }
    else {
        ?>"<b><?php echo $blog->Title() ?></b>"<br /><br />
          <acronym title="Modify your blog's title"><?php
          IconLL( Array( 
            Array( "Edit Title" , "javascript:de2('blog_title_" . $blog->Id() . "','blogging/blog/manage/title/edit',{blogid:'" . $blog->Id() . "'});" , "edit" )
          ));
          ?></acronym><?php
    }
?>