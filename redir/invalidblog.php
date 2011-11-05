<?php
    include "allow.php";
    $redirfields = Array();
    $redirfields[] = Array( "bloginvalid" , "yes" );
    $rediralert = "The blog you are trying to access was not found. Perhaps you can check out the BlogCube homepage to see if you can find what you're looking for.";
    include "redir/redir.php";
?>