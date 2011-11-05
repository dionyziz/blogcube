<?php
    $parid = $_POST['parid'];
    $postid = $_POST['postid'];
    $comment = $_POST['c'];
    $comment = htmlspecialchars( $comment );

    CommentPostSubmit( $postid , CommentSubmit( $comment , $parid ) );

    $bfc->start();
    ?>g('commentgobutton').disabled = false;
    g('commentgobutton').value = 'Post comment!';
    de2('postcomments_<?php
    echo $postid;
    ?>', 'blog/comments', {
        postid: <?php
        echo $postid;
        ?>
    }, 'Updating...');
    Comments.CommentsIncrease('<?php
    echo $postid;
    ?>',1);<?php
    $bfc->end();    
?>