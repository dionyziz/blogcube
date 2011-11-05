<?php
    include "js/js.php";
?>
/*
    Developer: dionyziz
*/

var LOADICON = '<img src="images/downloading.gif" />&nbsp;';
var LOADING = LOADICON + 'Downloading Data...';
var aftermatch2 = "";
var returntoblog = 0;
var oldhash = "";

function Fire_Handle() {
    var hash = window.location.hash.substring(1);

    if (hash == '') {
        hash = 'accounts/login';
    }
    if (oldhash != hash) {
        oldhash = hash;
        dm(hash);
    }
    setTimeout("Fire_Handle()", 250);
}

var fnBubbleElementDownloaded = function ( oXML , elementtarget , elementid ) { // callback function
    // an element was downloaded via XMLHTTP
    // this function is called as a callback by xmlhttp
    // TODO (rare) if two elements are loaded simultaniously into the same elementtarget
    // and the latter is downloaded first, the first will be displayed instead of
    // the last (wrongly).
    
    // if this element was just preloaded
    // elementtarget will be ""
    // check for that
    if( elementtarget ) {
        // if loaded directly and not simply preloaded...
        // hide element by fading to total transparency
        fade0( elementtarget );
        // get the object via DOM
        if ( k = getContainer( elementtarget , elementid ) ) {
            // replace the object's content
            rt = oXML.responseText;
            // alert( rt );
            k.innerHTML = rt;
        }
        // execute any JSON code we need
        JSONGo( elementtarget , elementid );
        // if JSON has not specified that we shouldn't fade in
        if( !donofade ) {
            // fade in
            fadein( elementtarget , false );
        }
        else {
            // donofade has been set to true using JSON for the specific element
            // set it back to false (for next elements)
            donofade = false;
            // and just display element by setting opacity to 100%
            fade1( elementtarget );
        }
    }
    else {
        // cacheable should always be true if we're preloading an element
        // we can't use JSON here, since we're not executing JSON code
        // of preloaded element (it will only be executed when read from the Cache)
        cacheable = true;
    }
    if( cacheable ) {
        // cacheable has specified that the downloaded element is cacheable
        cacheable = false;
        // cache the element
        CacheStoreElement( elementid , oXML.responseText );
    }
};

// check if user browser supports XMLHTTP
// try to create a new bcAJAXSocket object instance
var xTest = new bcAJAXSocket();

// check if we failed...
if (!xTest) {
    // failed to use XMLHTTP, display error message
    b.innerHTML+=MODERN_BROWSER;
}
else {
    // XMLHTTP seems to work fine. Attempt to download the starting element using AJAX
    // (which will download more elements afterwards)
    // TODO: We need to check for Opera and POST XMLHTTP support here
<?php
    if( isset( $_GET[ "blogid" ] ) ) {
        $blogid = $_GET[ "blogid" ];
    }
    else {
        $blogid = 0;
    }
    
    if( $blogid ) {
        ?>goblog(<?php
        echo $blogid;
        ?>)<?php
    }
    else {
        ?>goblogging()<?php
    }
?>;
}
function JSONGo( elementtarget , elementid ) {
    // if JSON code exists for the specified element...
    if ( y = d.getElementById( BFC.Prepend + elementid ) ) {
        // Use JSON technology to execute special javascript code sent to us by the
        // server after requesting a dynamic page using XMLHTTP
        // more information about JSON and how it works is available at http://en.wikipedia.org/wiki/JSON
        // in elements, include JSON code between bfc_start() and bfc_end() PHP function calls
        z = htmlspecialchars_decode( y.innerHTML );
        // alert( z );
        eval( z );
        // bfc code is no longer necessary, so we can clear it
        // if the element is cached, it will re-create the tag when we load it from cache
        // including all the necessary JSON code
        y.innerHTML = "";
    }
}

// var dmisloading = false;
// var dmloadpx = 0;

function dm( elementparameters , loadingmsg ) {
    // this should go away as soon as we've switched to dm2!
    disallowdesignmode();
    et( 'Loading...' );
    window.defaultStatus = window.status = "Loading...";
    de( 'main' , 'blogging/' + elementparameters , '' , loadingmsg );
    pluspos = elementparameters.indexOf('&');
    if (pluspos == -1) {
        pluspos = elementparameters.length;
    }
    oldhash = window.location.hash = elementparameters.substr(0, pluspos);
}

function dm2( elementname , elementparameters , loadingmsg ) {
    disallowdesignmode();
    et( 'Loading...' );
    window.defaultStatus = window.status = "Loading...";
    de2( 'main' , 'blogging/' + elementname , elementparameters , loadingmsg );
    oldhash = window.location.hash = elementname;
}

/* function dmloading() {
    // a("dmloading");
    dmloadpx++;
    if( !dmloadpx ) {
        dmloadpx = 0;
    }
    else if( dmloadpx == 100 ) {
        dmloadpx = 0
    }
    g("cubelogo").style.backgroundPosition = dmloadpx + "% 100%";
    dmloadpx++;
    if( dmisloading ) {
        setTimeout( "dmloading()" , 250 );
    }
} */

function pl( elementid ) {
    de( "" , elementid );
}

