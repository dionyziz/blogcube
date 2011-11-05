<?php
    $s_username = $_POST[ "u" ];
    $s_password = $_POST[ "p" ];
    
    $s_username = bcsql_escape( $s_username );
    $s_password = md5( $s_password );
    
    $_SESSION[ "s_username" ] = $s_username;
    $_SESSION[ "s_password" ] = $s_password;
    
    if (isset($_POST[ "t" ])){
        $s_turingnumber = $_POST[ "t" ];
        $_SESSION[ "s_turingnumber" ] = $s_turingnumber;
    }
    
    $errortype = auth();
    
    $bfc->start();
    if ( !$anonymous ) {
        ?>
        cp( "blogging/topbar" );
        cp( "blogging/topbarright" );
        if( returntoblog ) {
            returntoblogid = returntoblog;
            returntoblog = '';
            goblog( returntoblogid );
        }
        else {
            de( "bb" , "blogging/central" );
        }
        username = "<?php
        echo $s_username;
        ?>";
        <?php
    }
    else {
        $_SESSION[ "s_username" ] = "";
        $_SESSION[ "s_password" ] = "";
        $_SESSION[ "s_turingnumber" ] = "";
        ?>
        pl( "blogging/accounts/forgotpassword" );
        g( "login_table" ).style.display = "";
        g( "login_prompt" ).style.display = g( "login_loader" ).style.display = "none";
        username = "";
        <?php
        if ( $errortype == 1 ){
        ?>
        g( "user_invalid" ).style.display = "";
        g( "pass_invalid" ).style.display = g( "turing_invalid" ).style.display = "none";
        <?php    
        } elseif ( $errortype == 2 ){
        ?>
        g( "pass_invalid" ).style.display = "";
        g( "user_invalid" ).style.display = g( "turing_invalid" ).style.display = "none";
        <?php    
        } elseif ( $errortype == 3 ) {
        ?>
        g( "turing_invalid" ).style.display = "";
        g( "user_invalid" ).style.display = g( "pass_invalid" ).style.display = "none";
        <?php    
        } else {
        ?>
        g( "turing_invalid" ).style.display = "none";
        g( "user_invalid" ).style.display = g( "pass_invalid" ).style.display = "none";    
        <?php
        }
        if ( isset( $_SESSION[ "turingenabled" ] ) && $_SESSION[ "turingenabled" ] ) {
        ?>    
            g( "turingtest" ).style.display = "";
            cp ( "blogging/accounts/turingtest" );
            de2( "turingtest" , "blogging/accounts/turingtest", {} );
        <?php    
        }
    }
    $bfc->end();
?>