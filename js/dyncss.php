/*
    Dynamic CSS Loading
    Developer: dionyziz
*/
var cssstyling;
var laststyle = 0;

function CSSLoad( file ) {
    laststyle++;
    if( document.createStyleSheet ) {
        // MSIE
        
        document.createStyleSheet( file );
    }
    else {
        // FireFox
        
        var styles = "@import url( '<?php
        echo $systemurl;
        ?>" + file + "' );";
        var newSS=document.createElement('link');
        // a( styles );
        
        newSS.rel='stylesheet';
        newSS.href='data:text/css,'+escape(styles);
        document.getElementsByTagName("head")[0].appendChild(newSS);
    }
}

function CSSLastUnload() {
    if( laststyle ) {
        document.styleSheets[ laststyle ].disabled = true;
    }
}
