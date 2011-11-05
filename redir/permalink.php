<?php
    include "allow.php";

    $redirfields = Array();
    $redirfields[] = Array( "dm" , $_GET['permalink'] );
    include "redir/redir.php";
?>