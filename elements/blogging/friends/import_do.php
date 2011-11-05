<?php
    if ( !Element( "element_logged_in" ) ) {
        return false;
    }

    $type = $_POST[ 'type' ];
    switch( $type ) {
        case 'geasy':
            $username = safedecode( $_POST[ 'user' ] );
            $password = safedecode( $_POST[ 'pass' ] );
            
            $wind = new Wind_gmail_easy( $username, $password );
            wind_display( $wind );
            
            break;
        case 'gcsv':
            $csv = utf8RawUrlDecode( safedecode( $_POST['csv'] ) );
            $wind = new Wind_gmail_csv( $csv );
            //echo nl2br( $wind->ShowDebug() );
            wind_display( $wind );
            break;
        default:
            ?>Not yet supported!<?php
            break;
    }
    
    function wind_display ( $wind ) {
        global $anonymous;
        global $user;
        global $arrow;

        $wind->Reset();
        $wind->Sort();
        h3( 'Contacts' );
        ?><table class="formtable"><?php
        while ( list($email, $name) = $wind->Pop() ) {
            ?><tr><td class="ffield"><?php echo $email; ?></td><td class="ffield"><?php echo $name; ?></td><?php
            if ( list($user_id, $user_username, $user_firstname, $user_lastname) = $wind->Lookup( $email ) ) {
                ?><td style="border-top: 1px solid #3B7ABF; background-color: #a0ffa0;"><a href="javascript:de('wind_profile','blogging/profile/profile_view&user=<?php echo $user_username; ?>',''); void(g('wind_profile').style.display = ''); g('wind_profile_focus').focus();">[<?php echo $user_id; ?>] <?php echo $user_lastname; ?>, <?php echo $user_firstname; ?></a><?php
                if ( !$anonymous && $user->Id() != $user_id ) {
                    ?><div style="float:right"><?php
                    echo $arrow;
                    ?><a href="javascript:de('wind_profile','blogging/friends/addfriend&char=<?php echo $user_username; ?>','');">Add to friends</a></div><?php
                }
                ?></td><?php
            }
            ?></tr><?php echo "\n";
        }
        ?></table><?php
        echo "Total of " . $wind->Count() . " contacts fetched.\n";
        if ( $wind->ambiguities > 0 ) { 
            echo "<br /><small>" . $wind->ambiguities . " ambiguities detected.</small>\n";
        }
        ?><br /><br /><a href='javascript:toggle_wind_profile();'><?php 
        echo $arrow; 
        ?>Profile</a><br /><div id="wind_profile"></div><a id="wind_profile_focus"></a><?php
        
    }
?>