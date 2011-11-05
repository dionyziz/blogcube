<?php
    if ( !Element( "element_logged_in" ) ) {
        return false;
    }

?><h4>Create Album</h4>
<iframe src="<?php echo $systemurl."/cubism.bc?g=" ?>media/albums/uploadformphoto" frameborder="no" style="height:300px;width:100%">
</iframe><br /><br />
<?php img( "images/nuvola/tip.png" ); ?>You haven't created any albums. Upload a photo to automatically create one.
    <!--
        <select id="album_publ_stat" onchange="album_savepubl();">
            <option value="public" style="background-image:url('images/nuvoe/publicity_everyone.png');" selected="selected">Everyone</option>
            <option value="ffriends" style="background-image:url('images/nuvoe/publicity_friendsoffriends.png');" selected="selected">Friends of Friends</option>
            <option value="friends" style="background-image:url('images/nuvoe/publicity_friends.png');" selected="selected">Friends</option>
            <option value="private" style="background-image:url('images/nuvoe/publicity_meonly.png');" selected="selected">Me only</option>
        </select>
    //-->