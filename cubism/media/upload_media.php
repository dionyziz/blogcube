<?php 
    $file_numbers = count( $_FILES );
    // get the keys in the $_FILES array (i.e. <input type="file"> NAMES, and what we'd write $_FILES[ here ])
    // and store them in a new array, named $userfiles
    $userfiles = array_keys( $_FILES );

    $m1 = $totalsize = 0;
    
    ?><html><head>
<link href="style.css.php" type="text/css" rel="stylesheet" />
</head><body>
<?php
    for ( $i = 0; $i < $file_numbers; $i++ ) {
        $thisfile = $_FILES[ $userfiles[ $i ] ];
        if ( $thisfile ) {
            $m1++;    
        }
        $totalsize += $thisfile[ "size" ];
        if ( !is_uploaded_file( $thisfile[ 'tmp_name' ] ) ) {
            echo "Possible deception trial<br />";
            exit;
        }
        if ( $thisfile[ 'size' ] > 16*1024*1024 ) {
            echo "Maximum size of a file is 16MB";
            exit;
        }
    }
    if ( !$m1 ) {
        ?>No file was uploaded<br /><?php
        exit;
    }
    if ( !$totalsize ) {
        ?>All files have zero size<br /><?php
        exit;
    }
    $thismediaid = Array();
    for ( $i = 0; $i < $file_numbers; $i++ ) {
        // get the $i-th file from the $_FILES array
        $thisfile = $_FILES[ $userfiles[ $i ] ];
        // get the temporary filename, where we will be reading from
        $tempname = $thisfile[ 'tmp_name' ];
        // get the original filename
        $realname = $thisfile[ 'name' ];
        // open the file and read its contents into the $contents variable
        
        //don't actually need this part below
        $fp = fopen( $tempname , "r" );
        $contents = fread( $fp , filesize( $tempname ) );
        fclose( $fp );
        $contents = strip_tags( $contents );
        
        // submit the media to the database and save it to its permanent location
        $thismediaid[ $i ] = submit_media( $realname , $tempname );
        // instanciate an object of the media class for the last submitted media
    }
    function getfilename_noext( $realname ) {
        $dotposition = strrpos( $realname , ".");
        $filename = substr( $realname , 0 , $dotposition );    
        return $filename;
    }
        ?>
        <table style="font-size:95%">
            <tr>
                <td>
                    <table>
                        <tr>
                            <td>Total files uploaded:</td>
                            <td><b><?php echo $file_numbers; ?></b></td>
                        </tr>
                        <tr>
                            <td>Total size:</td>
                            <td><b><?php echo $totalsize; ?></b> bytes</td>
                        </tr>
                    </table>
                    <br />
                </td>
            </tr>
        <?php 
            for ( $i = 0; $i < $file_numbers; $i++ ) {
                $thisfile = $_FILES[ $userfiles[ $i ] ];
                $medclass = New media( $thismediaid[ $i ] );
                // retrieve data and display it for debugging
                $medfilename = $medclass->filename();
                $medmime = $medclass->mimetype();
                $medsimpletext = $medclass->simpletext();
                $medactive = $medclass->active();
                $medextens = $medclass->extension();        
        ?>
            <tr>
                <td>
                    <table class="formtable">
                        <tr>
                            <td class="ffield">
                                <b>Filename:</b>
                            </td>
                            <td class="ffield">
                                <?php echo RemoveExtension( $medfilename ); ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="nfield">
                                <b>Extension:</b>
                            </td>
                            <td class="nfield">
                                <?php echo $medextens; ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="nfield">
                                <b>Mimetype:</b>
                            </td>
                            <td class="nfield">
                                <?php echo $medmime; ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="nfield">
                                <b>Size: </b>
                            </td>
                            <td class="nfield">
                                <?php echo $thisfile[ 'size' ]; ?> bytes
                                
                            </td>
                            <td>
                                <br /><br />
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        <?php
            }
        ?>
        </table>
</body>
</html>