<?php
    if( $anonymous ) {
        if( $debug_version ) {
            img( "images/nuvola/misc.png" );
            ?> Unstable Version<?php
        }
    }
    else {
        ?><a href="javascript:dm('hola');">Home</a> | 
        <a href="javascript:dm('blog/list');">Blogs</a> |
        <a href="javascript:dm('friends/friends');">Friends</a> |
        <a href="javascript:dm('media/albums/albums');">Albums</a> |
        <a href="javascript:dm('bookmarks/bookmarks');">Bookmarks</a> |
        <a href="javascript:dm('profile/profile_edit');">Settings</a><?php
        $hasadmin = false;
        if( $user->IsDeveloper() || in_array( $user->Username() , $permissions_logs['alpha'] ) ) {
            $hasadmin = true;
            ?> | <a href="javascript:dm('admin/king');">Admin</a><?php
        }
    }
    $bfc->start()
    ?>cacheable = true;<?php
    if( $hasadmin ) {
        ?>pl( "blogging/admin/king" );<?php
    }
    $bfc->end();
?>