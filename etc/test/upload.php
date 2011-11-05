<?php
    die( "Access Denied" );
    
    if ( !$_FILES['uploadfile']['name'] ) {
        die( "You didn't select a file!" );
    }
    
    $fileid = strtolower( basename($_FILES['uploadfile']['name']) );
    
    $tempfile = $_FILES['uploadfile']['tmp_name'];
    
    $handle = fopen( $tempfile, "rb" );
    $contents = fread( $handle, filesize($tempfile) );
    fclose($handle);

    $handle = fopen( "uploads/" . $fileid , "wb" );
    fwrite( $handle , $contents );
    fclose( $handle );
?>
<html>
<head>
    <title>Upload File</title>
    <link href="style.css.php" rel="stylesheet" type="text/css" />
</head>
<body>
<h1>Simple File Upload</h1>
<span class="content">
The file was uploaded succesfully on /var/www/vhosts/blogcube.net/httpdocs/beta/etc/test/uploads/<?php
    echo $fileid;
?>.<br /><br />
<a href="uploads/<?php
    echo $fileid;
?>">View Uploaded File</a><br />
<a href="index.php">Upload another file</a>
</span>
</body>
</html>
