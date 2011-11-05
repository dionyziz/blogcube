<?php
    include "modules/module.php";

    print_r( SpellCheck(array("testing", "alskd", "good", "penuts", "suks")) );
    
    include "bc_spell.php";
    
    function SpellCheck($arrayofwords) {
        if ($arrayofwords != "") {
            $pspell_dic = pspell_new("en");
            foreach ($arrayofwords as $checkword) {
                if (!pspell_check($pspell_dic, $checkword)) {
                    $pspell_suggestions = pspell_suggest($pspell_dic, $checkword);
                    $suggestions[count($suggestions)-1][] = $i;
                    foreach ($pspell_suggestions as $suggest) {
                        $suggestions[count($suggestions)-1][] = $suggest;
                    }
                }
            }
            return $suggestions;
        }
        else {
            return false;
        }
    }
?>