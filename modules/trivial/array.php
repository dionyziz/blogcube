<?php
    function ArrayDiff( $arr1 , $arr2 ) {
        //sort arrays
        sort($arr1);
        sort($arr2);
        //create new arrays
        $newarr1 = array_diff($arr1,$arr2);
        $newarr2 = array_diff($arr2,$arr1);
        //return
        return array($newarr1,$newarr2);
    }
?>
