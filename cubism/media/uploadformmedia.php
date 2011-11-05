<html>
<?php
/*    <style type="text/css">
        body {
            height: "100%";
        }
        table {
            height: "100%";
        }
    </style>
*/
?>
<head>
<link href="style.css.bc" type="text/css" rel="stylesheet" />
<script type="text/javascript" >
var divcont;
var counter = 0;
var newcount;
var mediaid2;
var mediaid;
function addupload () {
    counter++;
    mediaid = "media"+counter;
    newcount = counter +1;
    mediaid2 = "media"+newcount;
    divcont = '<input type="file" name="media' + counter + '" /><br /><br /><div id="' + mediaid2 + '"></div>';
    document.getElementById( mediaid ).innerHTML = divcont;
}
</script>
</head>
<body>
<table class="formtable" style="font-size:95%">
    <tr>
        <td class="ffield" align="center"><b>Upload your files</b></td>
    </tr>
    <tr>
        <td class="nfield">
        <form enctype="multipart/form-data" action="<?php echo $systemurl."/cubism.bc?g=" ?>media/upload_media" method="post">
            <input type="hidden" name="MAX_FILE_SIZE" value="16777216" />
            <input type="file" name="userfile1" />
            <br /><br />
            <div id="media1"></div>
        </td>
    </tr>
    <tr>
        <td class="nfield"><?php echo $arrow; ?><a href="javascript:addupload();">Upload another file</a></td>
    </tr>
    <tr>
        <td class="nfield">
            <input type="submit" value="Send" />
            </form>
        </td>
    </tr>
    
</table>
<br />
</body>
</html>