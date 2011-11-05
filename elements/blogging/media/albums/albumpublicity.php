<?php 
    if ( !Element( "element_logged_in" ) ) {
        return false;
    }

    $albumpubl = $this_album->publicity();
    $publ_icons = Array( "everyone" , "friendsoffriends" , "friends" , "meonly" );
?>
    <table class="formtable">
        <tr><td class="ffield"><?php
            img( "images/nuvola/publicity.png" , "Edit album's publicity" , "Edit album's publicity" );
            ?> Edit album's publicity</td>
        </tr>
        <tr><td class="nfield"><?php
        PublicityStatus( $albumpubl );
        ?></td></tr>
    </table>