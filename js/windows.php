<?php
    include "js/js.php";
?>
/*
    Developer: dionyziz
*/
var mousex = 0;
var mousey = 0;
var dragobj = null;
var grabx = 0;
var graby = 0;
var orix = 0;
var oriy = 0;
var maxz = 500;
var dragging = false;
var WIN_MS = 30;
// how many positions which the mouse 
// was on in the past to keep stored?

function falsefunc() { return false; } 

function getMouseXY(e) { 
  // this function gets current mouse position
  // and stores it in (mousex, mousey)
  if (!e) e = window.event; // works on IE, but not NS (we rely on NS passing us the event)

  if (e) { 
    if (e.pageX || e.pageY) { // this doesn't work on IE6!! (works on FF,Moz,Opera7)
      mousex = e.pageX;
      mousey = e.pageY;
    }
   else if (e.clientX || e.clientY) { // works on IE6,FF,Moz,Opera7
      mousex = e.clientX + b.scrollLeft;
      mousey = e.clientY + b.scrollTop;
    }
  }
}

d.onmousemove = getMouseXY;

function grab(context, e) {
  if (!e) e = window.event;

  // this function is called when the user starts dragging the window
  k = g( context );
  if ( !k )
    return;
  d.onmousedown = falsefunc; // in NS this prevents cascading of events, thus disabling text selection
  d.onmousemove = drag;
  dragobj = k;
  dragobj.style.zIndex = ++maxz; // move window that we are dragging to the top of everything else
  getMouseXY(e);
  grabx = mousex;
  graby = mousey;
  elex = orix = findPosX(dragobj); // parseInt(dragobj.style.left,10);
  eley = oriy = findPosY(dragobj); // parseInt(dragobj.style.top,10);
  dragging = true;
  drag();
  dragobj.style.right = '';
  dragobj.style.bottom = '';
}

function drag(e) // parameter passing is important for NS family 
{
   // This function handles the object being dragged...
   if (dragobj) {
      if ( !dragging ) {
         dragobj = null;
         return;
      }
      newx = orix + (mousex-grabx); // <-- new x position of element according to mouse position
      newy = oriy + (mousey-graby); // <-- new y position.
      // check if element gets outside of the borders of the website window
      /* if ( ( newx + dragobj.scrollWidth > b.scrollWidth ) )
         newx = b.scrollWidth - dragobj.scrollWidth;
      else if( newx < 0 )
         if ( newx + dragobj.scrollWidth > b.scrollWidth )
            newx = b.scrollWidth - dragobj.scrollWidth; // gets out from the right side
         else if( newx < 0 )
            newx = 0;
      if ( newy + dragobj.scrollHeight > b.scrollHeight )
         newy = b.scrollHeight - dragobj.scrollHeight;
      else if( newy < 0 )
         newy = 0; */
      // actually move the object here.
      // dragobj contains reference to the HTML DOM object
      // of which we adjust the position
      dragobj.style.left = newx.toString(10) + 'px';
      dragobj.style.top = newy.toString(10) + 'px';
      // make sure the drag function is called again in 20 ms
   }
   // and get new mousepos
   getMouseXY(e);
   return false; // in IE this prevents cascading of events, thus text selection is disabled
}

b.onmouseup = function drop() {
  if (dragobj) {
    dragging = false;
  }
  getMouseXY();
  d.onmousemove = null; // getMouseXY;
  d.onmousedown = null;   // re-enables text selection on NS
};

var minimizeid = "";
var minix = 0;
var miniy = 0;
var minixsp = 0;
var miniysp = 0;
var finaly = 0;
var minicount = 0;
var EPSILON = 2;
var thiscount = 0;
var animating = false;

function Modalize(makemodal) {
    var z = CreateElement( 'bb' , 'modal_bg' , "position:fixed;left:0;right:0;top:0;bottom:0;z-index:100;background-image:url('images/modalbg.png');background-repeat:repeat;" );
    z.innerHTML = '&nbsp;';
    if (makemodal) {
        z.style.display = '';
    }
    else {
        z.style.display = 'none';
    }
}

function miniWindow( id ) {
    if ( animating ) {
        minix = 0;
        miniy = finaly;
        miniWinAnimate();
    }
    minimizeid = id;
    var allminis = "dynamic_minimized_windows";
    var miniid = "dynamic_win_minimized_" + id;
    var bigid = "dynamic_win_div_" + id;
    var titid = "dynamic_win_title_" + id;
    var idid = "dynamic_win_minicount_" + id;
    
    var l = g( bigid );
    if ( !l )
        return;
    var tit = g( titid );
    if ( !tit )
        return;
    l.style.display = "none";
    var k = CreateElement( "bb" , allminis , "position:absolute;left:3;top:5;" );
    var x = l.style.left.toString();
    var y = l.style.top.toString();
    minix = x.substr(0, x.length - 2);
    miniy = y.substr(0, y.length - 2);
    var y = CreateElement( allminis , idid , "display:none" );
    if ( y.innerHTML ) {
        thiscount = y.innerHTML;
    }
    else {
        y.innerHTML = thiscount = minicount++;
    }
    var z = CreateElement( allminis , miniid , "position:absolute;left:" + minix + "px;top:" + miniy + "px;" );
    finaly = thiscount * 20;
    minixsp = minix / 30;
    miniysp = (miniy - finaly) / 30;
    // alert( minixsp + ", " + miniysp );
    z.innerHTML = "<acronym title=\"Restore this Window\"><a href=\"javascript:restoreWindow('" + id + "');\">" + tit.innerHTML + "</a></acronym>";
    z.style.display = "";
    animating = true;
    miniWinAnimate();
}

function miniWinAnimate() {
    var id = minimizeid;
    var miniid = "dynamic_win_minimized_" + id;
    var bigid = "dynamic_win_div_" + id;
    var z = d.getElementById( miniid );
    minix-=minixsp;
    miniy-=miniysp;
    z.style.left = minix + 'px';
    z.style.top = miniy + 'px';
    if ( minix <= EPSILON && miniy - finaly <= EPSILON ) {
        minix = 0;
        miniy = finaly;
        z.style.left = minix + 'px';
        z.style.top = finaly + 'px';
        animating = false;
    }
    else {
        setTimeout( "miniWinAnimate();" , 20 );
    }
}

function restoreWindow( id ) {
    var allminis = "dynamic_minimized_windows";
    var miniid = "dynamic_win_minimized_" + id;
    var bigid = "dynamic_win_div_" + id;
    var idid = "dynamic_win_minicount_" + id;
    
    var l = g( bigid );
    if ( !l ) {
        de( "window_inaccessible" , "inaccessible" , "uncertain_position" );
    }
    else {
        l.style.display = "";
        l.style.zIndex = ++maxz;
    }
    var m = g( miniid );
    if ( !m )
        return;
    m.style.display = "none";
}
