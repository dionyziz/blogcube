<?php
    $name = $_POST[ "name" ];
    switch( $name ) {
        case "started":
            ?><h3>Getting Started</h3><?php gui_hide(); ?><br />
            To start a new blog on <?php echo $system; ?> you will first
            need to create an account. <?php echo $system; ?> is a trusted
            community and we won't allow any visitor to register for an account.
            In order to be able to register, you will have to find someone
            who is already a member of the community.<br /><br />If you
            don't know somebody who owns a blog here, you will most probably
            meet someone soon (or you can take a look at the blogs and contact
            someone you meet here). Once you get to know someone who owns 
            a blog here, ask them for an invitation to register for an account
            and create your own blog.
            <?php
            break;
    }
?>