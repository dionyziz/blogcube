<?php
    include "js/js.php";
?>
function tmgreet() {
var d = new Date();
var h = d.getHours();
if (h < 2) return "Heya";
else if (h < 3) return "Hio";
else if (h < 6) return "Greetings";
else if (h < 7) return "G'mornin'";
else if (h < 12) return "Mornin'";
else if (h < 13) return "Hi there";
else if (h < 14) return "Aloha";
else if (h < 15) return "Hello";
else if (h < 16) return "Ahoi";
else if (h < 17) return "Hola";
else if (h < 23) return "'Allo";
else return "'lo";
}
