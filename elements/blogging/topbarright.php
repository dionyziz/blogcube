<?php
    if ( !$anonymous ) {
        ?>
        <a href="javascript:dm('profile/profile_edit');"><?php
            img( "images/nuvola/profile.png" , "Profile" , "View and edit your profile" );
        ?></a>
        Logged in as <a title="Manage your account" href="javascript:dm('profile/profile_view&user=<?php 
        echo $user->Username();
        ?>');"><div id="username" class="inline"><?php
        echo $user->Username();
        ?></div></a>: 
        <a href="" onclick="javascript:dm('messaging/panel');return false;" 
           onmousemove="(x=g('topbar_info')).innerHTML='View your messages, or compose a new message';x.style.visibility='visible';" 
           onmouseout="g('topbar_info').style.visibility='hidden';"><?php
            if( $m = GetNewMessages( $user->Id() ) ) {
                $src = "img.png.bc?id=messages&n=" . $m;
            }
            else {
                $src = "images/nuvola/email.png";
            }
            img( $src , "Messaging" , "Messaging" , 16 , 16 );
        ?></a>
        <a href="" onclick="javascript:dm('accounts/logout','Logging Out...'+((g('topbar_info').style.visibility='hidden')==''?'':''));return false;" 
           onmousemove="(x=g('topbar_info')).innerHTML='Log out from <?php echo $system; ?>';x.style.visibility='visible';" 
           onmouseout="g('topbar_info').style.visibility='hidden';"><?php
            img( "images/nuvola/logout.png" , "Log out" );
        ?></a><?php
    }
    else {
        ?><a href="" onclick="de('main','blogging/accounts/login','');return false;">Log In</a><?php
    }
    $bfc->start()
    ?>cacheable = true;<?php
    $bfc->end();
?>
