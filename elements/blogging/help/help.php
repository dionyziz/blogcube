<?php
    $helpkey = $_POST[ "h" ];
    $target = $_POST[ "t" ];
    $helpitem = New DocumentationItem( $helpkey );

    $title = $helpitem->DocTitle();
    $content = $helpitem->DocContent();

    gui_window2( $title , $content , 0 , -1 , 200 , 10 );
    $bfc->start();
    ?>
    g( '<?php echo $target;
    ?>' ).style.display = '';
    <?php
    $bfc->end();
?>