<?php
    /*
    Module: Database stabilization
    File: /modules/stabilize/database.php
    Developers: dionyziz, feedWARd
    */
    
    function CompareFields($field1,$field2) {
        if ( $field1->Name() != $field2->Name() ) return false;
        if ( $field1->Type() != $field2->Type() ) return false;
        if ( $field1->IsNull() != $field2->IsNull() ) return false;
        if ( $field1->GetKey() != $field2->GetKey() ) return false;
        if ( $field1->DefaultValue() != $field2->DefaultValue() ) return false;
        if ( $field1->Extra() != $field2->Extra() ) return false;
        if ( $field1->Type() == "ENUM" ) {
            if ($field1->GetAllEnums() != $field2->GetAllEnums()) return false;
        }
        return true;
    }
    
    function DescribeField($curfield,$showprkey = false,$prinquery = false) {
        $curquery = "    `" . $curfield->Name() . "`";
        $isprimary = false;
        $curquery .= " " . $curfield->Type();
        if ( $curfield->Type() == "ENUM" ) {
            $curquery .= "(";
            while(($curenum = $curfield->GetEnum()) !== false) {
                $curquery .= "'" . $curenum . "',";
            }
            $curquery = substr($curquery,0,-1);
            $curquery .= ")";
        }
        if ( $curfield->IsNull() != "YES" ) $curquery .= " NOT NULL";
        if ( $curfield->DefaultValue() != null ) $curquery .= " default '" . $curfield->DefaultValue() . "'";
        if ( $curfield->Extra() ) $curquery .= " " . $curfield->Extra();
        if ( $curfield->GetKey() == "PRI" ) {
            $isprimary = true;
            if ( $prinquery == true ) $curquery .= " PRIMARY KEY";
        }
        //return values
        if (( $showprkey == true ) && ( $prinquery == false )) {
            return array($curquery,$isprimary);
        }
        else {
            return $curquery;
        }
    }
    
    function CompareTables($table1,$table2) {
        $s = FieldLCS($table1,$table2);
        $i = 0;
        $all = count($s);
        $queries = Array();
        $cur = "";
        $curquery = "";
        while ( $curfield = $table2->GetField() ) {
            if ( $i >= count($s) ) { $queries[] = "ALTER TABLE `" . $table2->Name() . "` DROP `" . $curfield->Name() . "`;"; }
            else if ( CompareFields($curfield,$s[$i]) == true ) {
                $i++;
            }
            else {
                $queries[] = "ALTER TABLE `" . $table2->Name() . "` DROP `" . $curfield->Name() . "`;";
            }
        }
        $i = 0;
        while ( $curfield = $table1->GetField() ) {
            if ( $i >= count($s) ) {
                $curquery = "ALTER TABLE `" . $table2->Name() . "` ADD " . DescribeField($curfield,true,true);
                if ( $cur == "" ) $curquery .= " FIRST;";
                else $curquery .= " AFTER `" . $cur . "`;";
                $queries[] = $curquery;
                $cur = $curfield->Name();
            }
            else if ( CompareFields($curfield,$s[$i]) == true ) {
                $cur = $s[$i]->Name();
                $i++;
            }
            else {
                $curquery = "ALTER TABLE `" . $table2->Name() . "` ADD " . DescribeField($curfield,true,true);
                if ( $cur == "" ) $curquery .= " FIRST;";
                else $curquery .= " AFTER `" . $cur . "`;";
                $queries[] = $curquery;
                $cur = $curfield->Name();
            }
        }
        return $queries;
    }
    
    define('LCS_OK', 0);
    define('LCS_LEFT', 1);
    define('LCS_UP', 2);

    function FieldLCS($table1, $table2) {
        /*
            blame dionyziz
        */
        $x = Array();
        $y = Array();
        while ($curfield = $table1->GetField()) {
            $x[] = $curfield;
        }
        while ($curfield = $table2->GetField()) {
            $y[] = $curfield;
        }
        $m = count($x);
        $n = count($y);
        
        // $c is a two-dimentional array which will store the length of the LCS
        // between the two left-most substrings of $x and $y of length $i and $j
        // so $c[$i][$j] = strlen(LCS(substr($x, 0, $i), substr($y, 0, $j))) if that makes any sense

        // $b is again a two-dimentional array which will store the path we have to
        // follow in order to construct the LCS we are building inside our two strings
        // we are constructing the LCS _backwards_
        // in simple words, LCS_OK means include this character and move backwards in both strings
        // while LCS_LEFT and LCS_UP means move backwards only in one of the two strings and do
        // not any character (since it's not a common character)

        // initialize the arrays, make them two-dimentional
        // in addition, set to zero the length of LCS between zero-length strings (trivially)
        $c = Array();
        $c = array_fill(0, $m + 1, array(0)); // $c[$i][0] = 0; for every $i
        $c[0] = array_fill(0, $n + 1, 0); // $c[0][$j] = 0; for every $j

        // go through the two stings character-by-character to construct the two arrays
        for ($i = 1; $i <= $m; ++$i) {
            for ($j = 1; $j <= $n; ++$j) {
                // if a given character is the same, append it to the LCS
                if (CompareFields($x[$i - 1],$y[$j-1]) == true) {
                    // the new LCS is going to be as long as the previous plus one character
                    $c[$i][$j] = $c[$i - 1][$j - 1] + 1;
                    // this is a character of the LCS
                    $b[$i][$j] = LCS_OK;
                }
                // the character is not the same, we have to compare the lengths of the two possible sub-LCSs
                // and use the greater one (since we want to construct as long a string as possible)
                else if ($c[$i - 1][$j] >= $c[$i][$j - 1]) {
                    // skip one character upwards
                    $c[$i][$j] = $c[$i - 1][$j];
                    $b[$i][$j] = LCS_UP;
                }
                else {
                    // skip one character leftwards
                    $c[$i][$j] = $c[$i][$j - 1];
                    $b[$i][$j] = LCS_LEFT;
                }
            }
        }
        // now all we need is to go through the $b table to make the final string
        $i = $m;
        $j = $n;
        $l = array();
        while ($i != 0 && $j != 0) {
            switch ($b[$i][$j]) {
                case LCS_OK:
                    --$i; // decrementing before using, so we don't need to make it -1 inside the {}
                    $l[] = $x[$i];
                    ++$cnt;
                    //fallthrough
                case LCS_LEFT:
                    --$j;
                    break;
                case LCS_UP:
                    --$i;
                    break;
            }
        }
        $l = array_reverse($l);
        return $l;
    }
    
    function StabilizeDatabase() {
        global $db_access;

        $beta_access = $db_access[ 'unstable_stabilize' ];
        $stable_access = $db_access[ 'stable_stabilize' ];
        $queries = array();
        
        if ( !isset( $beta_access ) || !isset( $stable_access ) ) {
            bc_die( 'Stabilization: Could not read access codes to databases' );
        }

        $beta_database = New BCDatabase(
            'localhost' ,
            $beta_access[ 'username' ] ,
            $beta_access[ 'password' ] ,
            $beta_access[ 'db' ]
        );

        $stable_database = New BCDatabase(
            'localhost' ,
            $stable_access[ 'username' ] ,
            $stable_access[ 'password' ] ,
            $stable_access[ 'db' ]
        );

        if ( !$stable_database->Connect() ) {
            bc_die( 'Stabilization: Connection to Stable failed: ' . $stable_database->Error() );
        }
        if ( !$beta_database->Connect() ) {
            bc_die( 'Stabilization: Connection to Beta failed: ' . $beta_database->Error() );
        }

        $stable_table_list = $stable_database->GetTables();
        $beta_table_list = $beta_database->GetTables();
        
        $stable_table_names = array();
        for($i=0;$i<count($stable_table_list);$i++) {
            $stable_table_names[] = $stable_table_list[$i]->Name();
        }
        $beta_table_names = array();
        for($i=0;$i<count($beta_table_list);$i++) {
            $beta_table_names[] = $beta_table_list[$i]->Name();
        }
        
        //drop tables
        $todrop = array_diff($stable_table_names,$beta_table_names);
        $curquery = "";
        foreach($todrop as $ctablename) {
            if ( $curquery == "" ) $curquery = "DROP TABLE `" . $ctablename . "`";
            else $curquery .= ",`" . $ctablename . "`";
        }
        if ( $curquery != "" ) {
            $curquery .= ";";
            $queries[] = $curquery;
        }
        
        //create tables
        $tocreate = array_diff($beta_table_names,$stable_table_names);
        foreach($tocreate as $ctablename) {
            $curtable = New BCDbTable($ctablename,$beta_database);
            $curquery = "CREATE TABLE `" . $ctablename . "` (";
            $curprkey = "";
            while ($curfield = $curtable->GetField()) {
                $curquery .= "\n";
                $curres = DescribeField($curfield,true);
                $curquery .= $curres[0];
                if ( $curres[1] == true ) $curprkey = $curfield->Name();
                $curquery .= ",";
            }
            if ( $curprkey != "" ) $curquery .= "\n    PRIMARY KEY ( `" . $curprkey . "` ),";
            $curquery = substr($curquery,0,-1);
            $curquery .= "\n);\n";
            $queries[] = $curquery;
        }
        
        //alter tables
        $toalter = array_intersect($beta_table_names,$stable_table_names);
        foreach($toalter as $ctablename) {
            $firsttable = New BCDbTable($ctablename,$beta_database);
            $secondtable = New BCDbTable($ctablename,$stable_database);
            $alterqueries = CompareTables($firsttable,$secondtable);
            $queries = array_merge($queries,$alterqueries);
        }

        return $queries;
    }
?>