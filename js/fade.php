<?php
    include "js/js.php";
?>
var mxf = 1;

function fade0(id) {
    z=g(id);
    if( !z ) {
        return;
    }
    if( z.style.filter ) {
        if( z.style.filter.alpha )
            z.style.filter.alpha.opacity = 0;
    }
    else
        z.style.opacity = 0;
    var z = CreateElement( "uncertain_position" , "blogcube_js_fade_" + id , "display:none" );
    if( z )
        z.innerHTML = 0;
}

function fade1(id) {
    z=g(id);
    if( !z ) {
        return;
    }
    if( z.style.filter ) {
        if( z.style.filter.alpha )
            z.style.filter.alpha.opacity = 100;
    }
    else
        z.style.opacity = 1;
    var z = CreateElement( "uncertain_position" , "blogcube_js_fade_" + id , "display:none" );
    if( z )
        z.innerHTML = 1;
}

function fadein(id,fromtimeout,milliseconds,mmxf,sstep,callbackondone) {
    if(!milliseconds)
        milliseconds = 100;
    if(!mmxf)
        mmxf = mxf;
    if(!sstep)
        sstep = 0.05;
    var z = CreateElement( "uncertain_position" , "blogcube_js_fade_" + id , "display:none" );
    if( !z )
        return;
    if( !z.innerHTML || !fromtimeout )
        z.innerHTML = "0";
    var dfade = z.innerHTML - 0;
    kk = g(id);
    if ( !kk )
        return;
    <?php
        // hackish
        if ( $user->Id() == 1 ) {
            // dionyziz
            ?>
            kk.style.opacity = "1";
            return;
            <?php
        }
    ?>
    dfade += sstep;
    if ( dfade >= mmxf ) {
        dfade = mmxf;
        kk.style.opacity = dfade;
        if( kk.style.filter )
            if( kk.style.filter.alpha )
                kk.style.filter.alpha.opacity = (dfade * 100);
        z.innerHTML = dfade;
        setTimeout( callbackondone , 10 );
        return;
    }
    kk.style.opacity = dfade;
    if( kk.style.filter )
        if( kk.style.filter.alpha )
            kk.style.filter.alpha.opacity = (dfade * 100);
    z.innerHTML = dfade;
    setTimeout( "fadein('" + id + "',true," + milliseconds + "," + mmxf + "," + sstep + ",'" + callbackondone + "')" , milliseconds );
}

function fadeout(id,fromtimeout,milliseconds,mmxf,sstep,callbackondone) {
    if(!milliseconds)
        milliseconds = 100;
    if(!mmxf)
        mmxf = 0;
    if(!sstep)
        sstep = 0.05;
    var z = CreateElement( "uncertain_position" , "blogcube_js_fade_" + id , "display:none" );
    if( !z )
        return;
    if( !z.innerHTML || !fromtimeout )
        z.innerHTML = "1";
    var dfade = z.innerHTML - 0;
    kk = g(id);
    if ( !kk )
        return;
    if ( dfade <= mmxf ) {
        dfade = mmxf;
        kk.style.opacity = dfade;
        if( kk.style.filter )
            if( kk.style.filter.alpha )
                kk.style.filter.alpha.opacity = (dfade * 100);
        z.innerHTML = dfade;
        setTimeout( callbackondone , 10 );
        return;
    }
    kk.style.opacity = (dfade -= sstep);
    if( kk.style.filter )
        if( kk.style.filter.alpha )
            kk.style.filter.alpha.opacity = (dfade * 100);
    z.innerHTML = dfade;
    setTimeout( "fadeout('" + id + "',true," + milliseconds + "," + mmxf + "," + sstep + ",'" + callbackondone + "')" , milliseconds );
}

function RGBColor(r,g,b) {
    this.mR = r;
    this.mG = g;
    this.mB = b;
    this.mCSS = "rgb(" + r + "," + g + "," + b + ")";
}

function TextFadeOut(string, startcolor, endcolor, maxlen, fadechars) {
    if( !startcolor )
        startcolor = new RGBColor(0,0,0);
    if( !endcolor )
        endcolor = new RGBColor(255,255,255);
    if ( string.length > maxlen ) {
        s = "";
        st = ist = maxlen - fadechars;
        colormix = new RGBColor(endcolor.mR - startcolor.mR, endcolor.mG - startcolor.mG, endcolor.mB - startcolor.mB);
        for (i=0; i < fadechars + 2; i++ ) {
            resultcolor = new RGBColor(
                Math.round(colormix.mR * ( colorpart = i / (fadechars + 2) ) + startcolor.mR),
                Math.round(colormix.mG * colorpart + startcolor.mG),
                Math.round(colormix.mB * colorpart + startcolor.mB)
            );
            s += "<span style=\"color:" + resultcolor.mCSS + ";\">" + string.substr(st,1) + "</span>";
            st++;
        }
        return string.substr(0 , ist ) + s;
    }
    return string;
}
