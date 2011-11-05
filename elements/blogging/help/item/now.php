<?php
    if ( !Element( "element_developer" ) ) {
        return false;
    }

$bfc->start();
echo "cacheable = false;";
$bfc->end();

    //$k = gui_window( "New Item" , "This is my first BlogCube window!" , "helphidden" );
    //bfc_start();
    //echo $k;
    //bfc_end();
    $i = $_POST[ "i" ]; // id
    $t = $_POST[ "t" ]; // title
    $k = $_POST[ "k" ]; // key
    $c = $_POST[ "c" ]; // content
    if( $i == 0 ) {
        $folder = New DocFolder();
    } 
    else {
        $folder = New DocFolder( $i );
    }
    
    if( $folder->AddDocumentationItem($k,$t,$c) ) {
        img ( "images/nuvola/done.png" , "Done" );
        ?>Your item has been successfully created<?php
        bfc_start();
        ?>
        g( "helpnewitem" ).innerHTML = "";
        <?php
        bfc_end();
    }
    else {
        img ( "images/nuvola/discard.png" , "Failed" );
        ?>Seems an item with the same key is allready existing<?php
    }
    
?>