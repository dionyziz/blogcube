<?php
    $blog = GetBlog(0);
?><br />
<a href="#" class="postbutton"><?php
    img( "images/silk/feed.png" , "feed" , "XML Feed - RSS Syndication" );
?> Blog Feed</a><br /><br />
<h6>Search this Blog</h6><br />
<input type="text" onfocus="this.value=''" onblur="if(this.value=='')this.value='Type to search'" value="Type to search" /><br /><br />
<h6>Archives</h6><br />
<div id="sidebar_archives">
<?php
    $archives = GetArchives($blog->Id());
    foreach($archives as $key => $arch) {
        ?><a href="javascript:de( 'allposts' , 'blog/monthposts&blogid=<?php echo $blog->Id(); ?>&month=<?php echo $key; ?>&start=0' );">
        <?php echo $arch . "</a><br />";
    }
?>
</div>
<iframe id="google_adsense" src="cubism.bc?g=googleadsense" style="border:0px none transparent;height:275px"></iframe><br /><br />
This Blog is powered by <a onclick="goblogging();return false;" class="postbutton" style="color:#3366cc;cursor:pointer" onmouseover="this.style.color='#55ad61'" onmouseout="this.style.color='#3366cc;'">BlogCube</a><br />
<?php
    if( $anonymous ) {
        ?><a onclick="returntoblog='<?php
            echo $blog->Id();
        ?>';goblogging();return false;" class="postbutton" style="color:#3366cc;cursor:pointer" onmouseover="this.style.color='#55ad61'" onmouseout="this.style.color='#3366cc;'">Login</a><?php
    }
?>
