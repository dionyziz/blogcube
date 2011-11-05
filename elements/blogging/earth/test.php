<?php
    if ( !Element( "element_logged_in" ) ) {
        return false;
    }
    
    bfc_start();
    ?>et("Earth Upload Test");<?php
    bfc_end();
    
    ?>Feel free to try out file uploading here.<br /><?php
    
    EarthGo( "earthexppostsubmit" , "earthexppostupload" , "#e3e9f9" , "black" );
?>