<?php
    if ( !Element( "element_logged_in" ) ) {
        return false;
    }

    h3( "Friends"  , "ffolder64" );
    
    function TravelFFolder( $friendscardcode , $allfriendids , &$numfriends , $mytree , $ffolder /* :FriendsTreeFolder */ , $isroot = false , $islast = false ) {
        ?><div class="friendsfolder<?php
        if( $isroot ) {
            $islast = true;
            ?>root<?php
        }
        if( $islast ) {
            ?> friendlast<?php
        }
        ?>"><?php
        
        img( "images/silk/folder_user.png" , "folder" , "Friends Folder" );
        
        ?> <?php
        echo $ffolder->Name();
        
        for( $i = 0 ; $i < $ffolder->FriendsCount() ; $i++ ) {
            $thisfriendid = $ffolder->Subfriend();
            $thisfriend = $mytree->FriendByUserId( $thisfriendid );
            // bc_die('Breakpoint 8095 @' . $thisfriendid);
            $frienduser = $thisfriend->User();
            $friendname = $frienduser->Username();
            $allfriendids[] = $thisfriendid;
            ?><div class="friendsmallcard<?php
            if( $i == $ffolder->FriendsCount() - 1 && !$ffolder->SubfoldersCount() ) {
                ?> friendlast<?php
            }
            ?>" id="friendsmallcard_<?php
            echo $frienduser->Id();
            ?>" onmouseover="Friends.friendcardover(<?php
            echo $frienduser->Id();
            ?>)" onmouseout="Friends.friendcardout(<?php
            echo $frienduser->Id();
            ?>)"><div style="float:right"><a href="" onclick="Friends.removefriend('<?php
            echo $frienduser->Id();
            ?>', '<?php
            echo $friendname;
            ?>');return false;">&times;</a></div><div onclick="javascript:dm('profile/profile_view&user=<?php
            echo $friendname;
            ?>');return false;"><?php
            echo $friendname;
            ?></div></div><?php
            bc_ob_section();
            ?><div class="friendcard friendlast" id="friendcard_<?php
            echo $frienduser->Id();
            ?>"><div class="friendcardavatar"><?php
            if( $frienduser->Avatar() ) {
                img( "download.bc?id=" . $frienduser->Avatar() , "avatar" , $friendname , 64 , 64 );
            }
            else {
                img( "images/nuvoe/mime_unknown64.png" , "avatar" , "No Avatar" , 64 , 64 );
            }
            ?></div><?php
            h4( $friendname );

            echo $frienduser->FirstName();
            ?> <?php
            echo $frienduser->LastName();
            ?></div><?php
            $friendscardcode .= bc_ob_fallback();
            $numfriends++;
        }

        for( $i = 0 ; $i < $ffolder->SubfoldersCount() ; $i++ ) {
            if( $i == $ffolder->SubfoldersCount() - 1 ) {
                $nextlast = true;
            }
            else {
                $nextlast = false;
            }
            TravelFFolder( $friendscardcode , $allfriendids , $numfriends , $mytree , $mytree->FolderById( $ffolder->Subfolder() ) , false , $nextlast );
        }
        ?></div><?php
    }

    $rootfolder = $user->FriendsRootFolderId();
    
    if( $rootfolder == 0 ) {
        ?><table><tr><td><img src="images/nuvola/error.png" /></td><td><h4>Error</h4></td></tr></table><br />We apologise, but a technical problem occured while trying to access your friends list.<br />
        Please contact a BlogCube administrator to solve this problem.<?php
    }
    else {
        $mytree = New FriendsTree( $user->Id() );
        $rootfolder = $mytree->FolderById( $user->FriendsRootFolderId() );
        $numfriends = 0;
        
        TravelFFolder( "" , array() , $numfriends , $mytree , $rootfolder , true , true );
        if( $numfriends == 0 ) {
            ?><br />There are no people in your friends list.<br />
            <small>You can add somebody in your friends by going to his or her profile and clicking "Add as a Friend".</small><?php
        }
        echo $friendscardcode;
    }

    $bfc->start();
    ?>allfriendids = new Array(<?php
    if( count( $allfriendids ) == 1 ) {
        // in case of one item, we cannot construct it using new Array( item ), because this will create an array with /item/ items.
        ?>1);allfriendids[0]=<?php
        echo $allfriendids[ 0 ];
        ?>;<?php
    }
    else {
        echo implode( ',' , $allfriendids );
        ?>);<?php
    }
    ?>
    cacheable = true;
    et( "Friends" );
    <?php
    $bfc->end();
?>
<div id="friend_killer"></div>
