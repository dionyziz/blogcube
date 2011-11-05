<?php
    $blog = GetBlog(0);
    $post = GetPost();
    $theuser = New User( $post->UserId() );
    $isbookmarked = IsBookmarked($post->Id());
    if ( $isbookmarked ) {
        $curbookmark = GetBookmarkByPostId($post->Id());
        $bmid = $curbookmark->Id();
    }
    ParseDate( $post->Date() , $year , $month , $day , $hour , $minute , $second );
?><br />
<div id="bookmark_postbmid_<?php
    echo $post->Id();
    ?>" style="display:none;"><?php
    if ( $isbookmarked ) {
        echo $bmid;
    }
    ?></div><div id="bookmarkbox_<?php
    echo $post->Id();
    ?>" style="<?php
    if ( !$isbookmarked ) {
        ?>display:none;<?php
    }
    ?>float:right;border:1px solid #cccccc;background-color:#F5F5F5;"><div style="display:table-cell;padding: 10px 20px 10px 20px;font-size:90%"><div id="bookmark_label_<?php
    echo $post->Id();
    ?>" style="display:inline"><?php
    if ( $isbookmarked ) {
        echo $curbookmark->Label();
    }
    ?></div><div id="bookmark_editlabel_<?php
    echo $post->Id();
    ?>" style="display:none;">
    <input id="bookmark_newlabel_<?php
    echo $post->Id();
    ?>" type="text" size="40" value="<?php
    if ( $isbookmarked ) {
        echo $curbookmark->Label();
    }
    ?>" onmouseover="this.style.borderColor = '#55ad61';" onmouseout="this.style.borderColor = '#3366cc';" style="border-color: #3366cc;" />
    <div id="bookmark_buttons_<?php
    echo $post->Id();
    ?>" style="text-align:right;"><a href="" onclick="Bookmarks.DropEditLabelSave('<?php
    echo $post->Id();
    ?>');return false;" class="bloglinks">Save</a>
    <a href="" onclick="Bookmarks.DropEditLabelCancel('<?php
    echo $post->Id();
    ?>');return false;" class="bloglinks">Cancel</a> </div>
    </div></div><div id="bookmark_dropdown_<?php
    echo $post->Id();
    ?>" style="padding: 10px 2px 10px 2px;border-left:1px dotted #cccccc;<?php
    if (!$isbookmarked) {
        ?>display: none;<?php
    }
    else {
        ?>display:table-cell;<?php
    }
    ?>">
    <div id="bookmark_indropdown_<?php 
    echo $post->Id();
    ?>"><?php
        DropDownMenu( array(
        array( 'caption' => 'Rename bookmark' , 'js' => "Bookmarks.DropEditLabel('" . $post->Id() . "')", 'image' => 'images/silk/textfield_rename.png' ),
        array( 'caption' => 'Delete Bookmark' , 'js' => "Bookmarks.DropDelete('" . $post->Id() . "')", 'image' => 'images/silk/bookmark_delete.png' )
        ) );
    ?></div></div>
</div>
<span class="blogdate"><?php
    echo FullDate( $post->Date() );
?></span><span class="blogposttitle"><?php
    ?><div id="bookmarkheart_<?php
        echo $post->Id();
    ?>" style="display: <?php
    if ( $isbookmarked ) {
    ?>inline<?php
    }
    else {
    ?>none<?php
    }
    ?>;"><?php
    img('images/silk/bookmark.png');
    ?> </div><div style="display: inline;"><?php
    echo $post->Title();
    ?></div></span><br /><span class="blogtext">
<?php
    echo BlogCute( $post->Text() );
?></span><br /><br />
<div class="bpsig">
<span class="inline postedby">Posted by <a href="" onclick="goblogging('profile/profile_view&user=<?php
    echo $theuser->Username();
    ?>');return false;"><?php
    echo $theuser->Username();
    ?></a> at <b><?php 
    echo $hour;
    ?>:<?php
    echo $minute;
    ?></b> (UTC)</small></span> <a href="" onclick="<?php
    $numcomments = $post->NumComments();
    if( !$numcomments ) {
        ?>Comments.CommentBox('newcomment_<?php
        echo $post->Id();
        ?>');<?php
        $tip = "Write a comment on this post";
    }
    else {
        ?>Posts.CommentsToggle(<?php
        echo $post->Id();
        ?>);<?php
        $tip = "View all comments that have been made on this Post and write your own";
    }
    ?>return false;" class="bloglinks"><?php
    img( 'images/silk/comment.png' , "Comments" , $tip );
    if( $numcomments ) {
        ?><div id="postnumc_<?php
        echo $post->Id();
        ?>" style="display:inline"><?php
        echo pluralize( 'Comment' , $numcomments );
        ?></div><?php
    }
    else {
        ?>Write Comment<?php
    }
    ?></a><?php
    if ( !$anonymous ) {
        ?> <div id="bookmarkbutton_<?php
        echo $post->Id();
        ?>" style="display:<?php
        if ( $isbookmarked ) {
            ?>none<?php
        }
        else {
            ?>inline<?php
        }
        ?>;"><a href="" onclick="Bookmarks.Add('<?php
        echo $post->Id();
        ?>');return false;" class="bloglinks"> <?php
        img('images/silk/bookmark_add.png', "Bookmark", "Add this Post to your Bookmarks");
        ?> Bookmark</a></div><?php
    }
    if( $blog->IsMine() ) {
        ?> <a href="" onclick="editpost('<?php
        echo $blog->Id();
        ?>','<?php 
        echo $post->Id(); 
        ?>');return false;" class="bloglinks"><?php
        img('images/silk/post_edit.png', "Edit", "Edit this Post");
        ?> Edit</a> <a href="" onclick="deletepost('<?php 
        echo $blog->Id();
        ?>','<?php
        echo $post->Id(); 
        ?>');return false;" class="bloglinks"><?php
        img('images/silk/post_delete.png', "Delete", "Delete this Post");
        ?> Delete</a><?php
    }
    
    ?><div class="inline" id="postcomments_<?php
    echo $post->Id();
    ?>"><?php

    include "elements/blog/comments.php";

    ?></div></div>