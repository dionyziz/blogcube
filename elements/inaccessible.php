<?php
    $content = "
The window you are trying to restore could not be retrieved.<br />This window is not accessible for a certain reason. If you are trying to access a specific blog, this might be because the blog has been moved, or deleted. You should also make sure that you are still logged in.";
    $z = gui_window( "Window Inaccessible" , $content , $target );
    bfc_start();
    echo $z;
    bfc_end();
?>