<?php
    include "js/js.php";
?>
var ERROR_BNAME = "Please enter a URL for your new blog";

function buildblog() {
    u = g( "blog_name" );
    if ( !u.value ) {
        a( ERROR_BNAME );
        u.focus();
    }
    else
        de( "blogcreator" , "blogging/blog/create/now&n=" + u.value );
    return false;
}

function blogoptions() {
    if( ( k = g( "blogoptions" ) ).style.display == "" ) {
        k.style.display = "none";
    }
    else {
        k.style.display = "";
        g( "blognewpost" ).style.display = g( "blogoldposts" ).style.display = "none";
    }
}

function blognewpost() {
    if( ( k = g( "blognewpost" ) ).style.display == "" ) {
        k.style.display = "none";
    }
    else {
        k.style.display = "";
        g( "blogoptions" ).style.display = g( "blogoldposts" ).style.display = "none";
    }
}

function blogoldposts() {
    if( ( k = g( "blogoldposts" ) ).style.display == "" ) {
        k.style.display = "none";
    }
    else {
        k.style.display = "";
        g( "blogoptions" ).style.display = g( "blognewpost" ).style.display = "none";
    }
}

function blogdelete( blogid ) {
    javascript:dm('blog/delete/confirm&blogid=' + se(blogid) );
}