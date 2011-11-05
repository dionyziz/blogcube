<?php
    include "css/css.php";
    
    /*
    $templateid = 0;
    $template = New Template( $templateid );
    */

?>
body {
    font-family: Arial, sans-serif;
    background-color: white;
    color: black;
}
td.aielposts {
    padding-left: 20px;
    padding-right: 20px;
    border-right: 1px solid #81a5c1;
    border-left: 1px solid #81a5c1;
}
div.bcb {
    width: 100%;
    margin: auto;
}
li {
    padding-bottom: 1px;
}
i {
    font-style:italic;
    font-family: Helvetica;
    font-weight:normal;
}
div.comment {
    padding-left: 8px;
    border-left-style: dotted;
    border-left-width: 1px;
}
div#blogcentral {
    height: 100%;
    width: 100%;
}
td.sidebarcontainerright {
    background-color: #e3e9f9;
    color: #222222;
    width: 250px;
    vertical-align: top;
    padding-left: 20px;
}
td.sidebarcontainerleft {
    background-color: #e3e9f9;
    color: #222222;
    display:none;
    vertical-align: top;
    padding-left: 20px;
}
a:link,a:active,a:visited {
    color: #3366cc;
    background-color: transparent;
    font-size: small;
    border-style: none;
    border-bottom: 1px solid #3366cc;
    padding: 0 0 0 0;
    margin: 0 0 0 0;
    text-decoration: none;
}
a:hover {
    color: #55ad61;
    background-color: transparent;
    border-bottom: 1px solid #55ad61;
}
a.noformat:link,a.noformat:active,a.noformat:visited {
    color: black;
    font-size: small;
    text-decoration: none;
    background-color: transparent;
    background-image: none;
    border-style: none;
}
a.postbutton:link,a.postbutton:active,a.postbutton:visited {
    border-bottom-style: none;
}
a.postbutton:hover {
    border-bottom-style: none;
}
h2 { /* blog title */
    font-size: xx-large;
    font-family: Verdana;
    color: #16365a;
    cursor: default;
}
span.date { /* date */
    display: block;
    color: #666666;
    font-weight: bold;
    cursor: default;
    font-size: 85%;
    padding-bottom: 13px;
}
h3 { /* post title */
    font-family: Arial;
    letter-spacing: 0;
    /* font-variant: small-caps; */
    font-size: 120%;
    display: inline;
    cursor: default;
    color: #3366cc;
    margin-bottom: 4px;
}
h5 { /* subtitle */
    font-size: medium;
    font-family: Arial;
    letter-spacing: 3px;
    font-weight: bold;
    cursor: default;
    color: #666666;
    padding-bottom: 20px;
    border-bottom: 1px solid #cccccc;
}
h6 { /* sidebar title */
    font-family: Arial;
    letter-spacing: 0;
    font-size: medium;
    display: inline;
    cursor: default;
    color: #222222;
}
span.blogtext {
    font-family: Arial;
    font-size: 100%;
    letter-spacing: 1px;
    font-weight: normal;
    font-style: normal;
    color: black;
    padding-right: 5px;
}
span.firstletter {
}
div.bpsig {
    font-size: small;
    color: #666666;
    font-style: normal;
    display: block;
    border-bottom: 1px solid #cccccc;
    padding-bottom: 20px;
}
input {
    font-family: Arial, sans-serif;
    background-color: white;
    border: 1px solid black;
    font-size: 100%;
}
textarea.blogcute {
    background-image:url('images/blogcutew.jpg');
    background-repeat:no-repeat;
    background-position:top right;
    border: 1px solid black;
    padding-right: 3px;
}
span.arrow { 
    font-size: 100%;
    color: #b96000;
}
table.oneblogpost { 
    border-bottom: 1px dotted #aaaaaa;
}
div#blogcopyright {
    color: black;
    font-size: 90%;
}
hr {
    background-color: #cccccc;
}

/* dropdown css */
div.dropdownbox {
    z-index: 1000;
    background-color: #e6eaff;
    border-top: 1px solid #3979bd;
    border-left: 1px solid #3979bd;
    border-right: 1px solid #3979bd;
    position: absolute;
    left: 0;
    top: 15px;
    width: 150px;
}
div.dropdownitem {
    display: block;
    border-bottom: 1px solid #3979bd;
    padding: 2px 2px 2px 2px;
}
div.dropdownitem a {
    display: block;
    text-align: left;
    font-weight: normal;
    text-decoration: none;
}
div.dropdownitem a:hover {
    padding-left: 2px;
    background-color: #3979bd;
    color: #e6eaff;
}
div.dropdownboxright {
    right: 0;
}
div.dropdownboxleft {
    left: 0;
}
div.dropdownparent {
    display: inline;
    position: relative;
    border-width: 0;
}
a.dropdownlink {
    display: inline;
    border: 0px none transparent;
}
a.dropdownlink:hover {
    background-color: transparent;
}
span.blogtitle {
    font-size: xx-large;
    font-family: Verdana;
    color: #16365a;
    cursor: default;
    font-weight: bold; 
    vertical-align: middle; 
    padding-top: 70px;
}
span.blogdate {
    display: block;
    color: #666666;
    font-weight: bold;
    cursor: default;
    font-size: 85%;
    padding-bottom: 13px;
}
span.blogposttitle {
    font-family: Arial;
    letter-spacing: 0;
    font-size: 120%;
    display: inline;
    cursor: default;
    color: #3366cc;
    margin-bottom: 4px;
    font-weight: bold;
}
a.bloglinks:link, a.bloglinks:active, a.bloglinks:visited {
    color: #3366cc;
    background-color: transparent;
    font-size: small;
    border-style: none;
    border-bottom: 1px solid #3366cc;
    padding: 0 0 0 0;
    margin: 0 0 0 0;
    text-decoration: none;
    border-bottom-style: none;
}
a.bloglinks:hover {
    color: #55ad61;
    background-color: transparent;
    border-bottom: 1px solid #55ad61;
    border-bottom-style: none;
}