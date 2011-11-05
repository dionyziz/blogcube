<?php
    /* 
    Module: Mime type recognition
    File: /modules/media/mime.php
    Developer: Izual
    */

    function getextension( $filename ) {
        $strlength = strlen( $filename );
        $dotposition = strrpos( $filename , "." );
        $extension = substr( $filename , $dotposition + 1 , $strlength - $dotposition + 1 );    
        return $extension;
    }
    function mime_by_filename( $filename  ) {
        $ext = getextension( $filename );
        $ext = strtolower( $ext );
        return mime_by_extension( $ext );
    }
    function mime_by_extension( $ext ) {
        $mimetypes = Array( 
            "jpg" => "image/jpeg" , 
            "png" => "image/png" , 
            "bmp" => "image/bmp" ,
            "gif" => "image/gif" ,
            "tiff" =>"image/tiff" ,
            "tif" =>"image/tiff" ,
            "ico" =>"image/x-icon" , 
            "jpe" =>"image/jpeg" ,
            "pjpeg" =>"image/jpeg",
            "jpeg" =>"image/jpeg" ,
            "rgb" =>"image/x-rgb" ,
            "mp3" =>"audio/mpeg" ,
            "m3u" =>"audio/x-mpegurl" ,
            "wav" =>"audio/x-wav" ,
            "ogg" =>"application/ogg" ,
            "css" =>"text/css" , 
            "html" =>"text/html" , 
            "htm" =>"text/html" ,
            "stm" =>"text/html" ,
            "txt" =>"text/plain" ,
            "rtx" =>"text/richtext" , 
            "mp2" =>"video/mpeg" ,
            "mpa" =>"video/mpeg" , 
            "mpe" =>"video/mpeg" , 
            "mpeg" =>"video/mpeg" ,
            "mpg" =>"video/mpeg" , 
            "mpv2" =>"video/mpeg" ,
            "mov" =>"video/quicktime" , 
            "qt" =>"video/quicktime" , 
            "asf" =>"video/x-ms-asf" ,
            "asr" =>"video/x-ms-asf" , 
            "asx" =>"video/x-ms-asf" ,
            "avi" =>"video/x-ms-asf" ,
            "movie" =>"video/x-sgi-movie " ,
            "mht" =>"message/rfc822" ,
            "mhtml" =>"message/rfc822" ,
            "doc" =>"application/msword" ,
            "dot" =>"application/msword" ,
            "bin" =>"application/octet-stream" ,
            "class" =>"application/octet-stream" ,
            "dms" =>"application/octet-stream " ,
            "exe" =>"application/octet-stream " ,
            "pdf" =>"application/pdf" ,
            "dir" =>"application/x-director" ,
            "dvi" =>"application/x-dvi " ,
            "js" =>"application/x-javascript" ,
            "wmf" =>"application/x-msmetafile" ,
            "zip" =>"application/zip" ,
            "rar" =>"application/x-rar-compressed" 
        );    
        
        if ( !$mimetypes[ $ext ] ) {
            return false;
        }
        else {
            return $mimetypes[ $ext ];
        }    
    }
    
    function mime_type( $filepath ) {
        if( !file_exists( $filepath ) )
            return false;
        dl( 'fileinfo.so' );
        $finfo = New finfo( FILEINFO_MIME );
        return $finfo->file( $filepath );
    }
?>