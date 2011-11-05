<?php
    if ( !Element( "element_logged_in" ) ) {
        return false;
    }
    
    h3( "Create a Blog" , "new48" );
    ?>
<br />
Start by picking a URL for your blog. <br />
<small>You shouldn't use any spaces or other symbols.</small><br />
<br />
<form onsubmit="return buildblog();">
<table class="formtable">
<tr><td>
You and others will use this URL to read and link to your blog.
</td></tr>
<tr><td class="ffield" style="text-align:center">
<b>http:// <input type="text" id="blog_name" class="inpt" /> .blogcube.net/</b><br />
<br /><br />
<?php
    IconLL( Array(
        Array( "Create Blog" , "javascript:void(buildblog());" , "sig" ) , 
        Array( "Cancel" , "javascript:de('main','blogging/hola');" , "discard" )
    ) );
?>
<input type="submit" style="display:none" />
</td></tr></table>
</form>
<br />
<div id="blogcreator"></div>
<?php
$bfc->start();
?>
cacheable = true;
et("New Blog");
g("blog_name").focus();
<?php
$bfc->end();
?>