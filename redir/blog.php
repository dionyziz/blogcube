<?php
    include "allow.php";
    $redirfields = Array();
    $redirfields[] = Array( "blogid" , $blog->Id() );
    include "redir/redir.php";
?>