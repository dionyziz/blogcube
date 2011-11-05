<?php
    $blog = GetBlog();
    
    $contents = "Insert Image:<br />
URL: <input type=\"text\" value=\"http://\" id=\"postinsertimage_" . $blogid . "\"><br />
<a href=\"javascript:insertImage(" . $blogid . ");\">Insert Image</a>
<a href=\"javascript:downloadBlogElement('" . $target . "','');\">Cancel</a>";

    $z = gui_window( "Insert Image" , $contents , $target , true , false , 0 , false , true );

    $bfc->start();
    echo $z;
    $bfc->end();
?>
