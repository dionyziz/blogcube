<?php
    if ( !Element( "element_logged_in" ) ) {
        return false;
    }

    $curfilterid = $_POST["filterid"];
    $curfilter = New Filter($curfilterid);
    global $user;
    $userid = $user->Id();
    if ( $curfilter->FilterIsSpam() == true ) {
        img('images/nuvola/error.png'); ?> You do not have access to this folder. Please contact an administrator for more information. <?php
        bc_die();
    }
    if ( $curfilter->FilterUserID() != $userid ) {
        img('images/nuvola/error.png'); ?> This filter is not owned by you so you do not have access to it. Contact an administrator for more information. <?php
        bc_die();
    }
    h4( "Viewing filter: " . $curfilter->FilterName() );
    while ($x = $curfilter->NextType()) {
        echo "<b>Criterion:</b> <u>Type:</u> " . $curfilter->GetTypeType() . " <u>Information:</u> " . $curfilter->GetTypeInfo() . " <u>from 2nd:</u> " . substr($curfilter->GetTypeInfo(),2) . "<u>to 2nd:</u> " . substr($curfilter->GetTypeInfo(),0,strlen($curfilter->GetTypeInfo())-1) . "<u>Length:</u> " . strlen($curfilter->GetTypeInfo()) . "<br />";
        echo "<blockquote><a href=\"deletecriterion&critid=" . $x . "\">Remove</a></blockquote>";
    }
    echo "<b><u><i>Move To:</i></u></b> " . $curfilter->FilterTargetID(), "<br />";
    ?>
    <a href="javascript:de('msg_filterview','blogging/messaging/filter/edit/edit&filterid=<?php echo $curfilter->FilterID(); ?>');">Edit</a>
    <a href="javascript:mfilter_delete('<?php echo $curfilterid; ?>');">Remove</a>
