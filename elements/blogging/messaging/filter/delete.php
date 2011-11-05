<?php
    if ( !Element( "element_logged_in" ) ) {
        return false;
    }

    $curfilterid = $_POST["filterid"];
    $allfilters = New Filters;
    $result = $allfilters->RemoveFilter($curfilterid);
    if ( $result == true ) {
        img('images/nuvola/done.png'); ?> Filter successfully deleted. <?php
        bfc_start();?>
        setTimeout('fadeout("viewfilterpanel_<?php echo $curfilterid; ?>");',2000);
        setTimeout('decheight(<?php echo $curfilterid; ?>);',4000);
        <?php
        bfc_end();
    }
    else {
        $errtext = $allfilters->GetErrorText();
        img('images/nuvola/error.png');
        echo $errtext; 
    }
    bfc_start();
?>
setTimeout('fadeout("msg_filterview");',2000);
<?php
    bfc_end();
?>