<html>
<head>
<link href="style.css.bc" type="text/css" rel="stylesheet" />
</head>
<body>
<table class="formtable" style="font-size:95%">
    <tr>
        <td class="ffield" align="center"><b>Upload your photo</b></td>
    </tr>
    <tr>
        <td class="nfield">
        <form enctype="multipart/form-data" action="<?php echo $systemurl."/cubism.bc?g=" ?>media/albums/upload_mediaphoto" method="post">
            <input type="hidden" name="MAX_FILE_SIZE" value="16777216" />
            <input type="file" name="userfile1" />
            <br /><br />
            <div id="media1"></div>
        </td>
    </tr>
    <tr>    
        <td class="nfield">
            <input type="submit" value="Send" />
        </td>
    </tr>
</table>
<br />
</body>
</html>