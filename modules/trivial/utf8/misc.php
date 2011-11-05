<?php
    function utf8RawUrlDecode ($source) {
       $decodedStr = "";
       $pos = 0;
       $len = strlen ($source);
       while ($pos < $len) {
           $charAt = substr ($source, $pos, 1);
           if ($charAt == '%') {
               $pos++;
               $charAt = substr ($source, $pos, 1);
               if ($charAt == 'u') {
                   // we got a unicode character
                   $pos++;
                   $unicodeHexVal = substr ($source, $pos, 4);
                   $unicode = hexdec ($unicodeHexVal);
                   $entity = "&#". $unicode . ';';
                   $decodedStr .= utf8_encode ($entity);
                   $pos += 4;
               }
               else {
                   // we have an escaped ascii character
                   $hexVal = substr ($source, $pos, 2);
                   $decodedStr .= chr (hexdec ($hexVal));
                   $pos += 2;
               }
           } else {
               $decodedStr .= $charAt;
               $pos++;
           }
       }
       return $decodedStr;
    }
?>