<?php
    if ( !Element( "element_logged_in" ) ) {
        return false;
    }
    
    if( $showingfilters !== true ) {
        bc_die( "Element not directly accessible" ); // /elements/blogging/messaging/filter/panel
    }

    ?><div class="blogpanel" id="viewfilterpanel_<?php
    echo $filter->FilterID()
    ?>"><table style="width:100%"><tr><td style="width:100%">
    <div style="float:left;margin-right:4px;margin-top:4px;margin-bottom:4px;"><?php
        img( "images/nuvola/mfilter48.png" , "Filter" , $filter->FilterName() , 48 , 48 ); 
    ?></div><div style="float:right;font-weight:bold"><a href="javascript:mfilter_delete('<?php
    echo $filter->FilterID();
    ?>')" style="text-decoration:none" title="Delete this filter">&times;</a></div>
    <div style="padding-top:4px"><h4 title="<?php
    // TODO: htmlspecialchars() on title!
    echo $filter->FilterName();
    ?>"><?php
    if( $defaultavatar ) {
        $bgcolor = New RGBColor( 227 , 233 , 249 );
    }
    echo TextFadeOut( $filter->FilterName() , New RGBColor( 59 , 122 , 191 ) , $bgcolor , 18 );
    ?></h4><br /><span style="font-size:85%">Contains <?php
    echo $filter->CountTypes();
    ?> criteria</span>
    <br /><a href="javascript:dm('messaging/filter/view/view&filterid=<?php 
    echo $filter->FilterID();
    ?>);">View Filter</a><br />
    <a href="javascript:dm('messaging/filter/edit/edit&filterid=<?php 
    echo $filter->FilterID();
    ?>');">Details</a>
    </div></td></tr></table></div>