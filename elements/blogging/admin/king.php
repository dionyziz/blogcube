<?php
    $bfc->start()
    ?>
    cacheable = true;
    et( "Administration" );
    <?php
    $bfc->end();
    h3( "Administration" , "admin64" );
?>
Let them respect our authority!
<?php
    $logs_access = in_array( $user->Username() , $permissions_logs[ 'alpha' ] ) || 
        in_array( $user->Username() , $permissions_logs[ 'blogcube' ] ) ||
        in_array( $user->Username() , $permissions_logs[ 'developers' ] );
    if( $user->IsAdmin() || $user->IsDeveloper() || $logs_access ) {
        if ( $user->IsAdmin() ) {
            ?><table><?php
            ?><tr><td class="hpitm"><?php echo $arrow; ?><a href="javascript:if((z=g('invassign')).style.display=='none')void(g('assianc').innerHTML='Cancel Invitation Assignment'+(z.style.display='')+(g('invusr').focus()?'':'')+(g('invass').innerHTML='')); else void(z.style.display='none'+((g('assianc').innerHTML='Assign Invitations')?'':''));" id="assianc">Assign Invitations</a></td></tr>
            </table>
            <div id="invassign" style="display:none;padding-left:10px">
            <h4>Assign Invitations</h4><br />
            <table>
            <tr><td>Assign invitations to which user?</td><td><input type="text" id="invusr" class="inpt" /></td></tr>
            <tr><td>How many invitations do you want to assign?</td><td><input type="text" id="numinv" class="inpt" value="2" /></td></tr>
            </table><?php
                LinkList( Array(
                    "Assign" => "javascript:invassign()" , // @js/forms
                    "Cancel" => "javascript:void(g('invassign').style.display='none'+((g('assianc').innerHTML='Assign Invitations')?'':'')+(g('invass').innerHTML=''))"
                ) );
            ?></div>
            <div id="invass"></div>
            <table>
            <tr><td class="hpitm"><?php echo $arrow; ?><a href="javascript:if((z=g('invhistory')).style.display=='none')void(g('histanc').innerHTML='Cancel Invitations History'+(z.style.display='')+(g('histusr').focus()?'':'')+(g('invhist').innerHTML='')); else void(z.style.display='none'+((g('histanc').innerHTML='Display Invitations History')?'':''));" id="histanc">Display Invitations History</a></td></tr>
            </table>
            <div id="invhistory" style="display:none;padding-left:10px">
            <h4>Invitations history</h4><br />
            <table>
            <tr><td>Display invitations of which user?</td><td><input type="text" id="histusr" class="inpt" /></td></tr>
            </table><?php
                LinkList( Array(
                    "Show" => "javascript:invhistory()" , // @js/forms
                    "Cancel" => "javascript:void(g('invhistory').style.display='none'+((g('histanc').innerHTML='Display Invitations History')?'':'')+(g('invhist').innerHTML=''))"
                ) );
            ?></div>
            <div id="invhist"></div>
            <table>
            <tr><td class="hpitm"><?php echo $arrow; ?><a href="javascript:dm('admin/userlist&start=0');">List Users</a></td></tr>
            </table><?php
        }
        ?><table><?php
        if ( $user->IsDeveloper() ) {
            ?><tr><td class="hpitm"><?php echo $arrow; ?><a href="javascript:dm('help/panel');">Manage Documentation</a></td></tr>
            <tr><td class="hpitm"><?php echo $arrow; ?><a href="javascript:dm('bugreporting/bugs');">View Bug Reports</a></td></tr>
            <tr><td class="hpitm"><?php echo $arrow; ?><a href="javascript:dm('messaging/spam/list');">Manage Spam Filters</a></td></tr> 
            <tr><td class="hpitm"><?php echo $arrow; ?><a href="javascript:dm('admin/stabilize/confirm');">Stabilize BlogCube</a></td></tr><?php 
        }
        if ( $logs_access ) {
            ?><tr><td class="hpitm"><?php echo $arrow; ?><a href="javascript:dm('admin/alpha');">IRC Log Viewer</a></td></tr><?php
        }
        ?></table><?php
    }
    else {
        bc_die( 'Access denied to administration panel' );
    }
?>