var focused_album = -1;
var focused_photo = -1;
var focused_minialbum = -1;
var startvalue;
var mainpicdivid = -1;
//var album_num;

function focus_album( divid ) {
    if ( focused_album != -1 ) {    
        g( "album_" + focused_album ).style.backgroundColor = "#f7f7ff";
        g( "drpdown_" + focused_album ).style.display = "none";
    }
    g( "album_" + divid ).style.backgroundColor = "#ced5ef";
    g( "drpdown_" + divid ).style.display = "inline";
    focused_album = divid;
}
function album_select( albid , divid , albname ) {
    g( "allalbums" ).style.display = "none";
    g( "allalbumphotos" ).style.display = "";
    g( "minialbum_" + divid ).style.fontWeight = "bold";
    g( "minialbum_" + divid ).style.backgroundColor = "#ced5ef";
    g( "minialbum_" + divid ).style.border = "1px solid #8baed5";
    de2( 'photosofalbum' , 'blogging/media/albums/albumphotos' , {
        albthink: albid,  
        point: 1} , 'Opening album...' );
    focus_tab( "albumname" );
    unfocus_tab( "allialbis" );
    focused_minialbum = divid;
    g( "albumshowname" ).innerHTML = albname;
}
function album_select_name( divid , albumname ) {
    g( "allalbums" ).style.display = "none";
    g( "allalbumphotos" ).style.display = "";
    g( "minialbum_" + divid ).style.fontWeight = "bold";
    g( "minialbum_" + divid ).style.backgroundColor = "#ced5ef";
    g( "minialbum_" + divid ).style.border = "1px solid #8baed5";
    de2( 'photosofalbum' , 'blogging/media/albums/albumphotos' , {
    albthink: albumname,
    point: 2} , 'Opening album...' );
    focus_tab( "albumname" );
    unfocus_tab( "allialbis" );
    focused_minialbum = divid;
    g( "albumshowname" ).innerHTML = albumname;
}
    
function photo_hover( divid ) {
    //no second parameter in this function
    if ( focused_photo != -1 ) {
        g( "photo_" + focused_photo ).style.backgroundColor = "#f7f7ff";
        g( "dropdownmenu_" + focused_photo ).style.display="none";
        if ( mainpicdivid != -1 ) {
            g( "photo_" + mainpicdivid ).style.backgroundColor = "#99ccff";
        }
    }
    g( "photo_" + divid ).style.backgroundColor = "#ced5ef";
    if ( mainpicdivid != -1 ) {
        g( "photo_" + mainpicdivid ).style.backgroundColor = "#99ccff";
    }
    g( "dropdownmenu_" + divid ).style.display="inline";
    focused_photo = divid;
}

function minialbumfocus( albid , divid , albname ) {    
    de2( 'photosofalbum' , 'blogging/media/albums/albumphotos' , {
        albthink: albid,
        point: 1} , 'Opening album...' );
    g( "minialbum_" + focused_minialbum ).style.backgroundColor = "#f7f7ff";
    g( "minialbum_" + focused_minialbum ).style.fontWeight = "";
    g( "minialbum_" + focused_minialbum ).style.border = "none";
    g( "minialbum_" + divid ).style.fontWeight = "bold";
    g( "minialbum_" + divid ).style.backgroundColor = "#ced5ef";
    g( "minialbum_" + divid ).style.border = "1px solid #8baed5";
    focused_minialbum = divid;
    g( "albumshowname" ).innerHTML = albname;
}

function focus_tab( divname ) {    
    g( divname ).style.backgroundColor = "#ced5ef";
    g( divname ).style.opacity = "1";
    g( divname ).style.cursor = "pointer";
    g( divname ).style.height = "28px";
}
function unfocus_tab( divname ) {
    g( divname ).style.backgroundColor = "#e6eaff";
    g( divname ).style.opacity = "1";
    g( divname ).style.cursor = "pointer";
    g( divname ).style.height = "23px";
}

function disable_tab( divname ) {
    g( divname ).style.backgroundColor = "#e6eaff";
    g( divname ).style.opacity = "0.4";
    g( divname ).style.cursor = "";
    g( divname ).style.height = "23px";
}

