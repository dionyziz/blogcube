<?php
    if ( !Element( "element_logged_in" ) ) {
        return false;
    }

    $filterid = $_POST["filterid"];
    $curfilter = New Filter($filterid);
    global $user;
    $userid = $user->Id();
    if (( $curfilter->FilterUserID() != $userid ) || ( $curfilter->FilterIsSpam() )) {
        h3( "Error", "error64" );
        echo "You do not have access to this filter, thus you cannot view or manage its contents.";
        bc_die();
    }
?>

<table width="100%">
    <tr>
        <td class="ffield" colspan="2"><?php 
        img('images/nuvola/2rightarrow.png'); 
        ?> <b>Filter Characteristics</b></td>
    </tr>
    <tr>
        <td class="nfield" style="width:49%;"><blockquote><?php
        echo $arrow; 
        ?>Caption:</blockquote></td>
        <td class="nfield" style="width:51%;"><input type="text" class="inbt" value="<?php 
        echo $curfilter->FilterName() 
        ?>" id ="filter_caption" size="32" /></td>
    </tr>
    <tr>
        <td class="nfield"><blockquote><?php 
        echo $arrow; 
        ?>Target:</blockquote></td>
        <td class="nfield">
        <b>Selected Target:</b><div id="filter_seltarget" class="inline"></div><br />
        <div id="filter_seltarget_id" style="display:none"></div>
        <?php
            //TODO: auto-expand all parent folders of the selected folder and show its name in "Selected target:"
            //(have to create an auto-expand function first for the folderview)
            $curfoldid = GetUserRoot($user->Id());
            if ( $curfoldid === false ) {
                ?><table><tr><td><?php 
                img('images/nuvola/error.png'); 
                ?></td><td><h4>Error</h4></td></tr></table><br />We apologise, but a technical 
                problem occured while trying to access your messages list.<br />
                Please contact a BlogCube administrator to solve this problem.<?php
            }
            else {
                $curfold = New MessageFolder($curfoldid);
                img('images/nuvola/mfolder.png'); ?> <a href="javascript:mfolder_sh('mfolder_<?php 
                echo $curfoldid; 
                ?>','<?php 
                echo $curfoldid; 
                ?>','false')"><?php
                echo "My Messages";
                $unreadcount = $curfold->CountUnread();
                if ( $unreadcount > 0 ) {
                    echo "<b>(", $unreadcount, ")</b>";
                }
                ?></a><blockquote><div style="display:none" id="mfolder_<?php 
                echo $curfoldid; 
                ?>"></blockquote><br /><?php
            }
        ?>
        </td>
    </tr>
    <tr>
        <td class="nfield" colspan="2"><?php 
        img('images/nuvola/2rightarrow.png'); 
        ?> <b>Filter Criteria</b></td>
    </tr>
    <tr>
        <td class="nfield" colspan="2"><?php 
            img('images/nuvola/newpost.png'); 
            ?><a href="javascript:mfilter_crit_sh('mfilter_addcriterion','n');">Add Criterion</a>
            <blockquote><div id="mfilter_addcriterion" style="display:none">
                <a href="javascript:mfilter_crit_sh('mfilter_crit_a','y');">If message is sent by</a><br />
                <a href="javascript:mfilter_crit_sh('mfilter_crit_b','y');">If subject contains</a><br />
                <a href="javascript:mfilter_crit_sh('mfilter_crit_c','y');">If message body contains</a><br />
            </div></blockquote>
        </td>
    </tr>
    <tr>
        <td class="nfield" colspan="2">
            <div id="mfilter_crit_a" style="display:none">
                <table style="width: 100%" class="intracted">
                    <tr>
                        <td class="ffield fintracted" style="width:49%">If message is sent by:</td>
                        <td class="nfield fintracted" style="width:51%">
                        <input type="Text" class="inbt" value="" id="filter_type1_text" size="32" />
                        </td>
                    </tr>
                    <tr>
                        <td class="nfield fintracted" colspan="2" align="right">
                        <a href="javascript:mfilter_crit_add('1');">Add</a> 
                        <a href="javascript:mfilter_crit_sh('mfilter_crit_a','n');">Cancel</a>
                        </td>
                    </tr>
                </table>
            </div>
            <div id="mfilter_crit_b" style="display:none">
                <table style="width: 100%" class="intracted">
                    <tr>
                        <td class="ffield fintracted" style="width:49%">
                        If message subject contains:
                        </td>
                        <td class="nfield fintracted" style="width:51%">
                        <input type="text" class="inbt" value="" id="filter_type2_text" size="32" />
                        </td>
                    </tr>
                    <tr>
                        <td class="nfield fintracted" colspan="2" align="right">
                        <a href="javascript:mfilter_crit_add('2');">Add</a> 
                        <a href="javascript:mfilter_crit_sh('mfilter_crit_b','n');">Cancel</a>
                        </td>
                    </tr>
                </table>
            </div>
            <div id="mfilter_crit_c" style="display:none">
                <table style="width: 100%" class="intracted">
                    <tr>
                        <td class="ffield fintracted" style="width:49%">If message body contains:</td>
                        <td class="nfield fintracted" style="width:51%">
                        <input type="text" class="inbt" value="" id="filter_type3_text" size="32" />
                        </td>
                    </tr>
                    <tr>
                        <td class="nfield fintracted" colspan="2" align="right">
                        <a href="javascript:mfilter_crit_add('3');">Add</a>
                        <a href="javascript:mfilter_crit_sh('mfilter_crit_c','n');">Cancel</a>
                        </td>
                    </tr>
                </table>
            </div>
            <div id="mfilter_fhidd" style="display: none;">
            <?php
                //output filter criteria in a form easily parsed by the js function already made
                while ($cftypeid = $curfilter->NextType()) {
                    echo $curfilter->GetTypeType() . "\t" . $curfilter->GetTypeInfo() . "\n";
                }
                bfc_start();
                //js function that parses criteria follows
            ?>
                mfilter_crit_upd();
            <?php
                bfc_end();
            ?>
            </div>
            <div id="mfilter_crit_list">
            </div>
        </td>
    </tr>
    <tr>
        <td colspan="2" align="right"><?php 
            img( "images/nuvola/add.png" ); 
            ?> <a href="javascript:mfilter_edit();">Update</a> <?php 
            img( "images/nuvola/discard.png" ); 
            ?>  <a href="#">Cancel</a>
        </td>
    </tr>
</table>
<div id="filter_addprocess">
</div>
