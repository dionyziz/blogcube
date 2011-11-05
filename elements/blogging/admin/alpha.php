<?php
    if ( !Element( "element_logged_in" ) ) {
        return false;
    }

    if ( !in_array( $user->Username() , $permissions_logs[ 'alpha' ] ) ) {
        img( "images/nuvola/error64.png" , "Error" );
        ?>This page cannot be displayed, because access to it has been restricted.<?php
        return false;
    } //if someone is not allowed to see even #alpha, he won't be allowed to see anything else, either.
    
    $logroom = $_POST[ 'r' ];
    if ( !isset( $logroom ) || !in_array( $logroom, array('alpha', 'blogcube', 'developers') ) )
        $logroom = 'alpha';
    if ( !( $alphadates = IRCLogList( $logroom ) ) ) {
        bc_die( 'Error while listing...' );
    }
    $bfc->start();
    ?>et('LogViewer');<?php
    $bfc->end();

    h3( "IRC Logs (#$logroom)" );
    if ( in_array( $user->Username() , $permissions_logs[ 'developers' ] ) ) { 
        ?><table width="100%"><tr>
        <td><?php echo $arrow; ?><a href="javascript:dm('admin/alpha&r=alpha');">#alpha</a></td>
        <td><?php echo $arrow; ?><a href="javascript:dm('admin/alpha&r=blogcube');">#blogcube</a></td>
        <td><?php echo $arrow; ?><a href="javascript:dm('admin/alpha&r=developers');">#developers</a></td>
        </tr></table><br /><?php 
    }
    ?>
    <table width="100%"><tr><td style="vertical-align:top; border-right: 1px solid #3b7abf;" width="16%">
    <center><h4>Dates</h4></center>
    <table class="formtable"><?php
    foreach ( $alphadates as $alphadate ) {
        ?><tr><td><a href="javascript:de('irclog','blogging/admin/alphaview&r=<?php echo $logroom ?>&item=<?php echo $alphadate; ?>','')"><?php echo $alphadate; ?></a></td><?php
        ?></tr><?php echo "\n";
    }
    ?></table>
    </td><td style="vertical-align:top;"><div id="irclog" width="100%"><h5>No date date selected</h5></div></td></tr></table><?php
?>
