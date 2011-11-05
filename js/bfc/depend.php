<?php
    // List of BFC dependencies and libraries
    
    $bfc->RegisterLibrary( 'friends' );
    $bfc->RegisterLibrary( 'md5' );
    $bfc->RegisterLibrary( 'posts' );
    $bfc->RegisterLibrary( 'strength' );
    $bfc->RegisterLibrary( 'users' );
    $bfc->RegisterLibrary( 'comments' );
    $bfc->RegisterLibrary( 'bookmarks' );
    $bfc->RegisterLibrary( 'expand' );
    
    $bfc->Depend( 'blogging/friends/friends' , 'friends' );
    $bfc->Depend( 'blogging/accounts/account_options' , 'md5' );
    $bfc->Depend( 'blogging/accounts/account_options' , 'strength' );
    $bfc->Depend( 'blogging/accounts/register' , 'strength' );
    $bfc->Depend( 'blogging/accounts/login' , 'users' );
    $bfc->Depend( 'blogging/accounts/register' , 'users' );
    $bfc->Depend( 'blog/allposts' , 'posts' );
    $bfc->Depend( 'blog/allposts' , 'comments' );
    $bfc->Depend( 'blog/allposts' , 'bookmarks' );
    $bfc->Depend( 'blog/posts' , 'posts' );
    $bfc->Depend( 'blog/posts' , 'comments' );
    $bfc->Depend( 'blog/posts' , 'bookmarks' );
    $bfc->Depend( 'blog/monthposts' , 'posts');
    $bfc->Depend( 'blog/monthposts' , 'comments');
    $bfc->Depend( 'blog/monthposts' , 'bookmarks' );
    $bfc->Depend( 'blogging/bookmarks/bookmarks' , 'bookmarks' );
    $bfc->Depend( 'blogging/blog/manage' , 'expand' );
?>
