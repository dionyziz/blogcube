<?php 
    //look at the id's for the form later on
?>
<html>
<head>
<link href="style.css.bc" type="text/css" rel="stylesheet" />
</head>
<body style="background-color:#f7f7ff;width:100px;height:50px;">
    <form enctype="multipart/form-data" action="<?php 
        echo $systemurl; 
        ?>/cubism.bc?g=media/firstalbumupload" id="f_photoup" method="post">
        <input type="hidden" name="MAX_FILE_SIZE" value="16777216" />
        <input type="file" name="userfile" onchange="parent.uploadphoto()" />
        <input type="submit" value="Upload" style="display:none" />
    </form>
    <div>
</body>
</html>