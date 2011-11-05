<?php
    function bc_ob_start( $functionname = "" ) {
        global $thisobfunc;
        global $prevobfunc;
        
        $prevobfunc = $thisobfunc;
        $thisobfunc = $functionname;
        if( $functionname ) {
            ob_start( $functionname );
        }
        else {
            ob_start();
        }
    }
    
    function bc_ob_section() {
        ob_end_flush();
        ob_start();
    }
    
    function bc_ob_fallback() {
        global $thisobfunc;
        
        $returnval = ob_get_clean();
        if( $thisobfunc ) {
            ob_start( $thisobfunc );
        }
        else {
            ob_start();
        }
        return $returnval;
    }
    
    function bc_ob_get_clean() {
        return ob_get_clean();
    }
    
    function bc_ob_end_flush() {
        global $thisobfunc;
        global $prevobfunc;
        
        $thisobfunc = $prevobfunc = "";
        ob_end_flush();
    }
?>