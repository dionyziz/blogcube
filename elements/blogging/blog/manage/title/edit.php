<?php
    include "elements/element_logged_in.php";
    $blog = GetBlog();

?><input type="text" value="" title="Type the new title here" id="newtitle_<?php 
echo $blog->Id(); 
?>" />
<acronym title="Store the new title for your blog and update as necessary"><a href="" onclick="de2('savetitle_<?php
echo $blog->Id();
?>','blogging/blog/manage/title/save', {blogid:'<?php
echo $blog->Id();
?>', title:g('newtitle_<?php
echo $blog->Id();
?>').value},'','Saving...');return false;">Save</a></acronym>
<acronym title="Undo your edits and keep the old title '<?php
echo $blog->Title();
?>' for your blog"><a href="" onclick="de2('<?php
echo $target;
?>','blogging/blog/manage/title/view',{blogid:'<?php
echo $blog->Id();
?>'});return false;">Cancel</a></acronym>
<div id="savetitle_<?php
echo $blog->Id();
?>"></div>
<?php
    $bfc->start();
?>
(x=g("newtitle_<?php 
    echo $blog->Id();
?>")).value = "<?php 
    // has to be done via JS because the title may contain " and ' at the same time
    echo escapedoublequotes( $blog->Title() ); 
?>";
x.select();
x.focus();
<?php
    $bfc->end();
?>