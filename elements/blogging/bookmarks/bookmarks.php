<?php
    if ( !Element( "element_logged_in" ) ) {
        return false;
    }
    
    h3( "Bookmarks" , "bookmarks64" );
    
    bfc_start();
    ?>
    et( "My Bookmarks" );
    <?php
    bfc_end();
    ?>
    <table class="bookmarklist" style="width:50%;">
        <tr>
            <td class="ffield">&nbsp;</td>
            <td class="ffield"><b>Label</b></td>
            <td class="ffield" style="text-align: right;"><b>Creation Date</b></td>
        </tr><?php
            $numbms = 0;
            $bmlist = GetAllBookmarks();
            foreach($bmlist as $thisbookmark ) {
                $numbms++;
                ?><tr id="listbookmark_<?php
                    echo $thisbookmark->Id();
                ?>"><td class="nfield" style="width:36px"><a href="javascript:Bookmarks.EditLabel('<?php
                echo $thisbookmark->Id();
                ?>');"><?php
                img( "images/silk/textfield_rename.png" , "Change Label" , "Change this bookmark's label" , 16 , 16 );
                ?></a> <a href="javascript:Bookmarks.Delete('<?php
                echo $thisbookmark->Id();
                ?>');"><?php
                img( "images/silk/bookmark_delete.png" , "Delete" , "Delete Bookmark" , 16 , 16 );
                ?></a></td><td class="nfield" id="bookmarklabel_<?php
                    echo $thisbookmark->Id();
                ?>"><?php
                echo TextFadeOut( strip_tags( $thisbookmark->Label() ) , $startcolor = &New RGBColor( 0 , 0 , 0 ) , $endcolor = &New RGBColor( 227 , 233 , 249 ) , 30 , 10 );
                ?></td><td class="nfield" style="text-align: right;"><?php
                echo ucfirst( BCDate( $thisbookmark->Date() ) );
                ?></tr><?php
            }
            ?><tr id="listbookmark_hid" <?php
                if ( $numbms ) {
                    ?>style="display:none;"<?php
                }
            ?>><td colspan="4" class="nfield" style="text-align:center;width:500px"><i>There are no bookmarks.</i></td><tr><?php
        ?></table>
    <div id="bookmark_hid" style="display:none;"></div>
    <?php
    $bfc->start();
    ?>
    Bookmarks.SetBmCount('<?php
        echo $numbms;
    ?>');
    <?php
    $bfc->end();
    BCTip( "To add a bookmark, click at the bookmark link at the bottom of the post." );
    ?>