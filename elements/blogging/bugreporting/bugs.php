<?php
    if ( !Element( "element_developer" ) ) {
        return false;
    }

    h3( "Bug List" , "bug64" );
?>
<div id="buglist"></div>
<div id="tempbuglist" style="display:none;"></div>
<?php
    bfc_start();
?>
    de('tempbuglist','blogging/bugreporting/buglist<?php
        if ( isset($_POST["s"]) ) {
            echo safedecode($_POST["s"]);
        }
    ?>');
    et("Bug List");
<?php
    bfc_end();
?>