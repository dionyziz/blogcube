<?php
    if ( !Element( "element_logged_in" ) ) {
        return false;
    }

    $bfc->start();
?>
    et("Upload your files");
<?php
    $bfc->end();
?>
<iframe src="<?php echo $systemurl."/cubism.bc?g=" ?>media/uploadformmedia" frameborder="no" style="height:300px;width:100%">
</iframe>
