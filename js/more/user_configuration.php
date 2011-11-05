var onfvalue;

function UpdateField( fieldname , publ ) {
    var year;
    var month;
    var start;
    var day;
    var nvalue;
    
    if ( publ == "2" ) {
        sz = g( "publicprofsave_" + fieldname );
        switch( sz.value ) {
            case "private":
                opticon = "meonly";
                break;
            case "public":
                opticon = "everyone";
                break;
            case "friends":
                opticon = "friends";
                break;
            case "ffriends":
                opticon = "friendsoffriends";
        }
        sz.style.backgroundImage = "url('images/nuvoe/publicity_"+opticon+".png')";
        if ( fieldname == "age" ) {
            nvalue = g( "publicprofsave_age" ).value;
            de( "getelem_dob" , "blogging/profile/save&fieldname=publicprofsave_age&fieldvalue=" + nvalue , "" , 'Saving...' );
        }
        else if ( fieldname == "birthday" ) {
            nvalue = g( "publicprofsave_birthday" ).value;
            de( "getelem_dob" , "blogging/profile/save&fieldname=publicprofsave_birthday&fieldvalue=" + nvalue , "" , 'Saving...' );            
        }        
        else {
            nvalue = g( "publicprofsave_" + fieldname ).value;
            fname = "publicprofsave_" + fieldname;
            de( "getelem_" + fieldname , "blogging/profile/save&fieldname=" + fname + "&fieldvalue=" + nvalue , "" , 'Saving...' );
        }    
    }
    else {
        if ( fieldname == "dob1" || fieldname == "dob2" || fieldname == "dob3" ) {
            day = g( "profsave_dob1" ).value;
            month = g( "profsave_dob2" ).value;
            year = g( "profsave_dob3" ).value;
            nvalue = year + '-' + month + '-' + day;
            de( "getelem_dob" , "blogging/profile/save&fieldname=profsave_dob&fieldvalue=" + nvalue , "" , 'Saving...' );
        }
        else {
            nvalue = g( "profsave_" + fieldname ).value;
            if ( onfvalue != nvalue ) {    
                nvalue = safeencode( nvalue );
                fname = "profsave_" + fieldname;
                de( "getelem_" + fieldname , "blogging/profile/save&fieldname=" + fname + "&fieldvalue=" + nvalue , "" , 'Saving...' );
            }
        }
    }
}
function profpersonal () {
    if ( g( "personal_info" ).style.display == "" ) {
        g( "personal_info" ).style.display = "none";
    }
    else {
        g( "personal_info" ).style.display = "";
        g( "contacts" ).style.display = g( "favourites" ).style.display = g( "prof_files" ).style.display = "none";
    }
}
function profcontacts () {
    if ( g( "contacts" ).style.display == "" ) {
        g( "contacts" ).style.display = "none";
    }    
    else {
        g( "personal_info" ).style.display = g( "prof_files" ).style.display = g( "favourites" ).style.display = "none";    
        g( "contacts" ).style.display = "";
    }
}
function proffavor () {
    if ( g( "favourites" ).style.display == "" ) {
        g( "favourites" ).style.display = "none";
    }    
    else {
        g( "personal_info" ).style.display = g( "contacts" ).style.display = g( "prof_files").style.display = "none";
        g( "favourites" ).style.display = "";    
    }
}
function proffiles () {
    if ( g( "prof_files" ).style.display == "" ) {
        g( "prof_files" ).style.display = "none";
    }
    else {
        g( "personal_info" ).style.display = g( "contacts" ).style.display = g( "favourites" ).style.display = "none";
        g( "prof_files" ).style.display = "";
    }
}
function prof_check ( fieldname ) {
    onfvalue = g( "profsave_" + fieldname ).value;
}
var id;
var m1=0;
function activate_deletion ( id ) {
    if ( g( "avatar_default_"+id ).checked ) {
        m1 = m1 + 1;
    }
    else {
        m1 = m1 - 1;
    }
    if ( m1 == 0 ) {
        g( "delavatar" ).style.display = "none";
    }
    else {
        g ( "delavatar" ).style.display = "";
    }
}
var cur_num_avatars;
var s;
function avatars_delete() {
    s = "";
    for ( i=0; i<cur_num_avatars; i++ ) {
        if ( g( "avatar_default_" + i ).checked ) {
            s += "&avatid" + i + "=" + g( "avatar_i_to_id_" + i ).innerHTML;
        }
    }
    de( "delavatar" , "blogging/profile/delete_avatar" + s );
}
function uploadavatar() {
    ifd("fifavup").getElementById("f_avup").submit();
    g("fifavup").style.display = "none";
    g("fifavupanime").style.display = "";
}
function avataruploaded() {
    g("newavatars").innerHTML += ifd("fifavup").getElementById("avatarhtm").innerHTML;
    g("fifavupanime").style.display = "none";
    g("fifavup").style.display = "";
    ifd("fifavup").location.href = "cubism.bc?g=media/uploadformavatars";
}
function avataruploaderror() {
    g("fifavupanime").style.display = "none";
    g("fifavup").style.display = "";
    ifd("fifavup").location.href = "cubism.bc?g=media/uploadformavatars";
    g("fafavuperror").innerHTML = "<b>Your avatar could not be uploaded.</b><br />Please make sure it is not bigger than 16MB in size and that it is a valid JPG or PNG picture and try again.";
}
function avatardelete( mediaid ) {
    if( confirm( "Do you really want to delete this avatar?" ) ) {
        pl( "blogging/profile/delete_avatar&mediaid=" + mediaid );
        g( "viewavatar_" + mediaid ).style.display = "none";
    }
}
function avatardefault( avatarid, avatarmediaid ) {
    if ( g("viewavatar_" + defaultavatar) ) { 
        g("avatardeftext_" + defaultavatar).style.display = "none";
        g("viewavatar_" + defaultavatar).className = "avatar_td";
    }
    defaultavatar = avatarmediaid;
    g("viewavatar_" + defaultavatar).className = "avatar_td_default";
    g("avatardeftext_" + defaultavatar).style.display = "";
    avatarout(defaultavatar);
    de( "avatarstatus", "blogging/profile/make_default_avatar&avatarid=" + avatarid + "&avatarmediaid=" + avatarmediaid );
}
function avatarover( mediaid ) {
    if ( mediaid != defaultavatar ) {
        g( "avatarcontrolbx_" + mediaid ).style.display = "";
        g( "viewavatar_" + mediaid ).style.backgroundImage = "url('images/cardfade.jpg')";
    }
}
function avatarout( mediaid ) {
    g( "viewavatar_" + mediaid ).style.backgroundImage = g( "avatarcontrolbx_" + mediaid ).style.display = "none";
}