function allalbclick() {
    g( "allalbums" ).style.display = "";
    g( "allalbumphotos" ).style.display = "none";
    g( "photo" ).style.display = "none";
    focus_tab( "allialbis" );
    disable_tab( "albumname" );
    disable_tab( "albiphoto" );
    if ( focused_minialbum != -1 ) {
        g( "minialbum_" + focused_minialbum ).style.backgroundColor = "#f7f7ff";
        g( "minialbum_" + focused_minialbum ).style.fontWeight = "";
        g( "minialbum_" + focused_minialbum ).style.border = "none";
    }
    g( "albumshowname" ).innerHTML = "Album's photos";
}
function albiclick() {
    if ( g( "allalbumphotos" ).style.display == "none" && g( "allalbums" ).style.display == "none" ) {
        g( "photo" ).style.display = "none";
        g( "allalbumphotos" ).style.display = "";
        focus_tab( "albumname" );
        disable_tab( "albiphoto" );
    }
}

function photo_select( photoid , divid ) {
    g( "allalbumphotos" ).style.display = "none";
    g( "photo" ).style.display = "";
    focus_tab( "albiphoto" );
    unfocus_tab( "albumname" );
    unfocus_tab( "allialbis" );
    de2( "photoview" , "blogging/media/albums/album_photoview" , {
        photooid: photoid, 
        divid: divid } , 'Opening photo...' );
}

function delete_album( albid , divid ) {
    if( confirm( "Do you really want to delete this album and all of its contents?" ) ) {
        pl( "blogging/media/albums/albums_delete&albumid=" + albid );
        g( "album_" + divid ).innerHTML = "";
        g( "minialbum_" + divid ).innerHTML = "";
        g( "album_" + divid ).style.display = "none";
        g( "minialbum_" + divid ).style.display = "none";
        focused_album = -1;
    }
}

function save_albumname( userid ) {
    //works for windows
    var album_num;
    nvalue = g( "newalbname" ).value;
    if( nvalue!="" ) {        
        pl( "blogging/media/albums/album_savecreation&albumname=" + nvalue );
        //g( "created_albums" ).innerHTML += '<div class="outerdiv"><div class="mainalbpicture" id="' + album_num + '" onmouseover="javascript:focus_album( \'' + album_num + '\');">' + g( "noalbpicture" ).innerHTML + '<br /><div style="cursor:pointer;" onclick="javascript:album_select_name( \'' + album_num + '\' , \'' + nvalue + '\');">' + nvalue + '</div></div></div>';
        de2( "allalbums" , "blogging/media/albums/allalbums", {userid: userid}, 'Creating...' );
        de2( "albumlistsmall" , "blogging/media/albums/albumlistsmall", {userid: userid} );
        ++album_num;
        //g( "newalbname" ).value = "";
    }
}

function delete_photo( photoid , divid ) {    
    if( confirm( "Do you really want to delete this photo?" ) ) {
        de2( '' , "blogging/media/albums/album_deletephoto" , {photoid: photoid} , 'Deleting photo...' );
        //pl( "blogging/media/albums/album_deletephoto&photoid=" + photoid );
        g( "outerphotodiv_" + divid ).innerHTML = "";
        g( "outerphotodiv_" + divid ).style.display = "none";
        albiclick();
        focused_photo = -1;
    }
}

function uploadphoto() {
    ifd("alb_photoupl").getElementById("f_photoup").submit();
    g("alb_photoupl").style.display = "none";
    g("uploadanims").style.display = "";
}
/*old version of photo uploading
function photouploaded( albumid ) {
    g("uploadanims").style.display = "none";
    g("alb_photoupl").style.display = "";
    ifd("alb_photoupl").location.href = "cubism.bc?g=media/uploadphotoalbums";
    de2( "photosofalbum" , "elements/blogging/media/albums/albumphotos" , {
    albthink: albumid,
    point: 1 } , 'Adding photo to album...' );
}
*/
function photouploaded( albumid ) {
    g("uploadanims").style.display = "none";
    g("alb_photoupl").style.display = "";   
    g("hereareallphotos").innerHTML += ifd("alb_photoupl").getElementById("photohtml").innerHTML;
    ifd("alb_photoupl").location.href = "cubism.bc?g=media/uploadphotoalbums&albumid=" + albumid;
    z = g( "albumsnophotos" );
    if ( z ) {
        if ( z.style.display == "" ) {    
            z.style.display = "none";
        }
    }
    //g( "newphoto" ).style.display = "";
}
function photouploaderror() {
    g("uploadanims").style.display = "none";
    g("alb_photoupl").style.display = "";
    ifd("alb_photoupl").location.href = "cubism.bc?g=media/uploadphotoalbums";
    g("photouperror").innerHTML = "<b>Your photo could not be uploaded.</b><br />Please make sure it is not bigger than 16MB in size and that it is a valid JPG or PNG picture and try again.";
/*
    g("newavatars").innerHTML += ifd("fifavup").getElementById("photohtml").innerHTML;
    g("fifavupanime").style.display = "none";
    g("fifavup").style.display = "";
    ifd("fifavup").location.href = "cubism.bc?g=media/uploadformavatars";
*/
}

