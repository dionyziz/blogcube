<?php
    if( $anonymous ) {
        $bfc->start();
        ?>dm('accounts/logout&gologin=yes');<?php
        $bfc->end();
        return false;
    }
?>