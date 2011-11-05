/*
    Developer: ch-world
*/

var ERROR_DIKEY = "Please enter a key for your item";
var ERROR_DICON = "Please enter some content for your item";
var ERROR_DITIT = "Please enter a title for your item";
var ERROR_DSKEY = "Please enter a key for your subfolder";
var ERROR_DSNAM = "Please enter a name for your subfolder";

function HelpNewItem() {
    i = g( "helpnewitem/folderid" ); // id
    c = g( "helpnewitem/text" ); // content
    t = g( "helpnewitem/title" ); // title
    k = g( "helpnewitem/key" ); // key
    
    if ( !k.value ) {
        a( ERROR_DIKEY );
        k.focus();
    }
    else if ( !t.value ) {
        a( ERROR_DITIT );
        t.focus();
    }    
    else if ( !c.value ) {
        a( ERROR_DICON );
        c.focus();
    }
    else {
        de( "helpnewitemresult" , "blogging/help/item/now&i=" + i.innerHTML + "&c=" + safeencode(c.value) + "&k=" + safeencode(k.value) + "&t=" + safeencode(t.value) );
    }
}

function HelpNewSubfolder() {
    i = g( "helpnewsubfolder/folderid" ); // id
    n = g( "helpnewsubfolder/name" ); // name
    k = g( "helpnewsubfolder/key" ); // key
    
    if ( !k.value ) {
        a( ERROR_DSKEY );
        k.focus();
    }
    else if ( !n.value ) {
        a( ERROR_DSNAM );
        t.focus();
    }
    else {
        de( "helpnewsubfolderresult" , "blogging/help/subfolder/now&i=" + i.innerHTML + "&n=" + safeencode(n.value) + "&k=" + safeencode(k.value) );
    }
}

function help( key ) {
    // Debug.Notice( 'help called' ) ;
    var z = CreateElement( 'bb' , 'helpsystem' , 'display:inline' );
    // Debug.Notice( 'Element Helpsystem should now be created' );
    var myid = 'helpitem_' + key;
    if (g(myid) === null) {
        z.innerHTML += '<div id="' + myid + '" style="display:none"></div>'; 
    }
    else {
        g(myid).style.display = 'none';
    }
    de2( myid , "blogging/help/help" , {h: key , t: myid} );
}