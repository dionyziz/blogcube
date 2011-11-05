<?php
    $blog = GetBlog(0);
?>
<table id="blogtbl"><tr>
<td class="sidebarcontainerleft">
    <div id="sidebarleft">
    </div>
</td>
<td class="aielposts">
<div id="blogcentral">
    <span class="blogtitle"><?php
        echo $blog->Title();
    ?></span><h5><?php
        echo nl2br( $blog->Description() );
    ?></h5>
    <div id="allposts">
    </div>
    <div id="uncertain_position"></div>
    <br />
    <div id="blogcopyright">
    BlogCube is currently in early development stages; expect unstable behaviour
    </div>
</div>
</td><td class="sidebarcontainerright">
    <div id="sidebar">
        loading...
    </div>
</td></tr></table>
<?php
    bfc_start();
?>
de( "allposts" , "blog/posts&blogid=<?php echo $blog->Id(); ?>&start=0" );
de( "sidebar" , "blog/sidebar&blogid=<?php echo $blog->Id(); ?>" );
et( '<?php
    if( $blog->Title() ) {
        echo escapesinglequotes( $blog->Title() );
    }
    else {
        echo escapesinglequotes( $blog->Name() );
    }
?>' );
CSSLoad( "style.css.bc?blogid=1" );
<?php
    bfc_end();
?>