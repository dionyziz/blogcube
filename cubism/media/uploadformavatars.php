<html>
<head>
<link href="style.css.bc" type="text/css" rel="stylesheet" />
</head>
<body style="background-color:#ccd6ee">
    <form enctype="multipart/form-data" action="<?php 
        echo $systemurl; 
        ?>/cubism.bc?g=media/upload_avatar" id="f_avup" method="post">
        <input type="hidden" name="MAX_FILE_SIZE" value="16777216" />
        <input type="file" name="userfile" onchange="parent.uploadavatar()" />
        <input type="submit" value="Upload" style="display:none" />
    </form>
</body>
</html>