<?php
    $albumid = $_GET[ "albumid" ];
?>
<html>
<head>
<link href="style.css.bc" type="text/css" rel="stylesheet" />
</head>
<body style="background-color:#f7f7ff;width:100px;height:50px;">
    <form enctype="multipart/form-data" action="<?php 
        echo $systemurl; 
        ?>/cubism.bc?g=media/uploadphotosserver" id="f_photoup" method="post">
        <input type="hidden" name="MAX_FILE_SIZE" value="16777216" />
        <input type="file" name="userfile" onchange="parent.uploadphoto()" />
        <input type="hidden" name="albumid" value="<?php
        echo $albumid;
        ?>" />
        <input type="submit" value="Upload" style="display:none" />
    </form>
</body>
</html>