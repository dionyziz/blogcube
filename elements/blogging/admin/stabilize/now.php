<?php
    if ( !Element( "element_developer" ) ) {
        return false;
    }
    
    if ( $_POST['public'] == 'yes' ) {
        Stabilize(true);
    }
    else {
        Stabilize();
    }
    
    img('images/nuvola/done.png');
?> Stabilization successfully completed <?php
$bfc->start();
?>
et("Stabilization successfully completed");
<?php
$bfc->end();