/*
    Developer: dionyziz
*/

var popcurid = "";
var poptim;
var poptimset = false;
var mouse_x = 0;
var mouse_y = 0;

function MouseXY( e ) {
    // this function gets current mouse position
    // and stores it in (mousex, mousey)
    if (!e) e = window.event; // works on IE, but not NS (we rely on NS passing us the event)
    
    if (e) { 
        if (e.pageX || e.pageY) { // this doesn't work on IE6!! (works on FF,Moz,Opera7)
            mouse_x = e.pageX;
            mouse_y = e.pageY;
        }
        else if (e.clientX || e.clientY) { // works on IE6,FF,Moz,Opera7
            mouse_x = e.clientX + b.scrollLeft;
            mouse_y = e.clientY + b.scrollTop;
        }
    }
}

function PopupRelax() {
    if( poptimset ) {
        clearTimeout( poptim );
        poptimset = false;
    }
}

function PopupUnrelax() {
    if( poptimset ) {
        clearTimeout( poptim );
    }
    poptim = setTimeout( "PopupDestruct()" , 2000 );
    poptimset = true;
}

function PopupDestruct() {
    z = g( popcurid );
    z.style.display = "none";
    poptimset = false;
    // by ch-world
    document.onmouseup = null;
}

function PopupMenu( tagid , e ) {
    // this function gets current mouse position
    // and stores it in (mousex, mousey)
    getMouseXY( e );

    // by ch-world
    document.onmouseup = PopupUnmenu;
    // end by

    popcurid = tagid;
    z = g( tagid );
    z.style.position = "absolute";
    z.style.left = mousex + 'px';
    z.style.top = mousey + 'px';
    z.style.display = "";
    //z.onmouseover = PopupRelax;
    //z.onmouseout = PopupUnrelax;
    poptimset = false;
    return false;
}

function PopupUnmenu() {
    PopupDestruct();
}
