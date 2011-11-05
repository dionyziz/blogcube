<?php
    include "elements/element_developer.php";

$bfc->start();
echo "cacheable = false;";
$bfc->end();

    $i = $_POST[ "i" ]; // id
    $n = $_POST[ "n" ]; // name
    $k = $_POST[ "k" ]; // key
    if( $i == 0 ) {
        $folder = New DocFolder();
    } 
    else {
        $folder = New DocFolder( $i );
    }
    
    if( $folder->AddFolder($k,$n) ) {
        img ( "images/nuvola/done.png" , "Done" );
        ?>Your subfolder has been successfully created<?php
        bfc_start();
        ?>
        g( "helpnewsubfolder" ).innerHTML = "";
        <?php
        bfc_end();
    }
    else {
        img ( "images/nuvola/discard.png" , "Failed" );
        ?>Seems a subfolder with the same key is allready existing<?php
    }
    
?>