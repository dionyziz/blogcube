<?php
    if ( !Element( "element_logged_in" ) ) {
        return false;
    }
?>
<table><tr><td style="width:70px;height:70px"><a href="javascript:dm('profile/profile_edit&avatstat=yes')" onmousemove="g('create_avatar').style.visibility='visible'" onmouseout="g('create_avatar').style.visibility='hidden'"><?php
    $avatid = $user->Avatar();
    if ( $avatid == 0 ) {
        img( "images/nuvola/avatar64.png" , "Avatar" );
    }
    else {
        img( "download.bc?id=" . $avatid , "Avatar" , $realtext , 64 , 64 , "border:1px solid #8dafd4" );
    }
?></a></td><td id="usergreeting"><h3 id="greetings"></h3></td></tr>
</table>
<br />
<table>
<tr>
<td colspan="2">
    <div id="create_avatar" style="visibility:hidden"><small><?php
        if( $avatid == 0 ) {
            ?>You haven't added an avatar yet. Create your own personal avatar now!<?php
        }
        else {
            ?>Modify your avatars or create new ones<?php
        }
    ?></small></div>
</td></tr>
</table>
<table>
<tr><td>
<?php
    $myblogs = $user->Blogs();
    if ( $myblogs === false ) {
        // no blogs created
        ?><table style="background-color:#ebf5ff;border:2px solid #a6bfc9;padding:2px"><tr><td>
        <table><tr><td><?php
            img( "images/nuvola/2rightarrow.png" );
        ?></td><td>&nbsp;<h3>Start a blog!</h3></td></tr></table>
        We feel it's about time to create your own personal blog!<br />
        <b>How about starting right now?</b><br /><br />
        <center><table><tr><td><?php
            img( "images/nuvola/newblog.png" );
        ?></td><td><a href="javascript:de('main','blogging/blog/create/new','','Hang on...');">Create a New Blog</a></td></tr></table></center>
        <br />
        Don't have any idea what a blog is? <a href="#">Find out!</a>
        <br /></td></tr></table><br />
        <?php
    }
?>
</td><td style="vertical-align:top;padding-left:5px;">
    <table style="background-color:#EBFFEB;border:2px solid #A6C9BF;"><tr><td style="padding:5px">
    <h4>did you know that...</h4><br /><?php
    $totd = Array(
        "BlogCube client-side code is under heavy development...<br />Therefore, most major features are currently disabled." ,
        "If you're a freak with security, BlogCube is what you need.<br />Use https://www.blogcube.net/ to access it over a secure connection." ,
        "You can have several avatars. When you add a comment, pick which avatar you want to use.<br />Change your avatars from your profile settings." ,
        "You can report BlogCube bugs, so that we can fix them.<br />We thank all of you, beta testers, for your valuable feedback :-)"
    );
    $thistip = ( time() / 3600 ) % count( $totd );
    echo $totd[ $thistip ];
?><br /></td></tr></table>
</td>
<?php 
/*
    <td style="vertical-align:top;padding-left:5px;">
    <table style="background-color:#cee0fc; border:1px solid #92bdfe;">
    <tr><td><b>Debug Features</b>
    <?php 
        $iconlist = Array (
            Array( "My Albums" , "javascript:dm('media/albums/albums');") ,
            Array( "Earth" , "javascript:dm('earth/test');" ),
            Array( "Wind" , "javascript:dm('friends/importfriends');" )
        );
        IconLL( $iconlist );
    ?>
    </td>
    </tr>
    </table>
    <br />
    </td>
    */
?>
</tr></table>
<br />
<div id="wysiwyg_">
</div>
<br /><?php
    $iconlist = Array( 
        Array( "My Blogs" , "javascript:dm('blog/list');" , "write" ) ,
        Array( "Bookmarks" , "javascript:dm('bookmarks/bookmarks');" , "bookmark" ) ,
        Array( "Report a Bug" , "javascript:dm('bugreporting/bug/report/new');" , "bug" )
    );

    IconLL( $iconlist );
    
    if( $user->InvitationsLeft() ) {
        ?><tr><td class="hpitm"><?php 
        img( "images/nuvola/invite.png" , "Invite" , "Invitation" ); 
        ?> You have <?php echo $user->InvitationsLeft(); ?> invitation<?php
        if( $user->InvitationsLeft() > 1 ) {
            ?>s<?php
        }
        ?> left! <a href="javascript:dm('invitations/invitations');">Invite a friend</a>!</td></tr><?php
    }
    
    ?><br /><?php

    // TODO: 
    // Quickpost
    // Friends' Recent Photos
    // Friends' Recent Posts
    $bfc->start();
?>
g('create_avatar').style.visibility='hidden';
g('greetings').innerHTML=tmgreet()+', <?php
    echo $user->Username();
?>!';
cacheable = true;
pl( "blogging/blog/create/new" );
pl( "blogging/blog/list" );
<?php
if( $user->InvitationsLeft() ) {
    ?>
    pl( "blogging/invitations/invitations" );
    <?php
}
?>
pl( "blogging/messaging/panel" );
et("<?php 
    echo $user->Username();
?>");<?php
    $bfc->end();
?>
