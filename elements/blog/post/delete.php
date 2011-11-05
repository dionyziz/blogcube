<?php
    if ( !Element( "element_logged_in" ) ) {
        return false;
    }

    $blogid = $_POST["blogid"];
    $postid = $_POST["postid"];
    $curpost = New Post($postid);
    $curpost->Delete();
    bfc_start();
?>
    de("allposts","blog/allposts&blogid=<?php
    echo $blogid;
    ?>","","Updating Blog...");
<?php
    bfc_end();
?>