function de2( targettag , element , parameters , loadingmsg ) {
    for ( parameter in parameters ) {
        element += '&' + parameter + '=' + se( parameters[ parameter ] );
    }
    de( targettag , element , '' , loadingmsg );
}

function de( elementtarget , elementparameters , inserttarget , loadingmsg ) {
    /*     
        download an element using AJAX
        download element named elementparameters into HTML tag with id elementtarget
        elementparameters may also contain additional element POST variables that should be
        send along with the element request in the form of elementpath/elementname&variable=value&foo=bar&blah=blue
        keep in mind that values have to be properly escaped if passed that way, so that they don't contain the & symbol
        [ use safeencode() for that ]
        the loadingmsg parameter specifies the text-to-be-displayed when loading
        the element. If no loadingmsg is specified, the default loading message will be used.
        If loadingmsg is set to "-", no loading message will be displayed.
        If no elementtarget is specified, the element is simply just downloaded, and cached if cacheable
        if the element is not cacheable there is no point in not specifying an elementtarget
        If no tag with id elementtarget exists and the inserttarget parameter is specified,
        a new tag with that id will be created at the end of inserttarget (keep in mind that, after
        the tag is created, all subsequent requests to load an element into that tag will not cause
        the tag to be recreated)
    */
    var elementid = elementparameters;
    // if the caller has specified an elementtarget (i.e. hasn't requested a simple cache)...
    if( elementtarget ) {
        // get the tag where we should load everything into
        var k = g( elementtarget );
        // if the tag doesn't exist
        if ( !k ) {
            // check if the caller has specified a part element to create the new tag into
            if ( inserttarget )
                // yes, create the element and store the result into k
                var k = CreateElement( inserttarget , elementtarget );
            else
                // no, something went wrong
                return;
        }
        if ( !elementparameters ) {
            // simply clear the content of the target tag
            k.innerHTML="";
            // and that's all
            return;
        }
    }
    // see if we can load the element from the js cache
    var ccel = CacheLoadElement( elementid );
    // check if the element was loaded from the cache successfully
    if( !ccel ) {
        // no the element hasn't been cached, we'll have to download it now
        // if the user has specified a target element (not only cache preloading)...
        if( elementtarget ) {
            // there is a target tag
            // we need to display a loading message
            if ( !loadingmsg )
                // no loading message specified, use the default loading message
                loadingmsg = LOADING;
            else
                // the caller has specified a loading message
                // if it's "-"...
                if ( loadingmsg == '-' )
                    // it means that we have to hide all loading messages and simply download the element
                    loadingmsg = '';
                else
                    // else, show the loading icon followed by the loading message passed to the function
                    loadingmsg = LOADICON + loadingmsg;
            // fade in the loading message
            fadein( elementtarget );
            // change the target's content to display the loading message; keep in mind that we started fading first
            // so that when we display the error message the element is almost transparent and therefore the change
            // isn't directly visible (appears smoothly)
            k.innerHTML = loadingmsg;
            // check if the caller has passed certain POST variables to the element
            // if the element was preloaded and cached, there is no point in passing POST variables, so we check for that
            // under the parent if
            if ( ( j = elementparameters.indexOf( "&" ) ) > -1 )
                // yes the caller has specified one or more variables, seperate the element name and store it into the elementid
                // variable, we'll need it soon
                elementid = elementparameters.substr( 0 , j );
        }
        // create a new XHConn object instance
        var z = new bcAJAXSocket();
        // set its target to elementtarget
        z.settarget( elementtarget );
        // set its id to elementid
        z.setid( elementid );
        // time to check for BFC dependencies
        var libs = BFC.Needs( elementid );
        var libss = '';
        if (libs != '') {
            libss = '&lib=' + libs;
        }
        else {
            libss = '';
        }
        // actually send the XMLHTTP request to the server, pass the requested document, the request method (POST) 
        // the POST parameters and the pointer to our callback function
        z.connect( MAIN , METHOD , TARGET + elementtarget + ID + elementparameters + "&uuid=" + uid + libss , fnBubbleElementDownloaded );
    }
    else {
        // ah, nice, the element has been cached
        // if the request was not only intending to preload and cache the element, only then we have to take some action
        if( elementtarget ) {
            // clear the text already in the tag
            k.innerHTML = "";
            // start fading in
            fadein( elementtarget );
            // replace the contents of the tag with the actual cached content
            k.innerHTML = ccel;
            // execute element's JSON code (bfc)
            JSONGo( elementtarget , elementid );
        }
    }
}
function goblog( blogid ) {
    CSSLastUnload();
    de( "bb" , "blog/central&blogid=" + blogid );
}
function goblogging( dmid ) {
    CSSLastUnload();
    if( !dmid )
        aftermatch2 = "";
    else
        aftermatch2 = dmid;
    de2( "bb" , "blogging/central" , {} , "-" );
    loadingdiv = document.createElement('div');
    loadingdiv.style.textAlign = 'center';
    loadingdiv.style.paddingTop = '20px';
    loadingimg = document.createElement('img');
    loadingimg.src = 'images/loading.gif';
    loadingdiv.appendChild(loadingimg);
    g('bb').appendChild(loadingdiv);
    setTimeout('window.status=""', 50);
}
