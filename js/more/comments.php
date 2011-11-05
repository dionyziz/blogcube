function comment(postid, parid) {
    // a("Hey are going to post a comment on " + postid + " with " + parid);
    c = g( "comment_" + postid + "_" + parid );
    alert( c.value );
    de( 'createnewcomment_' + postid + '_' + parid, 'blog/comment/now&postid=' + postid + '&parid=' + parid + '&c=' + safeencode( c.value ) );
}