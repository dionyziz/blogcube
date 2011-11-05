<?php
    /*
    Module: Database
    File: /modules/database/database.php
    Developers: dionyziz, feedWARd
    */
    include "modules/module.php";
    include "modules/database/prepared.php";
    
    final class BCDatabase { 
        private $mUsername;
        private $mPassword;
        private $mDatabasename;
        private $mHost;
        private $mPort;
        private $mConnection;
        private $mSQL;
        private $mError;
        private $mConnected;

        public function Error() {
            return $this->mError;
        }
        public function BCDatabase( $host , $username , $password , $database , $port = 3306 ) {
            if (empty($host) || empty($username) || empty($password) || empty($database)) {
                die('Could not create a database instance');
            }
            $this->mHost = $host;
            $this->mPort = $port;
            $this->mUsername = $username;
            $this->mPassword = $password;
            $this->mDatabasename = $database;
            $this->mConnected = false;
        }
        public function Connect() {
            $this->mConnection = @mysql_connect( $this->mHost . ":" . $this->mPort , $this->mUsername , $this->mPassword , true );
            if ( !$this->mConnection ) {
                $this->mPassword = ''; // security
                $this->mError = 'Connection failed: Check access codes';
                $this->mConnected = false;
                return false;
            }
            $this->mPassword = '';
            $this->mSQL = @mysql_select_db( $this->mDatabasename , $this->mConnection );
            if( !$this->mSQL ) {
                $this->mError = 'SelectDb failed: Check database name';
                $this->mConnected = false;
                return false;
            }
            $this->mConnected = true;
            return true;
        }
        public function Query( $sql_query ) {
            global $debug_version;
            
            if ( !$this->mConnected ) {
                bc_die( "The following SQL query could not be executed because no connection is present to the server. Use ->Connect() to connect.<br />" . CodeHighlight( $sql_query , 'SQL' ) );
            }
            if ( substr( $sql_query , strlen( $sql_query ) - 1 , 1 ) == ";" ) {
                // following the suggestion of php coders not to use a semicolon at the end of an SQL query
                $sql_query = substr( $sql_query , 0 , strlen( $sql_query ) - 1 );
            }
            /*
            $debug_q = substr( $sql_query , 0 , 20 );
            $quid = rand( 0 , 1234567890 );
            $debug = '<a href="" onclick="Debug.SQLShow(\'';
            $debug .= $quid;
            $debug .= '\');return false;">';
            $debug .= $debug_q;
            $debug .= ' &gt;&gt;</a>';
            $debug .= '<div id="sql_';
            $debug .= $quid;
            $debug .= '_real" style="display:none">';
            $debug .= CodeHighlight( $sql_query , 'SQL' );
            $debug .= '</div>';
            debug_notice( $debug );
            */
            $sqlr = mysql_query( $sql_query , $this->mConnection );
            if( is_bool( $sqlr ) ) {
                // either error or database write success
                if ( $sqlr === true ) {
                    // database write success
                    return New BCDbChange( $this );
                }
                else {
                    // returned literal false, database error or query error
                    if( $debug_version ) {
                        $sql_query = preg_replace( "#(^\s+)|(\n(\s+))#" , "" , $sql_query );
                        $sql_error = mysql_error();
                        $sqlsyntaxerror = "You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near '";
                        if( strpos( $sql_error , $sqlsyntaxerror ) !== false ) {
                            // syntax error
                            $atline = "' at line ";
                            $linepos = strrpos( $sql_error , $atline ) + strlen( $atline );
                            $linenum = substr( $sql_error , $linepos );
                            $highlightlines = Array( $linenum );
                            $sql_error = substr_replace( $sql_error , "<b>$linenum</b>" , $linepos );
                            $endoferrorsql = strrpos( $sql_error , "'" );
                            $errorsqlpos = strlen( $sqlsyntaxerror );
                            $errorsqlmsg = substr( $sql_error , $errorsqlpos , $endoferrorsql - $errorsqlpos );
                            $sql_error = substr_replace( $sql_error , "<b>$errorsqlmsg</b>" , $errorsqlpos , $endoferrorsql - $errorsqlpos );
                            $sql_error = substr_replace( $sql_error , "MySQL syntax error near '" , 0 , strlen( $sqlsyntaxerror ) );
                        }
                        else {
                            $highlightlines = Array();
                        }
                        include "elements/sqlerror.php";
                        // don't we need to cleanup here?
                        exit();
                    }
                    else {
                        bc_die( "Internal BlogCube Error" );
                    }
                }
            }
            else {
                // mysql resource
                return New BCDbResource( $sqlr , $sql_query );
            }
        }
        public function AffectedRows() {
            return mysql_affected_rows( $this->mConnection );
        }
        public function InsertId() {
            return mysql_insert_id( $this->mConnection );
        }
        public function EnumList( $table , $column ) {
            $sql = "SHOW
                    COLUMNS
                FROM
                    $table
                LIKE
                    '$column';";
            $sqlr = $this->Query( $sql );
            
            $sqlarray = $sqlr->FetchArray();
            $EnumValues = $sqlarray[ "Type" ];
            
            $EnumValues = substr( $EnumValues , 6 , strlen( $EnumValues ) - 8 );
            $EnumValues = str_replace( "','" , "," , $EnumValues );
            
            return explode( "," , $EnumValues);
        }
        public function ListColumns( $table ) {
            return $this->Query("SHOW COLUMNS FROM `$table`");
        }
        public function ListTables() {
            return $this->Query("SHOW TABLES FROM `" . $this->mDatabasename . "`");
        }
        public function GetTables() {
            $alltables = Array();
            $tablelist = $this->ListTables();
            while ($ctable = $tablelist->FetchArray()) {
                $alltables[] = New BCDbTable($ctable[0],$this);
            }
            return $alltables;
        }
        public function Prepare( $sql ) {
            return New BCPreparedQuery( $sql , $this );
        }
    }
    
    final class BCDbResource {
        private $mResource;
        private $mQuery;

        public function BCDbResource( $sqlresource , $query = '' ) {
            $this->mResource = $sqlresource;
            $this->mQuery = $query;
        }
        public function FetchArray() {
            return mysql_fetch_array( $this->mResource );
        }
        public function FetchRow() {
            return mysql_fetch_row( $this->mResource );
        }
        public function NumRows() {
            $numrows = @mysql_num_rows( $this->mResource );
            if ($numrows === false) {
                bc_die( 'Failed to NumRows() on resource returned by SQL Query:<br /><br />' . $this->mQuery );
            }
            return $numrows;
        }
    }
    
    final class BCDbChange {
        private $mQuery;
        private $mDatabase;
        private $mInsertId;
        
        public function BCDbChange( $database ) {
            $this->mDatabase = $database;
            $this->mAffectedRows = $database->AffectedRows();
            $this->mInsertId = $database->InsertId();
        }
        public function AffectedRows() {
            return $this->mAffectedRows;
        }
        public function InsertId() {
            return $this->mInsertId;
        }
    }

    function bcsql_escape( $sql_query ) {
        return mysql_escape_string( $sql_query );
    }
    function bcsql_query( $sql_query ) { // alias
        global $db_main;

        if (!isset($db_main)) {
            bc_die('No database connection established: <br /><br />' . $sql_query);
        }
        return $db_main->Query( $sql_query );
    }
    function bcsql_fetch_array( $bc_db_resource ) { // alias
        return $bc_db_resource->FetchArray();
    }
    function bcsql_num_rows( $bc_db_resource ) { // alias
        return $bc_db_resource->NumRows();
    }
    function bcsql_affected_rows() { // alias
        global $db_main;
        
        return $db_main->AffectedRows();
    }
    function bcsql_insert_id() { // alias
        global $db_main;
        
        return $db_main->InsertId();
    }

    /*
        try to connect to primary database
        this is the main database used at the server
        blogCube should be able to connect to it
        access codes should already have been set
    */
    if( !$readonly ) {
        // Primary DB
        // establish connection to mysql database
        if ( $debug_version ) {
            $accesscodes = $db_access['unstable']; // unstable database
        }
        else {
            $accesscodes = $db_access['stable']; // stable database
        }
        $db = $db_main = New BCDatabase( 'localhost' , $accesscodes['username'] , $accesscodes['password'] , $accesscodes['db'] );
        if ( !$db_main ) {
            bc_die( 'Failed to create database instance' );
        }
        if ( !$db_main->Connect() ) {
            bc_die( 'The BlogCube database could not be accessed' );
        }
        $db_main->Query( 'SELECT COUNT(*) FROM `users`');
        include "modules/database/tables.php";
    }
    else {
        $rocode = 1;
    }

    class BCDbTable {
        private $mTableName;
        private $mColumnList;
        private $mFields;
        private $mColumnsFetched;
        private $mColumnPtr;
        private $mColumnsCnt;
        private $mDb;

        public function BCDbTable( $tablename , $db = 0 ) {
            global $db_main;
            
            if ( $db === 0 ) {
                $db = $db_main;
            }
            $this->mDb = $db;
            if ( $tablename == '' ) {
                bc_die( 'Empty table name passed to BCDbTable constructor' );
            }
            $this->mTableName = $tablename;
            $this->mColumnsCnt = -1;
            $this->mColumnPtr = 0;
            $this->mColumnsFetched = false;
        }
        public function GetField() {
            if( $this->mColumnsCnt == -1 ) {
                $this->mColumnList = $this->mDb->ListColumns( $this->mTableName ); 
                $this->mColumnsCnt = $this->mColumnList->NumRows();
            }
            $nowptr = $this->mColumnPtr;
            $this->mColumnPtr++;
            if( !$this->mColumnsFetched ) {
                $curcol = $this->mColumnList->FetchRow();
                if( $curcol !== false ) {
                    $this->mFields[ $nowptr ] = New BcDbField( $curcol );
                }
                else {
                    $this->mColumnsFetched = true;
                }
            }
            if( $this->mColumnPtr > $this->mColumnsCnt ) {
                $this->mColumnPtr = 0;
                return false;
            }
            return $this->mFields[ $nowptr ];
        }
        
        public function Name() {
            return $this->mTableName;
        }
    }

    function BuildCREATEQuery( /* BCDbTable */ $table ) {
        $sql = 'CREATE TABLE `';
        $sql .= $table->Name();
        $sql .= '` (';
        while( $field = $table->GetField() ) {
            $sql .= '`';
            $sql .= $field->Name();
            $sql .= '` ';
            switch( $field->Type() ) {
                case 'int(11)':
                    $sql .= "int(11) NOT NULL default '0'";
                    break;
                case 'text':
                    $sql .= "text NOT NULL";
                    break;
                case 'datetime':
                    $sql .= "datetime NOT NULL default '0000-00-00 00:00:00'";
                    break;
                case '':
                    $sql .= 'ENUM(';
                    while( $enum = $field->Enums() ) {
                        $sql .= "'";
                        
                        $sql .= "'";
                    }
                    $sql .= ')';
                    break;
                default:
                    return false;
            }
        }
        $sql .= ');';
        return $sql;
    }
    function BuildDROPQuery( /* BCDbTable */ $table ) {
        $sql = 'DROP TABLE `' . $table->Name() . '`';
        return $sql;
    }
    function BuildALTERQuery( /* BCDbTable */ $old , /* BCDbTable */ $new ) {
    }

    class BCDbField {
        private $mName;
        private $mType;
        private $mNull;
        private $mKey;
        private $mDefault;
        private $mExtra;
        private $mEnum;
        private $mEnumPos;
        private $mEnumAll;
        
        public function GetAllEnums() {
            return $this->mEnumAll;
        }
        
        public function BCDbField( $fcolumn ) {
            $this->mName = $fcolumn[ 0 ];
            $this->mType = $fcolumn[ 1 ];
            $this->mNull = $fcolumn[ 2 ];
            $this->mKey = $fcolumn[ 3 ];
            $this->mDefault = $fcolumn[ 4 ];
            $this->mExtra = $fcolumn[ 5 ];
            if ( substr($this->mType,0,4) == "enum" ) {
                $this->mEnumAll = substr($this->mType,5,-1);
                $this->mEnum = explode(",",substr(str_replace("'","",$this->mType),5,-1));
                $this->mType = "ENUM";
                $this->mEnumPos = -1;
            }
        }
        public function Name() {
            return $this->mName;
        }
        public function Type() {
            return $this->mType;
        }
        public function IsNull() {
            return $this->mNull;
        }
        public function GetKey() {
            return $this->mKey;
        }
        public function DefaultValue() {
            return $this->mDefault;
        }
        public function Extra() {
            return $this->mExtra;
        }
        public function GetEnum() {
            if ( $this->mEnumPos == count($this->mEnum)-1) { $this->mEnumPos = -1; return false; }
            $this->mEnumPos++;
            return $this->mEnum[$this->mEnumPos];
        }
    }
?>
