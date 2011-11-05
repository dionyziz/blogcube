<html><head><title>Upload your photo</title>
<link href="style.css.php" type="text/css" rel="stylesheet" />
</head>

<body>
<?php
        $thisfile = $_FILES[ "userfile1" ];
        if ( $thisfile ) {
            $m1++;    
        }
        if ( !is_uploaded_file( $thisfile[ 'tmp_name' ] ) ) {
            echo "Possible deception trial<br />";
            exit;
        }
        if ( $thisfile[ 'size' ] > 16*1024*1024 ) {
            echo "Maximum size of a file is 16MB";
            exit;
        }    
        if ( !$m1 ) {
            exit;
        }
        if ( !$thisfile[ 'size' ] ) {
            exit;
        }
        $tempname = $thisfile[ 'tmp_name' ];
        // get the original filename
        //realname should have it's extension, so remove the RemoveExtension() function
        $realname = RemoveExtension( $thisfile[ 'name' ] );
        $album_name = gmdate( "l jS F Y" );
        $albumid = album_create( $album_name , "public" );
        $mediaid = add_photo_toalbum( $realname , $tempname , $albumid );
        set_album_mainpicid( $mediaid, $albumid );
        
?>this is a test
<script type="text/javascript"><?php 
bc_ob_end_flush();
bc_ob_section();
?>
parent.albumdm('media/albums/albums_list');
<?php
echo bc_ob_fallback();
?>
</script>
</body>
</html>