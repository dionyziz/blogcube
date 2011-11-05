<?php
    $content_type = "text/css";
    include "header.php";
?>
body {
    display:block;
    font-family:verdana, sans-serif;
    margin:2%;
    font-size:90%;
    color:#000000;
    background:#ffffff;
}

td {
    font-size: 90%
}

td.file_nfo {
    font-size: 60%;
    color: gray;
}

img {
    border-style: none;
    vertical-align: middle;
}

a:link,a:visited,a:active {
    text-decoration: none;
    color: royalblue;
}

a:hover {
    color: chocolate;
}

ul {
    display: inline;
}

li {
    display: inline;
    margin-right: 1em;
}

h1 {
    display:block;
    font-size:1.3em;
    color:inherit;
    background:inherit;
    font-weight:bold;
}

h2 {
    display:block;
    font-size:0.9em;
}

span.entry {
    display:block;
    color:inherit;
    background:inherit;
    padding:0;
    margin:1em 1em 1em 1em;
}

h4 {
    display:inline;
    color:#999999;
    background:inherit;
    font-size:0.8em;
}

h3 {
    display:block;
    font-size:1em;
    font-weight:bold;
    color:inherit;
    background:inherit;
    padding:1em 1em 0em 1em;
    margin:0;
    border-top:solid 1px #dddddd;
}

span.content {
    display:block;
    font-size:0.9em;
    color:inherit;
    background:inherit;
    padding:1em;
    line-height:1.5em;
}

input.terminal {
    background-color:black;
    color:white;
    border-style:none;
    width: 100%;
    display: block;
    font-size: 0.9em;
    font-family: Verdana;
}

span.terminal,td.terminal {
    background-color: black;
    color:white;
    display:block;
    font-size:0.9em;
}

span.terminal {
    border: 2px solid royalblue;
}


<?
    include "footer.php";
?>