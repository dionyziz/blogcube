<?php
    $contents = "Jump to Page:<br />";
    
    for ( $i = 1 ; $i < 20 ; $i++ ) 
        $contents .= "<a href=\"javascript:downloadBlogElement( 'page' , 'blog/item&index=" . $i . "' )\">" . $i . "</a>";
        
    $contents .= "<br /><img src=\"images/injection/";
    $contents .= $index;
    $contents .= ".jpg\" />";
    gui_window( "Injection Page " . $index , $contents , $target );
?>