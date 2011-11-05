<?php
    /* 
    Module: Documentation
    File: /modules/documentation/documentation.php
    Developer: feedWARd
    */
    include "modules/module.php";

    class DocFolder {
        private $id; // use $mId etc. here
        private $foldid;
        private $fkey;
        private $name;
        private $pointfold;
        private $pointdoc;
        private $resultfold;
        private $resultdoc;
        private $errtext;
        private $foldexists;
        private $docexists;
        
        public function DocFolder( $key = "default" ) {
            global $docfolders;
            
            if ( ContainsSingleQuotes($key) ) {
                bc_die("Key value may not contain single or double quotes");
            }
            if ( $key == "default" ) {
                $this->id = 0;
                $this->foldid = 0;
                $this->fkey = $key;
                $this->name = "Parent Folder";
            }
            else {
                $FolderResult = bcsql_query("SELECT * FROM `$docfolders` WHERE `dfolder_key` = '$key' LIMIT 1");
                $CheckIfExists = bcsql_num_rows($FolderResult);
                if ($CheckIfExists) {
                    $FolderAttr = bcsql_fetch_array($FolderResult);
                    $this->id = $FolderAttr["dfolder_id"];
                    $this->foldid = $FolderAttr["dfolder_parid"];
                    $this->fkey = $FolderAttr["dfolder_key"];
                    $this->name = $FolderAttr["dfolder_name"];
                }
                else {
                    $this->errtext = "Folder `$key` does not exist";
                    return false;
                }
            }
            $this->pointfold = 0;
            $this->pointdoc = 0;
            return true;
        }
        
        public function AddFolder( $key , $name ) {
            global $docfolders;
            
            $name = bcsql_escape($name);
            if ( $key == "default" ) {
                bc_die("Key value cannot be set to \"default\"");
            }
            else if ( ContainsSingleQuotes($key) ) {
                bc_die("Key value may not contain single or double quotes");
            }
            $query = "SELECT * FROM `$docfolders` WHERE `dfolder_key`='$key' LIMIT 1";
            $NewFoldResult = bcsql_query($query);
            $CheckIfUnique = bcsql_num_rows($NewFoldResult);
            if ( $CheckIfUnique >= 1 ) {
                $this->errtext = "DocFolder `$key` already exists";
                return false;
            }
            bcsql_query("INSERT INTO `$docfolders` (`dfolder_parid`,`dfolder_key`,`dfolder_name`) VALUES ('$this->id','$key','$name')");
            return true;
        }
        
        public function GetFolder() {
            global $docfolders;
            
            if ( empty( $this->resultfold ) ) {
                $this->resultfold = bcsql_query("SELECT * FROM `$docfolders` WHERE `dfolder_parid` = '$this->id' ORDER BY `dfolder_name` ASC");
                $this->foldexists = bcsql_num_rows($this->resultfold);
            }
            if ( $this->pointfold < $this->foldexists ) {
                $CurFolderAttr = bcsql_fetch_array( $this->resultfold );
                $this->pointfold++;
                return $CurFolderAttr[ "dfolder_key" ];
            }
            else {
                $this->errtext = "All folders have been returned";
                return false;
            }
        }

        public function RenameFolder( $newname ) {
            global $docfolders;
            
            $newname = bcsql_escape( $newname );
            bcsql_query( "
                        UPDATE 
                            `$docfolders` 
                        SET 
                            `dfolder_name` = '$newname' 
                        WHERE 
                            `dfolder_id` = '$this->id'" );
            $this->name = $newname;
        }
        
        public function FolderName() {
            return $this->name;
        }
        
        public function FolderID() {
            return $this->id;
        }
        
        public function ParentID() {
            return $this->foldid;
        }

        public function FolderKey() {
            return $this->fkey;
        }
        
        public function GetErrorText( $clear = true ) {
            $a = $this->errtext;
            if ( $clear == true ) { $this->errtext = ""; }
            return $a;
        }
        
        public function AddDocumentationItem($key,$title,$content) {
            global $docitems;
            
            if ( ContainsSingleQuotes( $key ) ) {
                bc_die("Key value may not contain single or double quotes");
            }
            $title = bcsql_escape( $title );
            $content = bcsql_escape( $content );
            $query = "SELECT * FROM `$docitems` WHERE `ditem_key`='$key' LIMIT 1";
            $NewDocResult = bcsql_query( $query );
            $CheckIfUnique = bcsql_num_rows( $NewDocResult );
            if ( $CheckIfUnique >= 1 ) {
                $this->errtext = "DocItem `$key` already exists";
                return false;
            }
            bcsql_query( "
                        INSERT INTO 
                            `$docitems` 
                            ( `ditem_parid`,`ditem_key`,`ditem_title`,`ditem_content` ) 
                            VALUES ('" . $this->id . "','$key','$title','$content')" );
            return true;
        }
        
        public function GetDocumentationItem() {
            global $docitems;
            
            if ( empty( $this->resultdoc ) ) {
                $this->resultdoc = bcsql_query( "
                    SELECT 
                        * 
                    FROM 
                        `$docitems` 
                    WHERE 
                        `ditem_parid` = '" . $this->id . "' 
                    ORDER BY 
                        `ditem_title` ASC" );
                $this->docexists = bcsql_num_rows( $this->resultdoc );
            }
            if ( $this->pointdoc < $this->docexists ) {
                $CurDocAttr = bcsql_fetch_array( $this->resultdoc );
                $this->pointdoc++;
                return $CurDocAttr[ "ditem_key" ];
            }
            else {
                return false;
            }
        }
        
        function CountFolders() {
            global $docfolders;
            
            $result = bcsql_query( "SELECT 
                                        COUNT(*) AS fcount 
                                    FROM
                                        `$docfolders` 
                                    WHERE 
                                        `dfolder_parid` = '" . $this->id . "'" );
            $farray = bcsql_fetch_array($result);
            return $farray[ "fcount" ];
        }
        
        function RemoveFolder() {
            global $docitems;
            global $docfolders;
            
            $queue = array( $this->fkey );
            while ( count( $queue ) ) {
                $tkey = $queue[ count( $queue ) - 1 ];
                $a = &New DocFolder( $tkey );
                bcsql_query( "
                                DELETE FROM
                                    `$docitems` 
                                WHERE 
                                    `ditem_parid` = '" . $this->id . "'"
                            );
                while ( $b = $a->GetFolder() ) {
                    array_push( $queue , $b );
                }
                if ( $a->CountFolders() == 0 ) {
                    bcsql_query( "
                                    DELETE FROM 
                                        `$docfolders` 
                                    WHERE 
                                        `dfolder_key` = '$tkey' 
                                    LIMIT 1");        
                    array_pop( $queue );
                }
            }
        }
    }
    
    class DocumentationItem {
        private $id; // use $mId etc. here
        private $foldid;
        private $dkey;
        private $title;
        private $content;
        private $errtext;
        
        public function DocumentationItem( $key ) {
            global $docitems;
            
            if ( ContainsSingleQuotes( $key ) ) {
                bc_die( "Key value may not contain single or double quotes" );
            }
            $DocResult = bcsql_query( "
                SELECT 
                    * 
                FROM 
                    `$docitems` 
                WHERE 
                    `ditem_key` = '$key' 
                LIMIT 1");
            $CheckIfExists = bcsql_num_rows( $DocResult );
            if ( $CheckIfExists ) {
                $DocAttr = bcsql_fetch_array( $DocResult );
                $this->id = $DocAttr[ "ditem_id" ];
                $this->foldid = $DocAttr[ "ditem_parid" ];
                $this->dkey = $DocAttr[ "ditem_key" ];
                $this->title = $DocAttr[ "ditem_title" ];
                $this->content = $DocAttr[ "ditem_content" ];
            }
            else {
                bc_die( "DocItem '$key' does not exist" );
            }
        }
        
        public function MoveToFolder( $targetkey ) {
            global $docfolders;
            global $docitems;
            
            if ( ContainsSingleQuotes( $targetkey ) ) {
                bc_die( "Key value may not contain single or double quotes" );
            }
            $FolderResult = bcsql_query( "
                SELECT 
                    * 
                FROM 
                    `$docfolders` 
                WHERE 
                    `dfolder_key` = '$targetkey' 
                LIMIT 1" );
            $FolderExists = bcsql_num_rows( $FolderResult );
            if ( $FolderExists ) {
                $FolderFetched = bcsql_fetch_array( $FolderResult );
                $FolderID = $FolderFetched[ "dfolder_id" ];
                bcsql_query( "
                    UPDATE 
                        `$docitems` 
                    SET 
                        `ditem_parid` = '$FolderID' 
                    WHERE 
                        `ditem_id` = '" . $this->id . "'
                    LIMIT 1");
                $this->foldid = $FolderID;
                return true;
            }
            bc_die( "Target folder '$targetkey' does not exist" );
        }
        
        public function EditDocumentationItem( $newtitle , $newcontent ) {
            global $docitems;
            
            $newtitle = bcsql_escape( $newtitle );
            $newcontent = bcsql_escape( $newcontent );
            bcsql_query( "
                UPDATE 
                    `$docitems` 
                SET 
                    `ditem_title` = '$newtitle', 
                    `ditem_content` = '$newcontent' 
                WHERE 
                    `ditem_id` = '" . $this->id . "' 
                LIMIT 1" );
            $this->title = $newtitle;
            $this->content = $newcontent;
        }
        
        public function RemoveDocumentationItem() {
            global $docitems;
            
            bcsql_query( "
                DELETE FROM 
                    `$docitems` 
                WHERE 
                    `ditem_id` = '" . $this->id . "'
                LIMIT 1");
            $this->title = "";
            $this->content = "";
            $this->id = -1;
            $this->foldid = -1;
            $this->dkey = -1;
        }
        
        function DocTitle() {
            return $this->title;
        }
        
        function DocContent() {
            return $this->content;
        }
        
        function GetErrorText($clear = true) {
            $a = $this->errtext;
            if ( $clear ) { 
                $this->errtext = "";
            }
            return $a;
        }
    }
?>