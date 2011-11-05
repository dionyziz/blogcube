<?php
    die( "Access Denied" );
?><html>
<head>
    <title>Upload File</title>
    <link href="style.css.php" rel="stylesheet" type="text/css" />
</head>
<body>
<h1>Simple File Upload</h1>
<span class="content">
Select the file to upload and hit `Upload' to transfer the file to Hades.
<br /><br />
<form method="POST" enctype="multipart/form-data" action="upload.php">
<input type="file" name="uploadfile" /><br /><br />
<input type="submit" value="Upload" class="mybutton" />
</form>
</span>
</body>
</html>
