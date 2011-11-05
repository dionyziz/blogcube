<?php
    include "js/js.php";
?>
var TARGET = "using=bubblexmlclient&target=";
var ID = "&id=";
var MAIN = "main.bc";
var METHOD = "POST";
var uniqueid = 0;
var d = document;
var MODERN_BROWSER = "<br />In order to view this page you will have to use a <b>modern</b> browser.<br />Why don't you try a recent version of <a href=\"http://www.mozilla.org/\">FireFox</a>?";
var BFCS = "bfc_";
var b = d.body;
var keyStr = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=";

function propQuot(str) {
    a('propQuot call (shouldn\'t happen)');
    // return str.split("&quot;").join('"');
}
function g(id) {
    return d.getElementById(id);
}
function a(tx) {
    alert(tx);
}
function ifd( ifid ) {
    return ( z = g( ifid ) ).contentDocument ? z.contentDocument : z.contentWindow.document;
}
function trim(str) {
    return str.replace(/^\s+/g, '').replace(/\s+$/g, '');
}
function et(newtitle) {
    window.status = window.defaultStatus = "";
    d.title = newtitle + ' ' + '-' + ' <?php echo $system; ?>';
}
function popup(url, name, width, height, returnWin, statusBar) {
    var url = url || "";
    var width = width || 500;
    var name = name || "BC";
    var height = height || 600;
    var left = (screen.width - width)/2;
    var top = (screen.height - height)/2.1;
    var win = window.open(url, name, 'left = ' + left + ', top = ' + top + ', toolbar = 0, scrollbars = 1, location = 0, status = ' + (statusBar ? 1 : 0) + ', statusmenubar = 0, resizable = 1, width=' + width + ', height=' + height);
    win.focus();
    if ( returnWin ) 
        return win;
    return false;
}
function encode64(input) {
   var output = "";
   var chr1, chr2, chr3;
   var enc1, enc2, enc3, enc4;
   var i = 0;

   do {
      chr1 = input.charCodeAt(i++);
      chr2 = input.charCodeAt(i++);
      chr3 = input.charCodeAt(i++);

      enc1 = chr1 >> 2;
      enc2 = ((chr1 & 3) << 4) | (chr2 >> 4);
      enc3 = ((chr2 & 15) << 2) | (chr3 >> 6);
      enc4 = chr3 & 63;

      if (isNaN(chr2)) {
         enc3 = enc4 = 64;
      } 
      else if (isNaN(chr3)) {
         enc4 = 64;
      }

      output = output + keyStr.charAt(enc1) + keyStr.charAt(enc2) + 
         keyStr.charAt(enc3) + keyStr.charAt(enc4);
   } while (i < input.length);
   
   return output;
}
function decode64(inp)
{
    var out = "";
    var chr1, chr2, chr3 = "";
    var enc1, enc2, enc3, enc4 = "";
    var i = 0;
    
    // remove all characters that are not A-Z, a-z, 0-9, +, /, or =
    inp = inp.replace(/[^A-Za-z0-9\+\/\=]/g, "");
    
    do {
        enc1 = keyStr.indexOf(inp.charAt(i++));
        enc2 = keyStr.indexOf(inp.charAt(i++));
        enc3 = keyStr.indexOf(inp.charAt(i++));
        enc4 = keyStr.indexOf(inp.charAt(i++));

        chr1 = (enc1 << 2) | (enc2 >> 4);
        chr2 = ((enc2 & 15) << 4) | (enc3 >> 2);
        chr3 = ((enc3 & 3) << 6) | enc4;

        out = out + String.fromCharCode(chr1);

        if (enc3 != 64) {
            out = out + String.fromCharCode(chr2);
        }
        if (enc4 != 64) {
            out = out + String.fromCharCode(chr3);
        }

        chr1 = chr2 = chr3 = "";
        enc1 = enc2 = enc3 = enc4 = "";
    } while (i < inp.length);
    
    return out;
}
function safeencode(str) {
    return se(str);
}
function se(str){
    // return str.replace(/\|/g,'||').replace(/&/g,'|a').replace(/\=/g,'|e');
    // return escape( str );
    return encodeURIComponent( str );
}
function filterslashes(str) {
    // replaces / with _
    // do not remove the line below, it is necessary for not filtering out /\// as it is considered a comment, while it is a regexpression
    // it will let the filtering system know the that the comment in the next line should not be filtered
    // <<<<BLOGCUBE
    return str.replace(/\//g,'_');
}

function uid() {
    return uniqueid++;
}

var donofade = false;

function CreateElement( target , id , extrastyle ) {
    var k = g( id );
    if ( k === null ) {
        var kk = g( target );
        if ( kk === null ) {
            return;
        }
        if ( extrastyle ) {
            xstyle = " style=\"" + extrastyle + "\"";
        }
        else {
            xstyle = "";
        }
        kk.innerHTML += "<div id=\"" + id + "\"" + xstyle + "></div>";
        return g( id );
    }
    else {
        return k;
    }
}
function getContainer( elementtarget , elementid ) {
    // deprecated, use g() instead
    return g( elementtarget );
}
function setHTML( id , innerHTML ) {
    // assignment operator
    if ( k = d.getElementById( id ) ) {
        k.innerHTML = innerHTML;
    }
}
function setValue( id , value ) {
    // assignment operator
    if ( k = d.getElementById( id ) ) {
        k.value = value;
    }
}
function htmlspecialchars( str ) {
    return str.replace(/&/g,"&amp;").replace(/</g,"&lt;").replace(/>/g,"&gt;");
}
function htmlspecialchars_decode( str ) {
    return str.replace(/&amp;/g,"&").replace(/&lt;/g,"<").replace(/&gt;/g,">");
}
function storeStaticElement( elementid , elementcontent ) {
    escapedcontent = elementcontent.replace( "\\" , "\\\\" ).replace( "\"" , "\\\"" );
    eval( "blog_static_element_" + elementid + " = \"" + escapedcontent + "\"" );
}
function findPosX(obj) {
    var curleft = 0;
    if (obj.offsetParent) {
        while (obj.offsetParent) {
            curleft += obj.offsetLeft;
            obj = obj.offsetParent;
        }
    }
    else if (obj.x)
        curleft = obj.x;
    return curleft;
}
function findPosY(obj) {
    var curtop = 0;
    if (obj.offsetParent) {
        while (obj.offsetParent) {
            curtop += obj.offsetTop;
            obj = obj.offsetParent;
        }
    }
    else if (obj.y)
        curtop = obj.y;
    return curtop;
}
/* function unescapeHTML(str) {
    var div = document.createElement('div');
    div.innerHTML = stripTags(str);
    return div.childNodes[0] ? div.childNodes[0].nodeValue : '';
}
function stripTags(str) {
    return str.replace(/<\/?[^>]+>/gi, '');
} */
function pluralize(text, number) {
    if (number > 1) {
        str = number + ' ' + text + 's';
    }
    else if (number == 1) {
        str = number + ' ' + text;
    }
    else if (number == 0) {
        str = 'No ' + text + 's';
    }
    return str;
}
