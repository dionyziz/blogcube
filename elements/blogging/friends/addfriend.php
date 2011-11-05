<?php
    if ( !Element( "element_logged_in" ) ) {
        return false;
    }

    $nfriend = $_POST[ "char" ];
    $thisuser = GetUserByUsername( $nfriend );
    $res = AddFriend( $thisuser->Id() , $user->FriendsRootFolderId() );
    if ( $res > 0 ) {
        img( "images/nuvola/done.png" ); 
        ?> User <?php 
        echo $nfriend;
        ?> was successfully added as your friend.<?php
    }
    else {
        img( "images/nuvola/error.png" );
        ?> <?php
        if ( $res == -1 ) {
            ?>This user is already a friend of yours.<?php
        }
        elseif ( $res == -2 ) {
            ?>You can't add yourself as a friend of yours.<?php
        }
        else {
            ?>Wicked friends error.<?php
        }
    }
?>