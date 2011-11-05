<?php
    /*
        Element: earthform
        Earth Developers: Makis, Dionyziz
    */
    if ( !Element( "element_logged_in" ) ) {
        return false;
    }

    // in-function element, remember to globalize any BlogCube-wide variable we use
    if( $earthcall !== true )
        bc_die( "Access Denied" );
    
    bfc_start();
    ?>earthpostsubmit='<?php
    echo escapesinglequotes( $earthpostsubmit );
    ?>';earthpostupload='<?php
    echo escapesinglequotes( $earthpostupload );
    ?>';<?php
    bfc_end();
?>
<div style="background-color:<?php
    echo $earthbackgroundcolor;
?>;color:<?php
    echo $earthforegroundcolor;
?>">
<div id="earthformp" class="inline">
<iframe src="cubism.bc?g=earth&bgc=<?php
    echo safeencode( $earthbackgroundcolor );
?>&fgc=<?php
    echo safeencode( $earthforegroundcolor );
?>" id="ifrearth" frameborder="no" style="height:300px;width:100%"></iframe>
</div>
<div id="earthprogp" class="inline"></div>
<div id="earthprogptmp"></div>
</div>