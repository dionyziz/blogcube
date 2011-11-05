<?php
    // this element can either be included or called directly.

    if ( !isset( $blog ) ) {
        $post = GetPost();
        $_POST['blogid'] = $post->BlogId();
        $blog = GetBlog(0);
    }

    $commentcolours = array(
        New RGBColorTransformation( 123 , 222 , 90  ), // green
        New RGBColorTransformation( 255 , 157 , 8   ), // orange
        New RGBColorTransformation( 238 , 36  , 8   ), // red
        New RGBColorTransformation( 115 , 36  , 115 ), // purple
        New RGBColorTransformation( 82  , 129 , 230 ), // blue
        New RGBColorTransformation( 255 , 226 , 115 ), // yellow
        New RGBColorTransformation( 16  , 36  , 139 ), // dark blue
        New RGBColorTransformation( 255 , 255 , 255 ), // white
        New RGBColorTransformation( 246 , 214 , 148 )  // ochra
    );
    // transformation
    foreach( $commentcolours as $i => $colour ) {
        $commentcolours[ $i ]->Lighten();
    }

    $commentcoloursptr = 0;
    $colours[ '' ] = New RGBColor( 238 , 238 , 238 ); // anonymous color = gray

    ?><br /><br /><?php
    if ( !isset( $numcomments ) ) {
        $numcomments = $post->NumComments();
    }
    if ( $numcomments ) {
        function ShowComments( $commentcolours, $commentcoloursptr , $colours , $post , $commentstree ) {
            global $user;

            $comment = $commentstree->Comment();
            if ( $comment !== false && ( $comment->Active() || $commentstree->ChildrenCount() ) ) {
                $cactive = $comment->Active();
                if ( $cactive ) {
                    if ( !isset( $colours[ $comment->Username() ] ) ) {
                        $colours[ $comment->Username() ] = $commentcolours[ $commentcoloursptr ];
                        $commentcoloursptr++;
                        if( $commentcoloursptr == count( $commentcolours ) ) {
                            $commentcoloursptr = 0;
                        }
                    }
                    $css = $colours[ $comment->Username() ]->CSS();
                }
                else {
                    $css = $colours[ '' ]->CSS();
                }
                ?><div style="border:1px solid #EEEEEE;padding-left:6px;margin:2px 0 2px 0;border-top:5px solid <?php
                echo $css;
                ?>;background-color:#F5F5F5;position:relative;min-height:70px"<?php
                if ( $cactive ) {
                    ?> onmouseover="Comments.OverComment(<?php
                    echo $comment->Id();
                    ?>)" onmouseout="Comments.OutComment(<?php
                    echo $comment->Id();
                    ?>)"<?php
                }
                ?> id="commentbox_<?php
                echo $comment->Id();
                ?>"><?php
                if ( $cactive && $comment->Avatar() ) {
                    ?><div class="commentavatar" style="float:left;width:68px"><?php
                    img( 'download.bc?id=' . $comment->Avatar() , $comment->Username() , $comment->Username() . "'s Avatar" , 64 , 64 );
                    ?></div><?php
                }
                ?><?php
                if ( $cactive ) {
                    ?><b>Comment</b> by <?php 
                    if ( $comment->Username() == '' ) {
                        ?>anonymous<?php
                    }
                    else {
                        ?><a href="" onclick="goblogging('profile/profile_view&user=<?php
                        echo $comment->Username();
                        ?>');return false;"><?php
                        echo $comment->Username();
                        ?></a><?php
                    }
                    ?>, <?php
                    echo $comment->CreateDateH();
                    ?><br /><div style="color:black;padding: 5px 5px 5px 5px;"><?php
                    ?><?php
                    echo $comment->Comment();
                    ?></div><div style="position:absolute;right:2px;bottom:2px;display:none" id="comment_controlbox_<?php
                    echo $comment->Id();
                    ?>">
                    <?php
                    if ( $comment->Username() && $comment->Username() == $user->Username() ) {
                        ?><a href="" onclick="return false;" title="Edit Comment" class="postbutton"><?php
                        img('images/silk/comment_edit.png');
                        ?></a> <a href="" onclick="Comments.KillComment('<?php
                        echo $comment->Id();
                        ?>','<?php
                        echo $post->Id();
                        ?>');return false;" title="Delete Comment" class="postbutton"><?php
                        img('images/silk/comment_delete.png');
                        ?></a> <?php
                    }
                    ?>
                    <a href="" onclick="Comments.CommentBox('comment_reply_to_<?php
                    echo $post->Id();
                    ?>_<?php
                    echo $comment->Id();
                    ?>');return false;" title="Reply to Comment" class="postbutton"><?php
                    img('images/silk/comment_add.png');
                    ?></a></div><?php
                }
                else {
                    ?><div style="float:right;color:#c0c0c0;padding-right:5px;cursor:default">comment deleted <?php
                    img('images/silk/bin_closed.png');
                    ?></div><br /><?php
                }
                ?></div><?php
            }
            // $commentstree->ChildrenCount(); (number of replies under this comment)
            ?><blockquote style="margin:0 0 0 15px;padding:0 0 0 0;clear:both"><?php
            if ( $comment !== false ) {
                ?><div id="comment_reply_to_<?php
                echo $post->Id();
                ?>_<?php
                echo $comment->Id();
                ?>" style="margin:2px 0 2px 0"></div><div id="comment_reply_to_<?php
                echo $post->Id();
                ?>_<?php
                echo $comment->Id();
                ?>_colour" style="display:none"><?php
                if ( isset( $colours[ $user->Username() ] ) ) {
                    echo $colours[ $user->Username() ]->CSS();
                }
                else {
                    echo $commentcolours[ $commentcoloursptr ]->CSS();
                }
                ?></div><?php
            }
            while( $childcomment = $commentstree->Child() ) {
                // recurse!
                ShowComments( $commentcolours, $commentcoloursptr , $colours , $post , $childcomment );
            }
            ?></blockquote><?php
        }
        $comments = new PostCommentsTree( $post->Id() );
        $root = $comments->Root();
        ShowComments( $commentcolours, $commentcoloursptr , $colours , $post , $root );
        ?><br />
        <a href="" onclick="Comments.CommentBox('newcomment_<?php
        echo $post->Id();
        ?>');return false;">Write a comment</a><br /><?php
    }
    ?><div id="newcomment_<?php
    echo $post->Id();
    ?>" style="display:inline"></div><div id="newcomment_<?php
    echo $post->Id();
    ?>_colour" style="display:none"><?php
    if ( isset( $colours[ $user->Username() ] ) ) {
        echo $colours[ $user->Username() ]->CSS();
    }
    else {
        echo $commentcolours[ $commentcoloursptr ]->CSS();
    }
?>