//here begin wysiwyg functions
var wysiwyg_fc = -1;
var wysiwyg_ph = -1;
function wysiwyg_focusalbum( divid ) {
    if ( wysiwyg_fc != -1 ) {    
        g( "wysiwygalbum_" + wysiwyg_fc ).style.backgroundColor = "#f7f7ff";
    }
    g( "wysiwygalbum_" + divid ).style.backgroundColor = "#ced5ef";
    wysiwyg_fc = divid; 
}
function wysiwyg_activalbum( albumid ) {
    var z = CreateElement('bb', 'albums_wysiwyg', 'display:none');
    z.style.display = 'none';
    var zz = CreateElement('albums_wysiwyg', 'albums_wysiwyg_real');
    de2('albums_wysiwyg_real', 'blogging/media/albums/wysiwyg/wysiwyg' , {albumid: albumid} );
}
function wysiwyg_focusphoto( divid ) {
    if ( wysiwyg_ph != -1 ) {    
        g( "wysiwygphoto_" + wysiwyg_ph ).style.backgroundColor = "#f7f7ff";
    }
    g( "wysiwygphoto_" + divid ).style.backgroundColor = "#ced5ef";
    wysiwyg_ph = divid; 
}    
function wysiwyg_insert( photoid ) {
    //so this is the difficult part, the function will insert the img
    //to the wysiwyg editor and will have to close the gui window
    WYSInsertImage( "download.bc?id=" + photoid );
    Modalize(false);
    g('albums_wysiwyg').style.display = 'none';
}
function wysiwyg_back() {
    //go back to albums
    BCWYSInsertPicture();
}
function album_publ( albumid, divid  ) {
    sz = g( "albumpublicity" );
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
    pl( "blogging/media/albums/albumsave_publicity&albumid=" + albumid + "&newpubl=" +sz.value );
    edit_publicity( divid );
}
/*
function edit_publicity( divid ) {
    if ( g( "album_options_" + divid ).style.display == "none" ) {    
        g( "album_options_" + divid ).style.display = "";
    }
    else {
        g( "album_options_" + divid ).style.display = "none";
    }
}
*/
function change_albname( divid ) {
    g( "changename_" + divid ).style.display = "";
    g( "name_" + divid ).style.display = "";
    g( "editname_" + divid ).focus();
    g( "editname_" + divid ).select();
}
function getstartalbname( divid ) {
    startvalue = g( "editname_" + divid ).value;
}
function savealbumname( divid , albumid ) {
    lstvalue = g( "editname_" + divid ).value;
    if( lstvalue!=startvalue && lstvalue!="" ) {
        //pl("blogging/media/albums/albumsavename&albumname=" + lstvalue + "&albumid=" + albumid );
        de2 ( '' , 'blogging/media/albums/albumsavename' , {
            albumname: lstvalue,
            albumid: albumid
        });
        
        g( "changename_" + divid ).style.display = "none";
        //g( "name_" + divid ).innerHTML = TextFadeOut( lstvalue , new RGBColor( 51 , 102 , 153 ) , new RGBColor( 164 , 192 , 221 ) , '8' );
        //g( "smalllistname_" + divid ).innerHTML = TextFadeOut( lstvalue , new RGBColor( 51 , 102 , 153 ) , new RGBColor( 164 , 192 , 221 ) , '10' );
        g( "name_" + divid ).innerHTML = lstvalue;
        g( "smalllistname_" + divid ).innerHTML = lstvalue;
    }
}
function defaultmainpic( mediaid , albumid , divid  ) {
    Debug.Notice('mainpicdivid = ' + mainpicdivid);
    pl( "blogging/media/albums/makedefault&mediaid=" + mediaid + "&albumid=" + albumid  );
    if ( mainpicdivid != -1 ) {
        g( "photo_" + mainpicdivid ).style.backgroundColor = "#f7f7ff";
    }
    g( "photo_" + divid ).style.backgroundColor = "#99ccff";
    mainpicdivid = divid;
}