<?php
    if ( !Element( "element_logged_in" ) ) {
        return false;
    }

    $n = $_POST[ "n" ];
    
    switch( CreateBlog( $n ) ) {
        case -1:
            ?><b>You have specified an invalid name for your blog.</b><br /><br />
            Blog names may only contain lower and upper case english characters, numbers
            and the minus symbol (for example my-blog). All blog names should be at least three characters
            long. Please try again.<?php
            break;
        case -2:
            ?><b>The blog name you specified already exists.</b><br /><br />
            Another user has already created a blog with the same name as the one
            you specified. Please pick another name and try again.<?php
            break;
        case -3:
            ?><b>The blog name you specified is reserved.</b><br /><br />
            You may not this blog name, as it has been reserved. Please try
            another name.<?php
            break;
        default:
            $bfc->start();
            ?>
            cp( "blogging/blog/list" );
            cp( "blogging/hola" );
            dm( "blog/list" );
            <?php
            $bfc->end();
    }
?>