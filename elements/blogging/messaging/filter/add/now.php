<?php
    if ( !Element( "element_logged_in" ) ) {
        return false;
    }

    $strcriteria = safedecode($_POST["strcriteria"]);
    $filtername = safedecode($_POST["filtername"]);
    $filtertarget = $_POST["filtertarget"];
    $allfilters = New Filters;
    $result = $allfilters->AddFilter($filtername,$filtertarget);
    if ( $result == false ) {
        img('images/nuvola/delete.png'); 
        echo $allfilters->GetErrorText();
    }
    else {
        $curfilter = New Filter($result);
        $criterion = split("\n",$strcriteria);
        foreach ($criterion as $curcrit) {
            $ccsplit = split("\t",$curcrit);
            if (( $ccsplit[0] >= 1 ) && ( $ccsplit[0] <= 3 ) && ( $ccsplit[1] != null )) {
                /*$ccsplit[1] = substr($ccsplit[1],0,strlen($ccsplit[1])-1);*/
                $curfilter->AddType($ccsplit[0],$ccsplit[1]);
            }
        }
    }
    img('images/nuvola/done.png');
    $searcharr = Array("\t","\n");
    $replacearr = Array("\\t","\\n");;
    $strcritrep = str_replace($searcharr,$replacearr,$strcriteria);
    echo $strcritrep . "<br />";
    echo "Filter successfully added";
?>