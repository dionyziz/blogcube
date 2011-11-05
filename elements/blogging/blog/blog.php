<?php
    // is this element being used?
    if ( !Element( "element_logged_in" ) ) {
        return false;
    }
    
    if( $showingblogs !== true ) {
        bc_die( "Element not directly accessible" ); // /elements/blogging/blog/list
    }

    ?><div class="blogpanel" id="viewblogpanel_<?php
    echo $blog->Id();
    ?>"><table style="width:100%"><tr><td style="width:100%">
    <div style="float:left;margin-right:4px;margin-top:4px;margin-bottom:4px;"><?php
        img( "images/nuvola/blog64.png" , "Blog" , $blog->Name() , 64 , 64 ); 
    ?></div><div style="float:right;font-weight:bold"><a href="javascript:blogdelete('<?php
    echo $blog->Id();
    ?>')" style="text-decoration:none" title="Delete this Blog">&times;</a></div>
    <div style="padding-top:4px"><h4 title="<?php
    // TODO: htmlspecialchars() on title!
    echo $blog->Title();
    ?>"><?php
    if( $defaultavatar ) {
        $bgcolor = New RGBColor( 227 , 233 , 249 );
    }
    echo TextFadeOut( $blog->Title() , New RGBColor( 59 , 122 , 191 ) , $bgcolor , 18 );
    ?></h4><br /><span style="font-size:85%">Created <?php
    echo BCDate( $blog->CreateDate() );
    ?></span>
    <br /><a href="javascript:goblog(<?php 
    echo $blog->Id();
    ?>);">View Blog</a><br />
    <a href="javascript:dm('blog/manage/blog&blogid=<?php 
    echo $blog->Id();
    ?>');">Details</a>
    </div></td></tr></table></div>