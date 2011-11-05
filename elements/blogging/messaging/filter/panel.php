<?php
    if ( !Element( "element_logged_in" ) ) {
        return false;
    }

    h3( "My Filters" , "kpdf48" );
    
    $showingfilters = true;
    $allfilters = New Filters;
    while ($curfilterid = $allfilters->NextFilter()) {
            $filter = New Filter($curfilterid);
            include "elements/blogging/messaging/filter/filter.php";
    }
    $showingfilters = false;
    ?><div class="blogpanel_new" id="viewfilterpanel_new"><table style="width:100%"><tr><td style="width:100%">
    <div style="float:left;margin-right:4px;margin-top:4px;margin-bottom:4px;"><?php
        img( "images/nuvola/new48.png" , "New Filter" , "New Filter" , 48 , 48 ); 
    ?></div>
    <div style="padding-top:4px"><h4 title="Create a New Filter">New Filter</h4><br />
    <br /><?php
        echo $arrow;
    ?> <a href="javascript:dm('messaging/filter/add/new')">Create a New Filter</a><br />
    </div></td></tr></table></div>
    <br />
    <br />
    <div id="mfilter_delete"></div>
    <?php
    bfc_start(); ?>
    et("My Filters");
    <?php
    bfc_end();
?>