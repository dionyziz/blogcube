<?php
    $url = $_POST[ "url" ];
    
    if ( substr( $url , 0 , 7 ) != "http://" &&
         substr( $url , 0 , 8 ) != "https://" &&
         substr( $url , 0 , 6 ) != "ftp://" ) {
        ?><br />
        The image above is not valid. Please make sure
        that the image url begins with http://, https:// or 
        ftp://.<br /><?php
        exit();
    }
    
    $imageres = @fopen( $url , "r" );
    if ( !$imageres ) {
        ?><br />
        The URL of the image is incorrect. Either
        the hostname or IP you typed is nonexisting,
        or the address to the image file could not
        be found. Please make sure that you typed 
        the image URL correctly.<br /><?php
        exit();
    }
    
    $bin = "";
    while ( !feof( $imageres ) ) {
        $bin .= fread( $imageres , 1024 );
    }
    
    $img = @imagecreatefromstring( $bin );
    if ( !$img ) {
        ?><br />
        The URL above is valid, yet it seems not to be an image. 
        Please make sure you typed a URL to a valid image file,
        whether that is a .jpg or a .png file. Using exotic image
        formats is not suggested, as most users will not be able
        to view them with their browsers. If you want to use
        such an image format, or insert a resource other than
        a picture, use a link instead.<br /><?php
        exit();
    }
?>(image ok)
