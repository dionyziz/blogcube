<?php
    if ( !Element( "element_logged_in" ) ) {
        return false;
    }

    $blog = GetBlog();

?><textarea title="Type your new description here" id="newdescription_<?php 
echo $blog->Id(); 
?>" cols="30" rows="4"><?php
    echo $blog->Description(); ?></textarea><br />
<acronym title="Store the new description and update as necessary"><a href="javascript:de2('savedescription_<?php 
echo $blog->Id(); 
?>','blogging/blog/manage/description/save', {blogid:'<?php 
echo $blog->Id(); 
?>',description:document.getElementById('newdescription_<?php echo $blog->Id(); ?>').value});">Save</a></acronym>
<acronym title="Undo your edits, and keep the old description of your blog"><a href="javascript:de2('<?php 
echo $target; 
?>','blogging/blog/manage/description/view', {blogid:'<?php echo $blog->Id(); ?>'});">Cancel</a></acronym>
<div id="savedescription_<?php echo 
$blog->Id(); 
?>"></div>
<?php
    $bfc->start();
?>
g("newdescription_<?php echo $blog->Id(); ?>").select();
g("newdescription_<?php echo $blog->Id(); ?>").focus();
<?php
    $bfc->end();
?>