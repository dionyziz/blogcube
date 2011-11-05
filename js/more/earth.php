<?php
    /*
        JS: Earth
        Developer: Dionyziz
    */
    include "js/js.php";
?>
var earthpostsubmit;
var earthpostupload;

function earthupload(earthid) {
    // submit the upload form automatically when the user selects a file or types a filename and hits return
    g("earthformp").style.display = "none";
    g("earthprogp").style.display = "";
    ifd("ifrearth").getElementById("earthform").submit();
    if( earthpostsubmit ) {
        setTimeout( earthpostsubmit + "(" + earthid + ")" , 50 );
        earthpostsubmit = "";
    }
    de("earthprogptmp","blogging/earth/earth&earthid="+earthid,"","-"); // display progress
}
function earthpostuploadcallback(earthid,mediaid,originalfilename) {
    // called by upload.bc when the upload gets completed
    g("earthprogp").innerHTML = "Upload Completed";
    if( earthpostupload ) {
        setTimeout( earthpostupload + "(" + earthid + "," + mediaid + ")" , 50 );
        earthpostupload = "";
    }
}
function earthexppostsubmit(earthid) {
    a( "This JS callback function got called with earthid " + earthid + ". A programmer using Earth for uploading can define his own callback function.\n\nEvent: Upload is starting." );
}
function earthexppostupload(earthid,mediaid) {
    a( "This JS callback function got called with earthid " + earthid + " and mediaid " + mediaid + ". A programmer using Earth for uploading can define his own callback function.\n\nEvent: Upload is done." );
}
