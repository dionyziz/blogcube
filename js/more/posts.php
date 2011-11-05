var NO_BLOG_NAME = "You haven't specified a name for your post. Are you sure that you want to continue?";
var NO_BLOG_CONTENT = "There are no text or images in your new blog post. Are you sure that you want to continue?";

function createnewpost() {
    z = g( "createnewpost" );
    zq = g( "addnewpostanchor" );
    if( z.style.display == "" ) {
        cancelnewpost();
    }
    else {
        z.style.display = "";
        zq.innerHTML = "Cancel Quick Post";
        g( "newpostanchor" ).focus();
        g( "newpostname" ).focus();
    }
}
function cancelnewpost() {
    z = g( "createnewpost" );
    zq = g( "addnewpostanchor" );
    zq.innerHTML = "Quick Post";
    z.style.display = "none";
}

function buildquickpost( blogid ) {
    u = g( "newpostname" );
    if ( !u.value )
        if ( !confirm( NO_BLOG_NAME ) ) {
            u.focus();
            return;
        }
    c = g( "postinserttext" );
    if ( !c.value )
        if ( !confirm( NO_BLOG_CONTENT ) )
            return;
    de( "newpostcreator" , "blogging/post/now&quickpost=yes&blogid=" + blogid + "&n=" + se(u.value) + "&c=" + se(c.value) , '' , "Publishing..." );
}

function buildpost( blogid, editid ) {
    u = g( "postname" );
    if ( !u.value )
        if ( !confirm( NO_BLOG_NAME ) ) {
            u.focus();
            return;
        }
    cv = ifd('wsiareaif_'+lastwsicode).body.innerHTML;
    if ( !cv )
        if ( !confirm( NO_BLOG_CONTENT ) )
            return;
    var l = "blogging/post/now&blogid=" + blogid + "&n=" + se(u.value) + "&c=" + se(cv);
    if ( editid > 0 ) {
        l = l + "&edit=" + editid;
    }
    de( "newpostcreator" , l , '' , "Publishing..." );
}

function addblogcute(prefix,suffix,text) {
    var s = g("postinserttext");
    blah = prefix + text + suffix;
     //IE
     if (d.selection) {
         sel=d.selection.createRange().text;
        if (!sel) { sel = text; }
         s.focus();
         d.selection.createRange().text=blah;
     }
     //MOZ
     else if (s.selectionStart||s.selectionStart=="0") {
         var startPos = s.selectionStart;
         var endPos = s.selectionEnd;
         s.value = s.value.substring(0, startPos) + blah + s.value.substring(endPos,c.length);
     }
    else {
         s.value += tag;
     }
}

function editpost( blogid , postid ) {
    goblogging('blog/manage/blog&blogid=' + blogid + '&editpost=yes&postid=' + postid );
}


function deletepost( blogid, postid ) {
  if ( confirm("Are you sure that you want to delete the selected post?") ) {
        de( "allposts" , "blog/post/delete&blogid=" + blogid + "&postid=" + postid,"","Deleting...");
  }
}