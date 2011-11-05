<?php
    if ( !Element( "element_logged_in" ) ) {
        return false;
    }

    $blog = GetBlog();
    
    if ( $_POST[ 'editpost' ] == 'yes' ) {
        if ( $post = GetPost() ) {
            $postedit = true;
        }
    }
    ?><h3 id="blog_header_title_<?php
    echo $blog->Id();
    ?>"><?php
    echo $blog->Title();
    ?></h3><br />
    <small><acronym title="To access your blog, simply go to <?php
    echo $blog->URL();
    ?>">http://<b><?php
    echo $blog->Name();
    ?></b>.<?php
    echo $domainname; // TODO
    ?>/</acronym></small><br /><br /><?php
        echo $arrow;
    ?> <a href="" onclick="blognewpost();return false"><?php
    if ( $postedit ) { // TODO
        ?>Edit Existing<?php
    }
    else {
        ?>New<?php
    }
    ?> Post</a><br />
    <div id="blognewpost"><br />
    <table class="formtable"><tr><td class="ffield"><table style="width:100%"><tr><td style="width:100px"><?php
    img( "images/nuvola/edit.png" , "Title" , "Post Title" );    
    ?> <b>Post Title:</b></td><td><input type="text" id="postname" class="inpt" style="width:100%"<?php 
    if ( $postedit ) {  // TODO
        ?> value="<?php echo str_replace( "\"" , "\\\"" , $post->Title() ); ?>" <?php 
    }
    ?> /></td></tr></table></td></tr><tr><td class="nfield">
    <?php
    Element( "wysiwyg" );
    if ( $postedit ) { // TODO: old magic global, pass as element parameter
        ?><br /><div id="newpostanchrs"><?php
        IconLL( Array( 
            Array( "Publish edited post" , "javascript:buildpost(" . $blog->Id() . "," . $post->Id() . ")" , "write" ) ,
            Array( "Publish as new" , "javascript:buildpost(" . $blog->Id() . ")" , "write" ) /*,
            Array( "Save as Draft" , "javascript:a('Still working on this function! :-)\n\n-dionyziz');" , "save" ) */
        ) );
        ?></div><?php
    } else {
        ?><br /><div id="newpostanchrs"><?php
        IconLL( Array( 
            Array( "Publish Post" , "javascript:buildpost(" . $blog->Id() . ")" , "write" ) /*,
            Array( "Save as Draft" , "javascript:a('Still working on this function! :-)\n\n-dionyziz');" , "save" ) */
        ) );
        ?></div><?php
    }
    ?></td></tr></table>
    <br />
    <div id="newpostcreator" class="inline"></div>
    <?php
    if ( $postedit ) { // TODO
        ?>You might as well want to <a href="" onclick="dm('blog/manage/blog&blogid=<?php
        echo $blog->Id();
        ?>');return false;">Create a New Post</a><?php
    }
    ?>
    </div><br />
    <?php
    echo $arrow;
    ?> <a href="" onclick="blogoldposts();return false">Older Posts</a><br />
    <div id="blogoldposts" style="display:none"><br />
        <table class="formtable">
            <tr>
                <td class="ffield">&nbsp;</td>
                <td class="ffield"><b>Title</b></td>
                <td class="ffield"><b>Content</b></td>
                <td class="ffield"><b>Posted</b></td>
            </tr><?php
                $numposts = 0;
                while( $thispost = $blog->Post() ) {
                    $numposts++;
                    ?><tr><td class="nfield" style="width:36px"><a href="" onclick="dm('blog/manage/blog&blogid=<?php
                    echo $blog->Id();
                    ?>&editpost=yes&postid=<?php
                    echo $thispost->Id();
                    ?>');return false;"><?php
                    img( "images/nuvola/edit.png" , "Edit" , "Edit Post" , 16 , 16 );
                    ?></a> <a href="" onclick="return false"><?php
                    img( "images/nuvola/delete.png" , "Delete" , "Delete this Post" , 16 , 16 );
                    ?></a></td><td class="nfield"><?php
                    echo TextFadeOut( strip_tags( $thispost->Title() ) , $startcolor = &New RGBColor( 0 , 0 , 0 ) , $endcolor = &New RGBColor( 227 , 233 , 249 ) , 30 , 10 );
                    ?></td><td class="nfield"><?php
                    echo TextFadeOut( strip_tags( $thispost->Text() ) , $startcolor , $endcolor , 50 , 10 );
                    ?></td><td class="nfield"><?php
                    echo ucfirst( BCDate( $thispost->Date() ) );
                    ?></tr></td><?php
                }
                if( !$numposts ) {
                    ?><tr><td colspan="4" class="nfield" style="text-align:center;width:500px"><i>There are no previous posts in this blog</i></td><tr><?php
                }
            ?></table>
    </div><br />
    <?php
    echo $arrow;
    ?> <a href="" onclick="blogoptions();return false">Blog Options</a><br />
    <div id="blogoptions" style="display:none"><br />
    <table class="formtable">
        <tr>
            <td class="ffield"><b>Title</b></td>
            <td class="ffield"><div id="blog_title_<?php
            echo $blog->Id();
    ?>"></div></td>
        </tr>
        <tr>
            <td class="nfield"><b>Description</b></td>
            <td class="nfield"><div id="blog_description_<?php
            echo $blog->Id();
    ?>"></div></td>
        </tr>
    </table>
    <?php
        IconLL( Array( 
            Array( "View Blog" , "javascript:goblog(" . $blog->Id() . ")" , "box" ) , 
            Array( "Delete Blog" , "javascript:dm('blog/delete/confirm&blogid=" . $blog->Id() . "');" , "delete" )
        ) );

    ?></div><br /><?php
        /*
        echo $arrow;
    ?> <a href="" id="habitats_link">Habitats</a>
    <div id="habitats" style="display:none">
        Habitats // TODO: PLEASE MAKE THIS PART OF AN UNSTABLE ELEMENT! --dionyziz
    </div> 
    <?php
    */
    $bfc->start();
    ?>
    de2("blog_title_<?php echo $blog->Id(); ?>","blogging/blog/manage/title/view", {blogid:'<?php echo $blog->Id(); ?>'}); 
    de2("blog_description_<?php echo $blog->Id(); ?>","blogging/blog/manage/description/view", {blogid:'<?php echo $blog->Id(); ?>'}); 
    et("<?php
    echo str_replace( "\"" , "\\\"" , $blog->Title() );
    ?>");
    /* Expand.clear();
    Expand.add('habitats'); */<?php
    $bfc->end();
?>
