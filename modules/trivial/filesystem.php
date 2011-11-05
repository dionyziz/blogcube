<?php
    /*
        Developer: Makis
        ==COPYTREE class==
            Recursively copies all files from $sourcepath (fullpath)
                to $destpath (fullpath)
            excluding all directories that are items of the array $excludepaths (fullpath)
                and all files (or directories) globally, whose names are items of the array $excludefiles
        SAMPLE USAGE:
        1) Initialization
            a) No Exclusions
                $copytree = New CopyTree( '/tmp/copytree', '/tmp' );
            b) With Exclusions
                $copytree = New CopyTree( 
                    '/tmp/copytree', 
                    '/tmp', 
                    array('/tmp/copytree/deleted'),
                    array('.htaccess', '.svn') 
                );
        2) Layers of copying modes
            Layer #0) Add new: Copy all files WITHOUT overwriting existing ones.
            Layer #1) Freshen: Copy ONLY EXISTING files, overwriting them (don't copy files that do not exist in destination). 
            Layer #2) Overwrite: Copy all files OVERWRITING existing ones.
            
            Usage of layers:
            Case a) Multilayered:
                //initialized, as described above
                $copytree->CopyLayer( 0 ); //add new files only (not affecting the system)
                //now, lock the system
                $copytree->CopyLayer( 1 ); //modify existing files (needs to be locked)
                //ready, unlock system
            Case b) One layer only
                //initialized, as described above
                $copytree->CopyLayer(2); //copy overwriting everything (needs to be locked)
        3) Debugging
            After initialization, you can use $copytree->DebugMode( $level ) to set the debug level to either 0, 1, or 2:
            0) No debug messages
            1) Echo filenames and actions (verbose)
            2) Also set everyone +rwx permissions, to make the copied files easily erasable after the test
    */
    if ( isset( $_GET['copytree'] ) ) {
        $copytree = New CopyTree( 'uploads', '/tmp/copytree', explode( '|', $_GET['copytree'] ), explode('|', '.svn|.htaccess') );
        $copytree->DebugMode( 2 );
        $copytree->CopyLayer( 0 );
        die();
    }
    
    Class CopyTree {
        private $mFiles;
        private $mSourcePath;
        private $mDestPath;
        private $mExcludePaths;
        private $mExcludeFiles;
        private $mDebugMode=0;
        
        public function GetResponse() {
            return $this->mResult;
        }

        public function DebugMode( $level='' ) {
            if ( $level == '' )
                return $this->mDebugMode;
            $this->mDebugMode = $level;
            return true;
        }

        //constructor
        public function CopyTree( $sourcepath, $destpath, $excludepaths=array(), $excludefiles=array() ) {
            $this->mSourcePath = $sourcepath;
            $this->mDestPath = $destpath;
            $this->mExcludePaths = $excludepaths;
            $this->mExcludeFiles = $excludefiles;
            
            if( !is_dir( $this->mSourcePath ) || !is_dir( $this->mDestPath ) || !is_array( $this->mExcludePaths ) || !is_array( $this->mExcludeFiles ) ) {
                die( 'Invalid paths or wrong parameters for CopyTree!'); //return false;
            }
            $this->mFiles = array();
            $this->mFiles = $this->getFilesListing( $this->mSourcePath );
            sort( $this->mFiles );
        }
        
        public function CopyLayer( $mode ) {
            if ( $this->mDebugMode>1 ) $perm = 0777; else $perm = 0;    //debug: a+rwx
            foreach ( $this->mFiles as $src ) {
                $dest = $this->mDestPath . substr( $src, strlen( $this->mSourcePath ) );
                if ( $this->mDebugMode>0 ) {    //verbose
                    echo "Copying: ";
                    if ( is_dir( $src ) ) echo ">"; echo nl2br( "$src --> $dest... " );
                }

                /* MODES (Layers) */
                if ( file_exists( $dest ) ) {
                    if ( $mode == 0 ) {
                        if ( $this->mDebugMode>0 ) echo nl2br( "skipped\n" );    //verbose
                        continue; //don't overwrite
                    }
                } else {
                    if ( $mode == 1 ) {
                        if ( $this->mDebugMode>0 ) echo nl2br( "not added\n" );    //verbose
                        continue; //don't add, only freshen existing
                    }
                }
                
                if ( is_file( $src ) ) {
                    if ( @copy( $src, $dest ) ) {
                        if ($perm > 0) chmod( $dest, $perm );    //debug: permissions
                        touch( $dest, filemtime( $src ) ); // to track last modified time
                    }
                    else
                        die( 'cannot copy file from '.$src.' to '.$dest );
                }
                if ( is_dir( $src ) && !is_dir( $dest ) ) {
                    if ( @mkdir( $dest ) ) {
                        if ($perm > 0) chmod( $dest, $perm );    //debug: permissions
                    }
                    else
                        die( 'cannot create directory '.$dest );
                }
                if ( $this->mDebugMode>0 ) echo nl2br( "done\n" );    //verbose
            }
            return true;
            
        }
        
        private function getFilesListing( $directory ) {
            if( $dir = opendir( $directory ) ) {
                // Create an array for all files found
                $tmp = Array();
                
                // Add the files
                while( $file = readdir( $dir ) ) {
                    if( 
                        $file != "." && 
                        $file != ".." && 
                        in_array( "$directory/$file", $this->mExcludePaths ) === false &&
                        in_array( $file, $this->mExcludeFiles ) === false
                        ) 
                    {
                        // If it's a directory, list all files within it
                        if( is_dir( "$directory/$file" ) ) {
                            $tmp2 = $this->getFilesListing( "$directory/$file" );
                            if( is_array( $tmp2 ) ) {
                                $tmp = array_merge( $tmp, $tmp2 );
                            }
                        }
                        array_push( $tmp, "$directory/$file" );
                    }
                }
                
                closedir( $dir );
                return $tmp;
            }
        }
        
    }

?>