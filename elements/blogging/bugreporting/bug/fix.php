<?php
    if ( !Element( "element_developer" ) ) {
        return false;
    }

    $bugid = $_POST["bugid"];
    $bugfix = $_POST["bugfix"];
    $curbug = New Bug( $bugid );

    bfc_start();
?>
cp('bugreporting/buglist');
et('Fixed: <?php echo $curbug->Name(); ?>');
dm('bugreporting/bugs&s=<?php
    echo safeencode("&bug_name=" . safeencode($curbug->Name()) . "&bug_highlight=" . $bugid);
?>');
<?php

/* &bug_name=<*?php 
echo safeencode($curbug->Name()); 
?*>&bug_highlight=<*?php 
echo safeencode($curbug->Name()); 
?*> */
    bfc_end();

    img('images/nuvola/done.png');
    if ( $bugfix == 1 ) {
        $curbug->FixBug($user->Id());
        ?> Bug successfully marked as fixed.<br /> <?php
    }
    else {
        $curbug->Fixbug(0);
        ?> Bug successfully marked as unfixed.<br /> <?php        
    }
?>
<br />
Please wait while we're taking you back to the bug list...
