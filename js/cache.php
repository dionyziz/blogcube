<?php
    include "js/js.php";
?>
/*
    Developer: dionyziz
*/

var cacheable = false;
var bc_cached_ = new Array(); // not yet used, we should be using it soon (conflicting ids)
var bc_cachep_ = new Array();

function CacheLoadElement( elementid ) {
    // load an element from the cache
    // by elementid, return element contents
    elementid = filterslashes( elementid );
    var z = g( "bc_cached_" + elementid );
    var zprg = g( "bc_cachep_" + elementid );
    if( z ) {
        if( zprg )
            if( zprg.innerHTML != '' )
                return false;
        return z.innerHTML;
    }
    return false;
}
function CacheElementExists( elementid ) {
    // check if an element exists in cache
    // for this to be true
    // element bc_cached_(elementid) must exist
    // AND its content must be something
    // (if it doesn't exist xz.innerHTML will be invalid,
    // yet never executed)
    return ( ( xz = g( "bc_cached_" + elementid ) ) && xz.innerHTML );
}
function CacheStoreElement( elementid , content ) {
    // store an element in the cache
    elementid = filterslashes( elementid );
    var z = CreateElement( "bb" , "bc_cached_" + elementid , "display:none" );
    if( z ) {
        z.innerHTML = content;
    }
    bc_cached_[ elementid ] = content;
    var zprg = CreateElement( "bb" , "bc_cachep_" + elementid , "display:none" );
    if( zprg ) {
        zprg.innerHTML = '';
    }
    bc_cachep_[ elementid ] = '';
    return false;
}
function cp( elementid ) {
    // purge cached object with the specified elementid
    elementid = filterslashes( elementid );
    var zprg = g( "bc_cachep_" + elementid );
    if( zprg ) {
        zprg.innerHTML = "purge";
    }
    bc_cachep_[ elementid ] = "purge";
    return false;
}
