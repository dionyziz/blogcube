<?php
    if ( !Element( "element_logged_in" ) ) {
        return false;
    }
    
    $formula = $_POST[ "formula" ];
    $formula = safedecode( $formula );
    $formula = base64_decode( $formula );
    
    $myformula = New Formula( $formula );
    
    bfc_start();
    ?>WYSInsertImage( "http://blogcube.net/<?php if ($debug_version) { ?>beta/<?php } ?>download.bc?t=math&id=<?php
    echo $myformula->PNG(); 
    ?>" );<?php
    bfc_end();
?>