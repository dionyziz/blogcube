<?php
    include "css/css.php";
?>
html, body {
    width: 100%;
}
td {
    font-size: 10pt;
}
div.bcb {
    text-align: left;
    margin: auto;
}
textarea {
    border: 1px solid #275383;
    font-family: verdana;
    font-size: 100%;
}
table.checkit {
    background-position:right;
    background-repeat:repeat-y;
    background-image:url('images/checkitendright.jpg');
    border-left:1px dotted #275383;
    margin-left: 2px;
}
table.checkit,table.checkitright {
    background-color:#f2f5fb;
}
table.checkitright {
    background-position:left;
    background-repeat:repeat-y;
    background-image:url('images/checkitendleft.jpg');
    border-right:1px dotted #275383;
    margin-right:2px;
}
table.checkit td, table.checkitright td {
    color: #275383;
}
table.checkit td h2, table.checkitright td h2 {
    color: #16365a;
    font-size: 120%;
    font-family: Helvetica;
    display: block;
}
table.checkit td i, table.checkitright td i {
    font-size: 90%;
    color: #3B7ABF;
    font-style: normal;
}
div.popupmnu {
    border-left: 3px solid #62b36e;
    border-top: 1px solid #62b36e;
    border-right: 1px solid #62b36e;
    border-bottom: 1px solid #62b36e;
    background-color: #ccd5ed;
}
td.popupitem { 
    border-bottom: 1px solid #62b36e;
}
table.intracted {
    border-left: 3px solid #8dafd4;
    border-bottom: 1px solid #8dafd4;
    border-right: 1px solid #3b7abf;
}
table.intracted td {
    background-color: #ccd5ed;
}
div#globalwarning {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    background-color: #ffffe1;
    border-bottom: 1px solid black;
    z-index: 2;
    padding: 1px 1px 1px 1px;
    vertical-align: middle;
    cursor: default;
}
div#centraltable {
    width:760px;
    height:100%;
    margin:auto;
    text-align:left;
}
td#usergreeting {
    text-align: left;
}
table#topbartbl {
    margin: auto;
}
div#nobrowser {
    margin:auto;
    text-align:center;
    width:760px;
}
td.ffoldername,td.friendname {
    text-align: left;
    width: 100%;
}
td.ffoldericon,td.ffoldericonx {
    width: 17px;
}
td.ffoldericonx {
    vertical-align: middle;
}
blockquote {
    padding-top: 0;
    padding-bottom: 0;
    margin-top: 0;
    margin-bottom: 0;
    padding-left: 4px;
    margin-left: 4px;
}
td.ffield {
    border-top: 1px solid #3B7ABF;
    background-color: #E3E9F9;
    padding-left: 4px;
    padding-top: 2px;
    padding-bottom: 2px;
    padding-right: 4px;
    vertical-align: top;
}
td.nfield {
    border-top: 1px solid #8dafd4;
    background-color: #E3E9F9;
    padding-left: 4px;
    padding-top: 2px;
    padding-bottom: 2px;
    padding-right: 4px;
    vertical-align: top;
}
td.efield {
    background-color: #E3E9F9;
    padding-left: 4px;
    padding-top: 2px;
    padding-bottom: 2px;
    padding-right: 4px;
    vertical-align: top;
}
td.ssfield {
    padding-left: 4px;
    padding-top: 2px;
    padding-bottom: 2px;
    padding-right: 4px;
    text-align: right;
}
td.topbar {
    background-color:#F3F3F3;
    color:#666666;
    padding: 3px 3px 3px 3px;
    background-image: url('images/dotted.gif');
    background-position: bottom;
    background-repeat: repeat-x;
}
td.topbarright {
    background-color:#F3F3F3;
    color:#666666;
    padding: 3px 3px 3px 3px;
    background-image: url('images/dotted.gif');
    background-position: bottom;
    background-repeat: repeat-x;
    text-align: right;
}
td#msg_msgstyle {
    background-image: url('images/vdotted.gif');
    background-repeat: repeat-y;
    background-position: left;
}
div#msg_msgview {
    padding: 3px 3px 3px 3px;
}
#username {
    color: black;
}
small {
    color: #3B7ABF;
    font-size: 95%;
}
small.z {
    color: white;
}
span.arrow {
    font-family: Arial;
    color: #54AE64;
    font-size: large;
    font-weight: bold;
    padding-right: 2px;
}
span#footrid { 
    width: 760px;
    font-size: 85%;
    color: #aaaaaa;
    cursor: default;
    text-align: left;
    padding: 5px;
}
div#footrd {
    width: 760px;
    margin: auto;
}
div#mncntr {
    height: 100%;
}
table#cubelogo {
    background-image: url('images/dotted.gif');
    background-repeat: repeat-x;
    background-position: bottom;
    margin: auto;
}
a.simplelink:link,a.simplelink:active,a.simplelink:visited {
    border-width: 0px;
    background-image: none;
    background-color: transparent;
    color: #3979C1;
    text-decoration: none;
}
a:link,a:active,a:visited {
    color: #3979C1;
    text-decoration: underline;
}
a:hover {
    background-color:#55AD61;
    text-decoration: none;
    color: white;
}
a.windowbutton:link,a.windowbutton:active,a.windowbutton:visited {
    font-family: Arial;
    text-decoration: none;
    color: #3C78BE;
    margin-bottom: 2px;
    font-weight: bold;
    font-size: small;
    padding: 0px 3px 0px 3px;
}
a.windowbutton:hover {
    background-color: #f0f0f0;
}
h3 {
    font-family: Arial;
    letter-spacing: 3px;
    font-size: 16pt;
    display: inline;
    cursor: default;
    color: #56AC61;
}
h4 {
    font-family: Arial;
    letter-spacing: 3px;
    font-size: 14pt;
    display: inline;
    cursor: default;
    color: #3B7ABF;
}
img {
    border-width: 0px;
}
input.inpt {
    color: #3B7943;
    font-family: verdana;
    font-weight: bold;
    border: 1px solid #275383;
    vertical-align: middle;
}
input.inbt {
    color: #3b4f79;
    font-family: verdana;
    font-weight: bold;
    border: 1px solid #275383;
    vertical-align: middle;
}
input.logininpt, input.loginpwd, input.logintrng {
    color: #275383;
    font-family: Helvetica;
    font-weight: bold;
    font-size: 100%;
    border: 1px solid #275383;
    background-position:left;
    background-repeat: no-repeat;
    padding-left: 20px;
    height: 17px;
    width: 150px;
    vertical-align: middle;
}
input.logininpt {
    background-image: url( 'images/nuvola/account.jpg' );
    background-position: 0 0;
}
input.loginpwd {
    background-image: url( 'images/nuvola/password.jpg' );
    background-position: 0 0;
}
input.logintrng {
    background-image: url( 'images/silk/textfield_key.png' );
    background-position: 0 0;
}
td.hpitm {
    font-size: 100%;
}
div.avatar_td, div.blogpanel {
    text-align: left;
    border-top: 1px solid #8dafd4;
    border-left: 1px solid #8dafd4;
    border-right: 1px solid #8dafd4;
    background-color: #e3e9f9;
    padding-left: 5px;
    padding-top: 1px;
    padding-bottom: 4px;
    padding-right: 1px;
    vertical-align: middle;    
    width: 500px;
}
div.avatar_td_default {
    text-align: left;
    border-top: 1px solid #8dafd4;
    border-left: 1px solid #8dafd4;
    border-right: 1px solid #8dafd4;
    background-color: #ccd6ee;
    padding-left: 5px;
    padding-top: 1px;
    padding-bottom: 4px;
    padding-right: 1px;
    vertical-align: middle;    
    width: 500px;
}
table.avatars_form_table {
    width: 500px;
}
div.avatar_upload_td , div.blogpanel_new {
    text-align: left;
    border: 1px solid #4c86c5;
    background-color: #ccd6ee;
    padding-left: 5px;
    padding-top: 1px;
    padding-bottom: 1px;
    padding-right: 1px;
    width: 500px;
}
div.avatar_td img /* , div.blogpanel img */ {
    border: 1px solid #8dafd4;
}
td.avatar_upload_select,td.avatar_upload_submit, td.avatar_upload_real {
    border: 1px solid #8dafd4;
    background-color: #e3e9f9;
}
td.contacts_td {
    text-align: center;
    border-top: 1px solid #3B7ABF;
    border-left: 1px solid #3B7ABF;
    border-right: 1px solid #3B7ABF;
    background-color: #ccd6ee;
    padding-left: 5px;
    padding-top: 1px;
    padding-bottom: 4px;
    padding-right: 1px;
    vertical-align: middle;    
}
td.view_contacts {
    text-align: left;
    border-left: 1px solid #3B7ABF;
    border-right: 1px solid #3B7ABF;
    border-bottom: 1px solid #3B7ABF;
    border-top: 1px solid #3B7ABF;
    background-color: #E3E9F9;
    padding-left: 5px;
    padding-top: 1px;
    padding-bottom: 4px;
    padding-right: 1px;
    vertical-align: middle;    
}
td.tipleft {
    border-left: 1px solid #3b7abf;
    background-color: #e3e9f9;
}
td.tipright {
    border-right: 1px solid #3b7abf;
    background-color: #e3e9f9;
}
td.tiptop {
    background-image: url('images/tipt.png');
    background-position: top;
    background-repeat: repeat-x;
    background-color: white;
}
td.tipbottom {
    background-image: url('images/tipb.png');
    background-position: bottom;
    background-repeat: repeat-x;
    background-color: white;
}
td.tiptip {
    background-color: #e3e9f9;
}
div.bcwindow {
    width: 400px;
    border: 1px solid #94beff;
    position: absolute;
}
div.bcwintitle {
    font-family: "Arial", "Helvetica", sans-serif;
    font-weight: bold;
    background-color: #cde2ff;
    border-bottom: 1px solid #a4ceff;
    color: #3979bd;
    cursor: move;
    padding: 2px 2px 2px 2px;
}
div.bcwincontent {
    background-color: #f6faff;
    opacity: .8;
    padding: 3px 3px 3px 3px;
    max-height: 500px;
    overflow: auto;
}
<?php
    include "css/media/albums.php";
    include "css/dropdown.php";
    include "css/messaging/messaging.php";
    include "css/math/math.php";
    include "css/friends/friends.php";
    include "css/debug/debug.php";
?>