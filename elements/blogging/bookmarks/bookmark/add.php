<?php
    if ( !Element( "element_logged_in" ) ) {
        return false;
    }

    $userid = $user->Id();
    
    $postid = $_POST["postid"];
    $bmlabel = $_POST["bmlabel"];
    
    $newbmid = CreateBookmark($postid,$bmlabel);
    
    $bfc->start();
    ?>
    g('bookmark_label_<?php
    echo $postid;
    ?>').innerHTML = TextFadeOut('<?php
    echo $bmlabel;
    ?>',"","",30,10);
    fade0('bookmark_dropdown_<?php
    echo $postid;
    ?>');
    g('bookmark_dropdown_<?php
    echo $postid;
    ?>').style.display = 'table-cell';
    fadein('bookmark_dropdown_<?php
    echo $postid;
    ?>');
    g('bookmark_postbmid_<?php
    echo $postid;
    ?>').innerHTML = '<?php
    echo $newbmid;
    ?>';
    fade0('bookmarkheart_<?php
    echo $postid;
    ?>');
    g('bookmarkheart_<?php
    echo $postid;
    ?>').style.display = 'inline';
    fadein('bookmarkheart_<?php
    echo $postid;
    ?>');
    <?php
    $bfc->end();
?>