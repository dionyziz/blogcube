<?php
    if ( !Element( "element_logged_in" ) ) {
        return false;
    }

    if (isset($_POST['albumid'])) {
        ob_start();
        include "elements/blogging/media/albums/wysiwyg/show_albumphotos.php";
        $contents = ob_get_clean();
        $title = $albumtitle;
    }
    else {
        $title = 'My albums';
        ob_start();
        include "elements/blogging/media/albums/wysiwyg/showalbums.php";
        $contents = ob_get_clean();
    }
    
    gui_window2( $title , $contents , 'wysiwyg_albums' , -1 , -1 , 10 , 10 , true );
    $bfc->start();
    ?>g('albums_wysiwyg').style.display = '';<?php
    $bfc->end();
?>