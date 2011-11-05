<?php
    if ( !Element( "element_logged_in" ) ) {
        return false;
    }
    
    // this element should display upload progress for a given pending upload
    // its earthid is given using AJAX POST
    
    // check if earth id has been passed to the element
    if( !isset( $_POST[ "earthid" ] ) ) {
        bc_die( "Querying progress for undefined upload" );
    }
    
    // okay it's passed
    $earthid = $_POST[ "earthid" ];
    if( !ValidId( $earthid ) ) {
        // woops, seems that it's not a valid earthid
        bc_die( "Querying progress for invalid upload" );
    }
    // okay, let's see if this pending upload actually exists
    $myearth = New Earth( $earthid );

    // upload completed, since earth has been made invalid
    if( $myearth->Id() == -1 ) {
        exit();
    }
    
    // check if our fellow is querying progress percentage for another user
    if( $myearth->UserId() != $user->Id() ) {
        bc_die( "Earth Access Denied" );
    }
    
    $pb = $myearth->Progress();
    $percentage = $pb / 100;
    $pbmax = 285 - 3;
    $pb1 = $percentage * $pbmax;
    $pb2 = (1 - $percentage) * $pbmax;
    
    // progress bar
    ?><div id="earthprogressbar" style="display:none"><table style="width:285px;height:10px">
    <tr>
        <td style="background-image:url('images/pbar.png');background-repeat:no-repeat;background-position:left;width:3px">
        </td>
        <td style="background-image:url('images/pbarx.png');background-repeat:repeat-x;background-position:left;width:<?php
        echo $pb1;
        ?>px"></td>
        <td style="background-image:url('images/pbar.png');background-repeat:no-repeat;background-position:right;width:<?php
        echo $pb2;
        ?>px">
        </td>
    </tr>
    </table><br /><?php
        echo $pb;
    ?>%...</div><?php
    
    // we're now going to replace the contents of g("earthprogp") which already exists in our document
    // with the new data we have. While the current element was being downloaded, the old percentage
    // was displayed, that's why we use two tags, earthprogp and earthprogtmp
    bfc_start();
    ?>g("earthprogp").innerHTML=g('earthprogressbar').innerHTML;
    /* we should also set a new timeout so that a new de will start to display the next progress */
    setTimeout('de("earthprogptmp","blogging/earth/earth&earthid=<?php
    echo $earthid;
    ?>","","-")',1000);<?php
    bfc_end();
?>