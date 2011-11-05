<?php
    include "js/js.php";
?>
var wsi;
var lastwsicode;
function initwsi( wsicode ) {
    // moz
    wsi = ifd( "wsiareaif_" + wsicode );
    wsi.designMode = "on";
    wsi.parentWindow.focus();
}
function BCWYSE( wh , va ) {
<?php
    if( UserBrowser() == "MSIE" ) {
        ?>wsi.parentWindow.focus();<?php
    }
?>
    if( !va )
        va = null;
    wsi.execCommand( wh , false, va );
}
<?php
if( $debug_version ) {
?>
function developerwysiwyg() {
    g("designmodego").style.display = "none";
    g("designmodewarn").style.display = "";
    document.designMode = "on";
}
<?php
}
?>
function allowdesignmode() {
    <?php
    if( $debug_version ) {
        ?>g("designmodego").style.display = "";<?php
    }
    ?>
}
function disallowdesignmode() {
    <?php
    if( $debug_version ) {
        ?>g("designmodego").style.display = "none";<?php
    }
    ?>
}
function BCWYSStrong() {
    BCWYSE("bold");
}
function BCWYSItalix() {
    BCWYSE("italic");
}
function BCWYSUnderbar() {
    BCWYSE("underline");
}
function BCWYSWoops() {
    BCWYSE("undo");
}
function BCWYSUnwoops() {
    BCWYSE("redo");
}
function BCWYSSupdo() {
    BCWYSE("superscript");
}
function BCWYSSubdo() {
    BCWYSE("subscript");
}
function BCWYSInsertPicture() {
    Modalize(true);
    var z = CreateElement('bb', 'albums_wysiwyg', 'display:none');
    z.style.display = 'none';
    var zz = CreateElement('albums_wysiwyg', 'albums_wysiwyg_real');
    de2('albums_wysiwyg_real', 'blogging/media/albums/wysiwyg/wysiwyg');
}
// function BCWYSFormula() --> /js/more/math
function BCWYSLink() {
    if( !wsi.queryCommandEnabled('createlink') ) {
        a( "Please select the text you want to make a link and then click on the Make Link button" );
    }
    else {
        href = prompt( "Please type the address you want your link to point to" , "http://blogcube.net/" );
        BCWYSE( "createlink" , href );
    }
}
function BCWYSUl() {
    BCWYSE( "insertunorderedlist" );
}
function BCWYSOl() {
    BCWYSE( "insertorderedlist" );
}
function BCWYSHr() {
    BCWYSE( "inserthorizontalrule" );
}
function BCWYSStrike() {
    BCWYSE("strikethrough");
}
function BCWYSAlignL() {
    BCWYSE("justifyleft");
}
function BCWYSAlignR() {
    BCWYSE("justifyright");
}
function BCWYSAlignC() {
    BCWYSE("justifycenter");
}
function WYSContent( wsicode ) {
    a( g( "wsiareaif_" + wsicode ).contentDocument.body.innerHTML );
}
function WYSInsertRAW( data ) {
    BCWYSE( "inserthtml" , data );
}
function WYSInsertImage( url ) {
    BCWYSE( "insertimage" , url );
}