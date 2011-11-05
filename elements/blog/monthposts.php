<?php
    $blog = GetBlog( 0 );

    $poststotal = 0;
    
    $monthpost = $blog->GetPostsMonth( $_POST['month'] , $_POST['start'] );
    $bfc->start();
    ?>
    <?php
    $alldivs = "";
    for( $x = 0; $x < min($monthpost->Length(), 5); $x++ ) {
        $post = $monthpost->Post( $x );
        $poststotal++;
        $postdate = date("F Y",strtotime($post->Date()));
        ?>
        ay='post_<?php echo $blog->Id(); ?>_<?php echo $post->Id(); ?>';
        aa='blog/post&blogid=<?php echo $blog->Id(); ?>&postid=<?php echo $post->Id(); ?>';
        de(ay,aa,'','Reading Blog Entry...<br />');<?php
        $alldivs .= "<div id=\"post_" . $blog->Id() . "_" . $post->Id() . "\"></div>";
    }
    $bfc->end();
    
    if( !$poststotal ) {
        ?><i>(there are no posts currently on this blog)</i><?php
    }
    else {
        // new comment code
        bc_ob_section();
        ?><div style="border:1px solid #EEEEEE;padding:5px 8px 5px 5px;border-top:5px solid black;background-color:#F0F0F0;position:relative" id="newcommentreal"><div style="float:right"><a href="" onclick="Comments.CommentBox(0);return false;" class="postbutton">&times;</a></div>Write a<?php
        if ( $user->Username() == '' ) {
            ?>n <b>anonymous</b> comment<?php
        }
        else {
            ?> comment as <b><?php
            echo $user->Username();
            ?></b><?php
        }
        ?>:<br /><div style="padding:5px 2px 1px 0;margin:0 0 0 0;"><textarea style="width:100%;" id="commentarea" row="6"></textarea></div><div style="text-align:right"><input type="button" value="Post comment!" onclick="Comments.QuickComment()" style="border-color:#205083;padding-left:20px;background-position:left;background-repeat:no-repeat;background-image:url(images/silk/comment_add.png)" id="commentgobutton" /><div id="comment_poster" style="display:none"></div></div></div></div><?php
        $newcommentcode = bc_ob_fallback();
        $bfc->start( false , false );
        ?>Comments.NewCommentCode='<?php
        echo escapesinglequotes($newcommentcode);
        ?>';<?php
        $bfc->end();
    }
    echo $alldivs;
    ?><br /><div id="comment_killer" style="display:none"></div>
    <div style="float:left;">
    <?php
    if( $monthpost->Length() == "6" ) {
        $newstart = $_POST['start'] + 5;
        ?><a href="javascript:de( 'allposts' , 'blog/monthposts&blogid=<?php 
        echo $blog->Id();
        ?>&month=<?php
        echo $_POST['month'];
        ?>&start=<?php 
        echo $newstart; 
        ?>' );"><?php
        img( 'images/silk/back.png' , 'Back' , 'Older Posts' );
        ?> Older Posts</a>
        <?php
    }
    ?>
    </div>
    <div style="text-align:right">
    <?php
    if( $_POST['start'] != "0" ) {
        $newstart = $_POST['start'] - 5;
        ?><a href="javascript:de( 'allposts' , 'blog/monthposts&blogid=<?php
        echo $blog->Id(); ?>&month=<?php
        echo $_POST['month']; ?>&start=<?php
        echo $newstart; ?>' );">Newer Posts <?php
        img( 'images/silk/forward.png' , 'Forward' , 'Newer Posts' );
        ?></a><?php
    }
    ?>
    </div>
    <div style="clear:both"></div>
    <div id="bookmark_hid" style="display:none"></div><?php
    if( $blog->IsMine() ) {
        IconLL( Array( 
            Array( "New Post" , "javascript:goblogging('blog/manage/blog&blogid=" . $blog->Id() . "')" ) ,
            // if you are going to update this text, also update /js/more/posts
            Array( "Quick Post" , "javascript:createnewpost()" , "" , "addnewpostanchor" ) 
        ) );
        ?><div id="createnewpost" style="display:none;" class="inline"><br />
            <?php
                h3( "Quick Post" );
            ?>
            Post Title: <input type="text" id="newpostname" /><br /><br />
            <textarea rows="10" cols="50" id="postinserttext" class="blogcute" wrap="hard"></textarea><br />
            <br /><?php
            IconLL( Array(
                Array( "Publish Post" , "javascript:buildquickpost('" . $blog->Id() . "')" ) ,
                Array( "Cancel" , "javascript:cancelnewpost();" )
            ) );
            ?>
            <input type="submit" style="display:none" />
            <div id="newpostcreator"></div>
            <a name="newpostanchor"></a>
            </div>
        <?php
    }
?>
