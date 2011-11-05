<?php
    include "js/js.php";
?>
function formuladoinsert( formula ) {
    de( 'formula_loader' , 'blogging/math/applyformula&formula=' + se( encode64( formula ) ) , '' , 'Generating Image...' );
}
function mathexp( categoryid ) {
    for( i = 0 ; i <= 7 ; i++ ) {
        g( 'math_ax_' + i ).style.backgroundColor = g( 'math_ax_' + i ).style.borderColor = "#e3e9f9";
    }
    g( 'math_ax_' + categoryid ).style.borderColor = "#3979c1";
    g( 'math_ax_' + categoryid ).style.backgroundColor = "#ccd6ee";
    g( 'operexp' ).innerHTML = "<br />" + g( 'math_x_' + categoryid ).innerHTML;
}
function texinsert( tex ) {
    var s = g("newformula");
    //IE
    if (d.selection) {
        s.focus();
        (d.selection.createRange()).text=tex;
        s.focus();
    }
    //MOZ
    else if (s.selectionStart||s.selectionStart=="0") {
        s.value = s.value.substring(0,s.selectionStart) + tex + s.value.substring(s.selectionEnd,s.value.length);
    } 
    else {
        s.value += tex;
    }
}
function insertclear() {
    formuladoinsert( g('newformula').value );
    g('newformula').value = '';
    formulacancel();
}
function formulacancel() {
    g( "wyswgrealr" ).style.display = "";
    g( "mathtoolbar" ).innerHTML = "";
    g( "mathtoolbarp" ).style.display = "none";
    g('mathtobpar').style.height = "1px";
}
function BCWYSFormula() {
    g( "wyswgrealr" ).style.display = "none";
    g( "mathtoolbarp" ).style.display = "";
    de( 'mathtoolbar' , "blogging/math/insertformula" , "" , "Loading Formula Editor..." );
}
