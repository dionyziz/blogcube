<?php
    if ( !Element( "element_developer" ) ) {
        return false;
    }
?>
Are you sure that you want to proceed to stabilization and execute the queries below?<br /><br />
<input type="button" onclick="dm('admin/stabilize/now');" value="Yes, test" />
<input type="button" onclick="if (confirm('You are about to distribute a new stabilized version publicly. This action cannot be undone. Are you sure that you want to continue?')){dm('admin/stabilize/now&public=yes');}" value="Yes, public" />
<input type="button" onclick="dm('admin/king');" value="No" />
<?php
    $queries = StabilizeDatabase();
    foreach ($queries as $curquery) {
        echo CodeHighlight($curquery, 'SQL');
    }
    if ( !count($queries) ) { ?>
        <br /><br /><i>No queries should be executed as the beta database is identical to the stable database.</i>
<?php
    }
?>