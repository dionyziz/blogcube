<?php
    /*
    Module: Habitats/Map
    File: /modules/habitats/map.php
    Developer: Dionyziz
    */

    function AllHabitats() {
        global $habitats;
        global $habitatsblogs;
        
        $sql = "SELECT
                    hab_id, hab_name, hab_description, hab_x, hab_y,
                    COUNT(*) AS hab_size
                FROM
                    $habitats,
                    $habitatsblogs
                WHERE
                    hb_habid = hab_id
                GROUP BY
                    hb_habid
                ORDER BY
                    hab_size DESC";
            
        $habitats = array();
        $res = bcsql_query($sql);
        while ( $row = $res->FetchArray() ) {
            $habitats[] = New Habitat( $row , false ); // no queries in here
        }
        
        return $habitats;
    }

    function HabitatsMap() {
        $allhabitats = AllHabitats(); // one SQL query only
        
        $spec = array( 0 => array( 'pipe' , 'r' ), // Python stdin <-- PHP stdout
                       1 => array( 'pipe' , 'w' ), // Python stdout --> PHP stdin
                       2 => array( 'pipe' , 'w' ) ); // Python stderr --> PHP stdin
        $ph = popen( 'maps.py' , $spec , $pipes );
        if ( !is_resource( $ph ) ) {
            return false; // failed to run python program
        }

        fwrite( $pipes[ 0 ] , "0,0,1,1\n" 
                              . count( $allhabitats )
                              . "\n" );
        
        foreach ( $allhabitats as $habitat ) {
            fwrite( implode( ',' , array( $habitat->X() , 
                                          $habitat->Y() ,
                                          $habitat->Size() ,
                                          '0.0.ff' ) )
                    . "\n" );
        }
        
        fclose( $pipes[ 0 ] );
        
        // read output from Python's stdout
        $data = '';
        while ( !feof( $pipes[ 1 ] ) ) {
            $data .= fgets( $pipes[ 1 ] , 1024 );
        }
        fclose( $pipes[ 1 ] );
        
        // read potential errors from Python's stderr
        $errors = '';
        while ( !feof( $pipes[ 2 ] ) ) {
            $errors .= fgets( $pipes[ 2 ] , 1024 );
        }
        fclose( $pipes[ 2 ] );
        
        $ret = proc_close( $ph );
        
        if ( $errors != '' ) {
            // an error occurred
            return false;
        }
        
        if ( $ret != 0 ) {
            // the program didn't exit with code 0
            return false;
        }
        
        return $data;
    }


    ?>
