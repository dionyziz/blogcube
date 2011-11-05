<?php
    $s_username = "";
    $s_password = "";
    session_unregister( "s_username" );
    session_unregister( "s_password" );

    $bfc->start();
    ?>
    username = "";
    cp( "blogging/topbar" );
    cp( "blogging/topbarright" );
    de( "topbar" , "blogging/topbar" , '' , '-' );
    de( "topbarright" , "blogging/topbarright" , '' , '-' );
    <?php
    $bfc->end();

    if( $_POST[ "gologin" ] != "yes" ) {
        h3( "Logged Out" , "button_ok" );
        ?>
        You're now logged out. <br /><br /><?php
            img( "images/nuvola/gohome.png" );
        ?> <a href="javascript:dm('accounts/login','Navigating...');"><?php echo $system; ?> Home Page</a><?php
        bfc_start();
        ?>et( "Logout Successful" );
        cp( "blogging/accounts/login" );
        // pl( "blogging/accounts/login" );<?php
    }
    else {
        bfc_start();
        ?>dm( "accounts/login" );<?php
    }
    bfc_end();
?>